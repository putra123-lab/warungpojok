
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StockBatchController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Statistik
    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->name('statistics.index');

    // Transaksi penjualan
    Route::get('/sales', [SaleController::class, 'index'])
        ->name('sales.index');

    Route::get('/sales/create', [SaleController::class, 'create'])
        ->name('sales.create');

    Route::post('/sales', [SaleController::class, 'store'])
        ->name('sales.store');

    Route::get('/sales-report', [SaleController::class, 'report'])
        ->name('sales.report');

    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');

    Route::get('/sales/{sale}', [SaleController::class, 'show'])
        ->name('sales.show');

    // Manajemen khusus admin
    Route::middleware('role:admin')->group(function () {

        // Produk
        Route::resource('products', ProductController::class);

        // Bahan baku
        Route::resource('ingredients', IngredientController::class);

        // Pengguna
        Route::resource('users', UserController::class);

        // Stok masuk dan batch kedaluwarsa
        Route::get('/stock-batches', [StockBatchController::class, 'index'])
            ->name('stock-batches.index');

        Route::post('/stock-batches', [StockBatchController::class, 'store'])
            ->name('stock-batches.store');
    });
});

require __DIR__.'/auth.php';
