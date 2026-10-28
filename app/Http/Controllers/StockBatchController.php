<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientBatch;
use App\Models\Product;
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockBatchController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        $ingredients = Ingredient::orderBy('name')->get();

        $productBatches = ProductBatch::with('product')
            ->latest()
            ->get();

        $ingredientBatches = IngredientBatch::with('ingredient')
            ->latest()
            ->get();

        return view('stock-batches.index', compact(
            'products',
            'ingredients',
            'productBatches',
            'ingredientBatches'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,ingredient'],
            'item_id' => ['required', 'integer', 'min:1'],
            'received_date' => ['required', 'date'],
            'expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:received_date',
            ],
            'quantity' => ['required', 'numeric', 'gt:0', 'max:999999999'],
        ]);

        DB::transaction(function () use ($validated) {
            $quantity = $validated['quantity'];

            if ($validated['type'] === 'product') {
                $item = Product::whereKey($validated['item_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                ProductBatch::create([
                    'product_id' => $item->id,
                    'received_date' => $validated['received_date'],
                    'expiry_date' => $validated['expiry_date'] ?? null,
                    'initial_quantity' => $quantity,
                    'remaining_quantity' => $quantity,
                ]);

                $item->increment('stock', $quantity);
            } else {
                $item = Ingredient::whereKey($validated['item_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                IngredientBatch::create([
                    'ingredient_id' => $item->id,
                    'received_date' => $validated['received_date'],
                    'expiry_date' => $validated['expiry_date'] ?? null,
                    'initial_quantity' => $quantity,
                    'remaining_quantity' => $quantity,
                ]);

                $item->increment('stock', $quantity);
            }
        });

        return redirect()
            ->route('stock-batches.index')
            ->with('success', 'Stok masuk berhasil disimpan.');
    }
}
