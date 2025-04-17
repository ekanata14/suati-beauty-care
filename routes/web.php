<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controller

use App\Http\Controllers\Admin\AdminController as AdminAdminDashboardController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductDashboardController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/admin', [AdminAdminDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.admin');

Route::get('/admin/pelanggan', [AdminPelangganDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.pelanggan');

Route::get('/admin/product', [AdminProductDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.product');
Route::get('/admin/transaction', [AdminTransactionDashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.transaction');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
