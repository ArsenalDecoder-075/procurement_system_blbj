<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RawMaterialController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SupplierMaterialController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReceivingController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('purchase-orders', PurchaseOrderController::class);
Route::resource('receivings', ReceivingController::class);
Route::resource('raw-materials', RawMaterialController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('stocks', StockController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('/', [DashboardController::class, 'index'])->name('home');

// Master Data
// Supplier Routes
Route::resource('suppliers', SupplierController::class);

// Routes khusus untuk halaman harga
Route::get('/suppliers/{supplier}/prices', [SupplierController::class, 'prices'])
    ->name('suppliers.prices');

// Route untuk menambah harga material
Route::post('/suppliers/{supplier}/add-material-price', [SupplierController::class, 'addMaterialPrice'])
    ->name('suppliers.add-material-price');

// Route untuk menghapus harga material
Route::delete('/suppliers/{supplier}/remove-material-price', [SupplierController::class, 'removeMaterialPrice'])
    ->name('suppliers.remove-material-price');

Route::resource('raw-materials', RawMaterialController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('supplier-materials', SupplierMaterialController::class);

// Transaksi
Route::resource('purchase-orders', PurchaseOrderController::class);
Route::resource('receivings', ReceivingController::class);

// Manajemen Stok
Route::resource('stocks', StockController::class);
Route::get('/stocks/adjustment', [StockController::class, 'adjustment'])->name('stocks.adjustment');
Route::post('/stocks/adjustment', [StockController::class, 'storeAdjustment'])->name('stocks.adjustment.store');
Route::get('/stocks/transfer', [StockController::class, 'transfer'])->name('stocks.transfer');
Route::post('/stocks/transfer', [StockController::class, 'storeTransfer'])->name('stocks.transfer.store');

require __DIR__.'/auth.php';
