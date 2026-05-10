<?php

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactSettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemVariantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\RecommendationRuleController;
use App\Http\Controllers\RentalBookingController;
use App\Http\Controllers\RiasController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::get('/katalog', [IndexController::class, 'catalog'])->name('catalog');
Route::get('/katalog/{item}', [IndexController::class, 'showItem'])->name('catalog.show');
Route::get('/bundle/{bundle}', [IndexController::class, 'showBundle'])->name('bundle.show');

Route::get('/cek-ketersediaan-varian', [AvailabilityController::class, 'variant'])->name('availability.variant');

Route::get('/aksesoris', [IndexController::class, 'accessories'])->name('accessories.index');
Route::get('/rias', [RiasController::class, 'index'])->name('rias.index');

Route::get('/rekomendasi', [RecommendationController::class, 'index'])->name('recommendation.index');
Route::post('/rekomendasi', [RecommendationController::class, 'recommend'])->name('recommendation.process');

Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{item}', [CartController::class, 'add'])->name('cart.add');
Route::post('/keranjang/update-tanggal', [CartController::class, 'updateDates'])->name('cart.updateDates');
Route::post('/keranjang/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/checkout/keranjang', [CheckoutController::class, 'showCartCheckout'])->name('checkout.cart.show');
Route::post('/checkout/keranjang', [CheckoutController::class, 'storeCartCheckout'])->name('checkout.cart.store');

Route::get('/checkout/bundle/{bundle}', [CheckoutController::class, 'showBundleCheckout'])->name('checkout.bundle.show');
Route::post('/checkout/bundle/{bundle}', [CheckoutController::class, 'storeBundleCheckout'])->name('checkout.bundle.store');

Route::get('/checkout/success/{orderCode}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/status/{orderCode}', [CheckoutController::class, 'paymentStatus'])->name('checkout.payment.status');
Route::get('/checkout/receipt/{orderCode}', [CheckoutController::class, 'receipt'])->name('checkout.receipt');

Route::get('/cek-pesanan', [OrderTrackingController::class, 'index'])->name('order.track.index');
Route::post('/cek-pesanan', [OrderTrackingController::class, 'check'])->name('order.track.check');

Route::post('/midtrans/notification', [PaymentController::class, 'midtransNotification'])->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);

    Route::resource('items', ItemController::class)->except(['show']);
    Route::post('/items/{item}/toggle-status', [ItemController::class, 'updateStatus'])->name('items.updateStatus');
    Route::resource('item-variants', ItemVariantController::class)->except(['show']);
    Route::patch('/item-variants/{itemVariant}/stock', [ItemVariantController::class, 'updateStock'])->name('item-variants.updateStock');

    Route::resource('bundles', BundleController::class)->except(['show']);
    Route::resource('recommendation-rules', RecommendationRuleController::class)->except(['show']);
    Route::resource('contact-settings', ContactSettingController::class)->except(['show']);

    Route::resource('orders', OrderController::class)->only(['index', 'show']);
    Route::post('/orders/expire-pending', [OrderController::class, 'expirePendingOrders'])->name('orders.expirePending');
    Route::post('/orders/{order}/expire', [OrderController::class, 'expireSingle'])->name('orders.expireSingle');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::patch('/rental-bookings/{rentalBooking}/return', [RentalBookingController::class, 'returnItems'])->name('rental-bookings.return');
    Route::resource('rental-bookings', RentalBookingController::class)->except(['show']);

    Route::resource('payments', PaymentController::class)->except(['show']);
});
