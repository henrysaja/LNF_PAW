<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;


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

    // Route untuk membuat laporan barang hilang/ditemukan
    Route::get('/item/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    Route::patch('/items/{id}/cancel', [ItemController::class, 'cancel'])->name('items.cancel');

    // Route untuk mengajukan klaim pada barang tertentu
    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.updateStatus');

    // Letakkan di dalam grup auth yang sudah ada
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk menandai barang sebagai "Ditemukan" (hanya untuk pemilik laporan)
    Route::get('/item/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::patch('/claims/{claim}/status', [ClaimController::class, 'updateStatus'])->name('claims.updateStatus');

    // Route untuk menandai barang sebagai "Ditemukan" (hanya untuk pemilik laporan)
    Route::patch('/items/{item}/mark-as-found', [ItemController::class, 'markAsFound'])->name('items.markAsFound');

    // Route Khusus Admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Route untuk mengelola profil pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
