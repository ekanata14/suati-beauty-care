<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controller

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminController as AdminAdminDashboardController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductDashboardController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionDashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/admin', [AdminAdminDashboardController::class, 'index'])->name('admin.admin.index');
    Route::get('/admin/admin/create', [AdminAdminDashboardController::class, 'create'])->name('admin.admin.create');
    Route::post('/admin/admin', [AdminAdminDashboardController::class, 'store'])->name('admin.admin.store');
    Route::get('/admin/admin/edit/{id}', [AdminAdminDashboardController::class, 'edit'])->name('admin.admin.edit');
    Route::put('/admin/admin/update', [AdminAdminDashboardController::class, 'update'])->name('admin.admin.update');
    Route::delete('/admin/admin/delete', [AdminAdminDashboardController::class, 'destroy'])->name('admin.admin.destroy');
    Route::get('/admin/pelanggan', [AdminPelangganDashboardController::class, 'index'])->name('admin.pelanggan.index');

    Route::get('/admin/product', [AdminProductDashboardController::class, 'index'])->name('admin.product.index');
    Route::get('/admin/transaction', [AdminTransactionDashboardController::class, 'index'])->name('admin.transaction.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
