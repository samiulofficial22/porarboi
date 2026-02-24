<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

// Maintenance Routes (Use only for first time or after DB changes)
Route::get('/init-db', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return "Database Migration and Seeding Successful! <br><br> Logs: <br>" . Artisan::output();
    }
    catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Public Routes
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/', [HomeController::class , 'index'])->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::get('/categories', [CategoryController::class , 'index'])->name('categories.index');
Route::get('/privacy-policy', function () {
    return view('privacy');
})->name('privacy');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');
Route::get('/book/{book}', [HomeController::class , 'show'])->name('book.show');

Route::get('/robots.txt', function () {
    $content = setting('robots_txt', "User-agent: *\nAllow: /");
    return response($content)->header('Content-Type', 'text/plain');
});

// Free Download Routes
Route::get('/free-download/{book}', [\App\Http\Controllers\User\FreeDownloadController::class , 'process'])->name('free.download');
Route::get('/download-file/{order}/{book}', [\App\Http\Controllers\User\FreeDownloadController::class , 'downloadFile'])->name('free.download.file');

// Google Auth
Route::get('auth/google', [GoogleController::class , 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class , 'handleGoogleCallback']);
Route::post('logout', [GoogleController::class , 'logout'])->name('logout');

// Standard Auth
Route::get('login', [\App\Http\Controllers\Auth\AuthController::class , 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class , 'login'])->name('login.post');
Route::get('register', [\App\Http\Controllers\Auth\AuthController::class , 'showRegisterForm'])->name('register');
Route::post('register', [\App\Http\Controllers\Auth\AuthController::class , 'register'])->name('register.post');

// User Routes (Protected)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class , 'index'])->name('dashboard');
    Route::get('/checkout', [UserOrderController::class , 'cartCheckout'])->name('cart.checkout');
    Route::get('/checkout/{book}', [UserOrderController::class , 'checkout'])->name('checkout');
    Route::post('/order', [UserOrderController::class , 'store'])->name('order.store');
    Route::get('/my-books', [UserOrderController::class , 'myBooks'])->name('my-books');
    Route::get('/download/{book}', [UserOrderController::class , 'downloadPdf'])->name('download');
    Route::get('/history', [UserOrderController::class , 'history'])->name('history');
    Route::delete('/orders/{order}/cancel', [UserOrderController::class , 'cancel'])->name('orders.cancel');
    Route::get('/notifications', [UserDashboardController::class , 'notifications'])->name('notifications.index');

    // Cart Routes
    Route::post('/cart/add/{book}', [\App\Http\Controllers\User\CartController::class , 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartKey}', [\App\Http\Controllers\User\CartController::class , 'remove'])->where('cartKey', '[a-zA-Z0-9_-]+')->name('cart.remove');
    Route::get('/cart/items', [\App\Http\Controllers\User\CartController::class , 'getCart'])->name('cart.items');

    Route::get('/profile', [UserDashboardController::class , 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class , 'updateProfile'])->name('profile.update');
    Route::post('/notifications/{id}/read', [UserDashboardController::class , 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [UserDashboardController::class , 'markAllAsRead'])->name('notifications.markAllRead');
});

// Admin Routes (Protected + Admin Middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class , 'index'])->name('dashboard');
    Route::get('/notifications', [AdminDashboardController::class , 'notifications'])->name('notifications.index');

    // Books Management
    Route::resource('books', AdminBookController::class);

    // Categories Management
    Route::resource('categories', AdminCategoryController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class , 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class , 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class , 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}', [AdminOrderController::class , 'destroy'])->name('orders.destroy');

    // Users Management
    Route::get('/users', [UserController::class , 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class , 'show'])->name('users.show');
    Route::delete('/users/{user}', [UserController::class , 'destroy'])->name('users.destroy');

    // Settings Management
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class , 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class , 'update'])->name('settings.update');
});
