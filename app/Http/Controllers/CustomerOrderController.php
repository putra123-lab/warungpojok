<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Menampilkan Menu Pelanggan
    |--------------------------------------------------------------------------
    */

    public function menu(string $token)
    {
        $table = DiningTable::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('customer.menu', compact(
            'table',
            'products'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Menyimpan Pesanan Pelanggan
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, string $token)
    {
        $table = DiningTable::where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $quantities = collect($validated['items'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($quantities->isEmpty()) {
            throw ValidationException::withMessages([
                'items' => 'Pilih minimal satu produk.',
            ]);
        }

        $order = DB::transaction(function () use (
            $table,
            $validated,
            $quantities
        ) {
            $products = Product::whereIn('id', $quantities->keys())
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $quantities->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Ada produk yang sudah tidak tersedia.',
                ]);
            }

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                if ($quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi.",
                    ]);
                }
            }

            $order = Order::create([
                'order_number' => 'QR-' .
                    now()->format('ymd') . '-' .
                    Str::upper(Str::random(6)),

                'dining_table_id' => $table->id,
                'customer_name' => $validated['customer_name'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'menunggu',
            ]);

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('customer.menu', ['token' => $token])
            ->with(
                'success',
                'Pesanan berhasil dikirim. Nomor pesanan: ' .
                $order->order_number
            );
    }
}
