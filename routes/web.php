<?php

use App\Http\Controllers\KantinController;

Route::get('/', [KantinController::class, 'vendor']);
Route::get('/menu/{id}', [KantinController::class, 'menu']);
Route::post('/pesan', [KantinController::class, 'pesan']);
Route::get('/pesanan', [KantinController::class, 'pesanan']);
