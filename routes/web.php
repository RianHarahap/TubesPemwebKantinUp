<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman Login
Route::get('/', function () { return view('login'); })->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Proteksi Halaman Dashboard
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/home', function () { return view('user.dashboard'); })->name('user.dashboard');
});

// Group Halaman ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Group Halaman PENJUAL
Route::middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/penjual/dashboard', function () {
        return view('penjual.dashboard');
    })->name('penjual.dashboard');
});