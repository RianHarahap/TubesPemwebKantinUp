<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --- AUTH & GUEST ---
Route::get('/', function () { return view('login'); })->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () { return view('auth.forgot-password'); })
    ->middleware('guest')->name('password.request');

// --- GROUP USER / MAHASISWA ---
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/home', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/user/kantin/{id}', [UserController::class, 'detail'])->name('user.kantin');
    
    // Route Pelengkap Sidebar agar tidak error
    Route::get('/user/favorit', function() { return "Halaman Favorit"; })->name('user.favorit');
    Route::get('/user/riwayat', function() { return "Halaman Riwayat"; })->name('user.history');
    Route::get('/user/wallet', function() { return "Halaman Digital Wallet"; })->name('user.wallet');
});

// --- GROUP ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/kelola-vendor', [App\Http\Controllers\AdminController::class, 'kelolaVendor'])->name('kelola-vendor');
    Route::get('/kelola-vendor/create', [App\Http\Controllers\AdminController::class, 'createVendor'])->name('kelola-vendor.create');
    Route::post('/kelola-vendor', [App\Http\Controllers\AdminController::class, 'storeVendor'])->name('kelola-vendor.store');
    Route::get('/kelola-vendor/{vendor}', [App\Http\Controllers\AdminController::class, 'showVendor'])->name('kelola-vendor.show');
    Route::get('/kelola-vendor/{vendor}/edit', [App\Http\Controllers\AdminController::class, 'editVendor'])->name('kelola-vendor.edit');
    Route::put('/kelola-vendor/{vendor}', [App\Http\Controllers\AdminController::class, 'updateVendor'])->name('kelola-vendor.update');
    Route::patch('/kelola-vendor/{vendor}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleVendorStatus'])->name('kelola-vendor.toggle-status');
    Route::delete('/kelola-vendor/{vendor}', [App\Http\Controllers\AdminController::class, 'destroyVendor'])->name('kelola-vendor.destroy');
    Route::get('/laporan-transaksi', [App\Http\Controllers\AdminController::class, 'laporanTransaksi'])->name('laporan-transaksi');
});

// --- GROUP PENJUAL ---
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Penjual\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Penjual\DashboardController::class, 'index'])->name('dashboard');
    
    // Kelola Menu (penjual hanya boleh mengelola menu miliknya)
    Route::resource('menus', \App\Http\Controllers\Penjual\MenuController::class);
    
    // Pesanan Masuk
    Route::get('/orders', [\App\Http\Controllers\Penjual\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Penjual\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [\App\Http\Controllers\Penjual\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Saldo & Riwayat Pesanan
    Route::get('/saldo', [\App\Http\Controllers\Penjual\SaldoController::class, 'index'])->name('saldo.index');
    Route::get('/saldo/{id}/detail', [\App\Http\Controllers\Penjual\SaldoController::class, 'detail'])->name('saldo.detail');
});

// --- API Routes ---
Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
    Route::get('/vendor/{vendor}/stats', [App\Http\Controllers\AdminController::class, 'getVendorStats']);
});