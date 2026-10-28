<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     */
    public function index()
    {
        $sales = Sale::with('user')
            ->latest()
            ->get();

        return view('sales.index', compact('sales'));
    }

    /**
     * Menampilkan halaman kasir.
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    /**
     * Menyimpan transaksi penjualan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => 'required|integer|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:tunai,qris,transfer',
        ]);

        // Abaikan produk yang jumlahnya nol.
        $items = array_values(array_filter(
            $validated['items'],
            fn ($item) => (int) $item['quantity'] > 0
        ));

        if (count($items) === 0) {
            throw ValidationException::withMessages([
                'items' => 'Pilih minimal satu produk untuk transaksi.',
            ]);
        }

        $sale = DB::transaction(function () use ($validated, $items) {
            $total = 0;
            $cart = [];

            // Validasi produk dan stok.
            foreach ($items as $item) {
                $product = Product::where('is_active', true)
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => 'Ada produk yang tidak aktif atau tidak ditemukan.',
                    ]);
                }

                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$product->name} tidak mencukupi.",
                    ]);
                }

                $subtotal = (float) $product->price * $quantity;
                $total += $subtotal;

                $cart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'price' => $product->price,
                ];
            }

            // Validasi pembayaran.
            if ((float) $validated['paid_amount'] < $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Uang pembayaran kurang dari total belanja.',
                ]);
            }

            // Hitung kebutuhan bahan baku sesuai resep.
            $ingredientUsages = [];

            foreach ($cart as $item) {
                $rules = DB::table('product_ingredients')
                    ->where('product_id', $item['product']->id)
                    ->get();

                foreach ($rules as $rule) {
                    $ingredientId = $rule->ingredient_id;

                    $required = (float) $rule->quantity_used
                        * $item['quantity'];

                    $ingredientUsages[$ingredientId] =
                        ($ingredientUsages[$ingredientId] ?? 0)
                        + $required;
                }
            }

            // Pastikan stok bahan baku mencukupi.
            foreach ($ingredientUsages as $ingredientId => $required) {
                $ingredient = DB::table('ingredients')
                    ->where('id', $ingredientId)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$ingredient ||
                    (float) $ingredient->stock < $required
                ) {
                    $name = $ingredient->name ?? 'Bahan baku';

                    throw ValidationException::withMessages([
                        'items' => "Stok bahan baku {$name} tidak mencukupi.",
                    ]);
                }
            }

            // Simpan transaksi utama.
            $sale = Sale::create([
                'invoice_number' => 'INV-' . now()->format('YmdHis')
                    . '-' . strtoupper(bin2hex(random_bytes(3))),
                'user_id' => auth()->id(),
                'total' => $total,
                'paid_amount' => $validated['paid_amount'],
                'change_amount' =>
                    (float) $validated['paid_amount'] - $total,
                'payment_method' => $validated['payment_method'],
                'status' => 'selesai',
            ]);

            // Simpan detail transaksi dan kurangi stok produk.
            foreach ($cart as $item) {
                $product = $item['product'];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // Kurangi stok bahan baku.
            foreach ($ingredientUsages as $ingredientId => $required) {
                DB::table('ingredients')
                    ->where('id', $ingredientId)
                    ->decrement('stock', $required);
            }

            return $sale;
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Menampilkan laporan penjualan berdasarkan tanggal.
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $startDate = $validated['start_date']
            ?? now()->toDateString();

        $endDate = $validated['end_date']
            ?? now()->toDateString();

        $query = Sale::with(['user', 'items.product'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        // Daftar transaksi.
        $sales = (clone $query)
            ->latest()
            ->get();

        // Total omzet.
        $totalOmzet = (clone $query)
            ->sum('total');

        // Jumlah transaksi.
        $jumlahTransaksi = $sales->count();

        // Jumlah produk terjual.
        $totalProdukTerjual = $sales->sum(function ($sale) {
            return $sale->items->sum('quantity');
        });

        // Lima produk terlaris.
        $produkTerlaris = $sales
            ->flatMap(function ($sale) {
                return $sale->items;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                $item = $items->first();

                return [
                    'nama' => $item->product_name
                        ?: ($item->product?->name ?? 'Produk dihapus'),
                    'jumlah' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('jumlah')
            ->take(5);

        return view('sales.report', compact(
            'sales',
            'startDate',
            'endDate',
            'totalOmzet',
            'jumlahTransaksi',
            'totalProdukTerjual',
            'produkTerlaris'
        ));
    }
}
