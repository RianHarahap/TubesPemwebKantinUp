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
    
    // Fitur Favorit
    Route::get('/user/favorit', [UserController::class, 'favorit'])->name('user.favorit');
    Route::post('/user/toggle-favorite', [UserController::class, 'toggleFavorite'])->name('user.toggle-favorite');

    // Route Pesanan
    Route::get('/user/riwayat', [App\Http\Controllers\KantinController::class, 'pesanan'])->name('user.history');
    Route::post('/user/pesan', [App\Http\Controllers\KantinController::class, 'pesan'])->name('user.pesan'); 
    
    // Fitur Wallet
    Route::get('/user/wallet', [UserController::class, 'wallet'])->name('user.wallet');
    Route::post('/user/top-up', [UserController::class, 'topUp'])->name('user.top-up');
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

// --- FITUR FORGOT PASSWORD ---

// 1. Halaman Form Minta Link (GET)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

// 2. Proses Kirim Email Link Reset (POST) - INI YANG TADI ERROR
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

// 3. Halaman Form Ketik Password Baru (GET - Dipicu dari link di email)
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// 4. Proses Update Password Baru ke Database (POST)
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');