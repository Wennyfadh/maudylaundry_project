<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Akses Bebas Pelanggan / Publik)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicOrderController::class, 'index'])->name('home');
Route::get('/order', [PublicOrderController::class, 'index'])->name('public.order');
Route::post('/order', [PublicOrderController::class, 'storeOrder'])->name('public.order.store');
Route::get('/order/success/{id}', [PublicOrderController::class, 'success'])->name('public.success');
Route::post('/order/{id}/confirm-qris', [PublicOrderController::class, 'confirmQrisPayment'])->name('public.qris.confirm');
Route::get('/tracking', [PublicOrderController::class, 'tracking'])->name('public.tracking');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Internal Routes (Owner & Multi-Staff)
|--------------------------------------------------------------------------
*/
Route::middleware('role:owner,staff')->group(function () {
    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Transaksi Pesanan & Pembayaran
    Route::resource('pesanan', PesananController::class);
    Route::patch('/pesanan/{id}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::get('/pesanan/{id}/nota', [PesananController::class, 'nota'])->name('pesanan.nota');

    Route::resource('pembayaran', PembayaranController::class);
    Route::get('/pembayaran/{id}/struk', [PembayaranController::class, 'struk'])->name('pembayaran.struk');

    // 3. Pengadaan Stok / Purchases & Suppliers (PDF 1 Requirement)
    Route::resource('purchases', PurchaseController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);

    // 4. Data Master (Pelanggan, Layanan, Inventaris)
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('inventaris', InventarisController::class);

    // 5. Laporan & Cetak Laporan (Harian, Mingguan, Bulanan, Tahunan, Detail Transaksi)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
});

/*
|--------------------------------------------------------------------------
| Owner-Only Protected Routes (Role-Based Access Control)
|--------------------------------------------------------------------------
*/
Route::middleware('role:owner')->group(function () {
    Route::resource('pegawai', PegawaiController::class);
});
