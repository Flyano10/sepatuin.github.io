<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\AdminOrderController;

// ✅ Halaman utama & detail produk
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ✅ Login Admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// ✅ Dashboard User
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Grup route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // 🔒 Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete_photo');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');

    // 🛒 Cart & Checkout
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/process-payment', [CheckoutController::class, 'processPayment'])->name('checkout.process-payment');

    // ✅ Halaman sukses setelah checkout
    Route::get('/checkout/success', function () {
        return view('checkout.success');
    })->name('checkout.success');

    // 📦 Riwayat Pesanan User
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');

    // Wishlist
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [\App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Review Produk
    Route::post('/products/{product}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::delete('/products/{product}/review', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('review.destroy');

    // 🛠️ Admin Area — hanya untuk admin
    Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
        // 📦 Kelola Produk
        Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Route hapus gambar galeri produk (admin)
        Route::delete('/products/image/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');

        // 📄 Kelola Pesanan
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::get('/orders/export-pdf', [AdminOrderController::class, 'exportPdf'])->name('orders.exportPdf');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    });
});

// ✅ Halaman statis tambahan (About & Contact)
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Sitemap.xml
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index']);

require __DIR__ . '/auth.php';
