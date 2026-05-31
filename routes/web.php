<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\AuthController;

// Route Otentikasi (Bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('items.index');
});

// Route Daftar Barang dan Detail (Bisa dilihat tanpa login)
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// Route yang WAJIB Login
Route::middleware('auth')->group(function () {
    Route::get('/item/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.updateStatus');
});
