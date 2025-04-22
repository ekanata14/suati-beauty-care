<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controller
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AdminController as AdminAdminDashboardController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductDashboardController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionDashboardController;

// Client Controller
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;

Route::get('/', [ClientDashboardController::class, 'index'])->name('home');
Route::get('/about', [ClientDashboardController::class, 'about'])->name('about');
Route::get('/products', [ClientDashboardController::class, 'products'])->name('products');
Route::get('/products/category/{id}', [ClientDashboardController::class, 'productsCategory'])->name('products.category');
Route::get('/products/{id}', [ClientDashboardController::class, 'productDetail'])->name('products.detail');
Route::get('/products/search', [ClientDashboardController::class, 'searchProducts'])->name('products.search');

Route::post('/order/addToOrder', [ClientDashboardController::class, 'addToOrder'])->name('addToOrder');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/cart', [ClientDashboardController::class, 'cart'])->name('cart');
    Route::post('/order/delete-from-cart', [ClientDashboardController::class, 'deleteFromCart'])->name('delete.item');
    Route::post('/checkout', [ClientDashboardController::class, 'checkout'])->name('checkout');
    Route::get('/upload-payment/{id}', [ClientDashboardController::class, 'uploadPayment'])->name('cart.upload.payment');
    Route::post('/upload-payment', [ClientDashboardController::class, 'uploadPaymentStore'])->name('upload.payment.store');
    Route::get('/history', [ClientDashboardController::class, 'history'])->name('history');
    Route::get('/history/detail/{id}', [ClientDashboardController::class, 'historyDetail'])->name('history.detail');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Admin Admin Routes
    Route::get('/admin/admin', [AdminAdminDashboardController::class, 'index'])->name('admin.admin.index');
    Route::get('/admin/admin/create', [AdminAdminDashboardController::class, 'create'])->name('admin.admin.create');
    Route::post('/admin/admin', [AdminAdminDashboardController::class, 'store'])->name('admin.admin.store');
    Route::get('/admin/admin/edit/{id}', [AdminAdminDashboardController::class, 'edit'])->name('admin.admin.edit');
    Route::put('/admin/admin/update', [AdminAdminDashboardController::class, 'update'])->name('admin.admin.update');
    Route::delete('/admin/admin/delete', [AdminAdminDashboardController::class, 'destroy'])->name('admin.admin.destroy');

    // Admin Pelanggan Routes
    Route::get('/admin/pelanggan', [AdminPelangganDashboardController::class, 'index'])->name('admin.pelanggan.index');
    Route::get('/admin/pelanggan/create', [AdminPelangganDashboardController::class, 'create'])->name('admin.pelanggan.create');
    Route::post('/admin/pelanggan', [AdminPelangganDashboardController::class, 'store'])->name('admin.pelanggan.store');
    Route::get('/admin/pelanggan/edit/{id}', [AdminPelangganDashboardController::class, 'edit'])->name('admin.pelanggan.edit');
    Route::put('/admin/pelanggan/update', [AdminPelangganDashboardController::class, 'update'])->name('admin.pelanggan.update');
    Route::delete('/admin/pelanggan/delete', [AdminPelangganDashboardController::class, 'destroy'])->name('admin.pelanggan.destroy');

    // Admin Category Routes
    Route::get('/admin/category', [AdminCategoryController::class, 'index'])->name('admin.category.index');
    Route::get('/admin/category/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/admin/category', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/admin/category/delete', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');

    // Admin Product Routes
    Route::get('/admin/product', [AdminProductDashboardController::class, 'index'])->name('admin.product.index');
    Route::get('/admin/product/create', [AdminProductDashboardController::class, 'create'])->name('admin.product.create');
    Route::post('/admin/product', [AdminProductDashboardController::class, 'store'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminProductDashboardController::class, 'edit'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminProductDashboardController::class, 'update'])->name('admin.product.update');
    Route::delete('/admin/product/delete', [AdminProductDashboardController::class, 'destroy'])->name('admin.product.destroy');

    // Admin Transaction Routes
    Route::get('/admin/transaction', [AdminTransactionDashboardController::class, 'index'])->name('admin.transaction.index');
    Route::put('/admin/transaction/update-status', [AdminTransactionDashboardController::class, 'updateStatus'])->name('admin.transaction.update.status');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
