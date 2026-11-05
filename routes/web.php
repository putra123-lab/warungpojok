
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\StockBatchController;
use App\Http\Controllers\LoginActivityController;
use App\Http\Controllers\DiningTableController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| PEMESANAN PELANGGAN MELALUI QR CODE
|--------------------------------------------------------------------------
| Dapat diakses tanpa login.
*/

Route::get('/menu/meja/{token}', [CustomerOrderController::class, 'menu'])
    ->name('customer.menu');

Route::post('/menu/meja/{token}', [CustomerOrderController::class, 'store'])
    ->name('customer.orders.store');

/*
|--------------------------------------------------------------------------
| ROUTE PENGGUNA LOGIN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Statistik
    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->name('statistics.index');

    // Transaksi kasir
    Route::get('/sales', [SaleController::class, 'index'])
        ->name('sales.index');

    Route::get('/sales/create', [SaleController::class, 'create'])
        ->name('sales.create');

    Route::post('/sales', [SaleController::class, 'store'])
        ->name('sales.store');

    // Laporan penjualan
    Route::get('/sales-report', [SaleController::class, 'report'])
        ->name('sales.report');

    // Struk dan detail penjualan
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');

    Route::get('/sales/{sale}', [SaleController::class, 'show'])
        ->name('sales.show');

    /*
    |--------------------------------------------------------------------------
    | PESANAN QR CODE
    |--------------------------------------------------------------------------
    | Dapat diakses admin dan karyawan.
    */

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    /*
    |--------------------------------------------------------------------------
    | KHUSUS ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')->group(function () {

        // Manajemen produk
        Route::resource('products', ProductController::class);

        // Manajemen bahan baku
        Route::resource('ingredients', IngredientController::class);

        // Manajemen pengguna
        Route::resource('users', UserController::class);

        // Manajemen stok
        Route::get('/stock-batches', [StockBatchController::class, 'index'])
            ->name('stock-batches.index');

        Route::post('/stock-batches', [StockBatchController::class, 'store'])
            ->name('stock-batches.store');

        // Aktivitas login dan logout
        Route::get('/login-activities', [LoginActivityController::class, 'index'])
            ->name('login-activities.index');

        // Manajemen meja dan QR Code
        Route::get('/dining-tables/print', [DiningTableController::class, 'print'])
            ->name('dining-tables.print');

        Route::resource('dining-tables', DiningTableController::class)
            ->parameters([
                'dining-tables' => 'diningTable',
            ])
            ->except(['show']);
    });
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
