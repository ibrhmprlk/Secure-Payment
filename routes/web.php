<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/error', [CheckoutController::class, 'error'])->name('checkout.error');