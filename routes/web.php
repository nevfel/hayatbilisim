<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Subscribed;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\KuveytPosController;
use App\Http\Controllers\QuickPaymentController;
use Inertia\Inertia;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Sözleşme sayfaları (herkese açık)
Route::get('/kvkk', function () {
    return Inertia::render('Legal/KVKK');
})->name('kvkk');

Route::get('/terms', function () {
    return Inertia::render('Legal/Terms');
})->name('terms');

Route::get('/privacy', function () {
    return Inertia::render('Legal/Privacy');
})->name('privacy');

// Ürün (herkese açık - giriş gerektirmez)
Route::get('/erp-cozumu', [ProductController::class, 'show'])->name('product.show');

// Sepet (herkese açık - giriş gerektirmez)
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/', [CartController::class, 'store'])->name('cart.store');
    Route::put('/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
});

// Siparişler (herkese açık - giriş gerektirmez)
Route::prefix('orders')->group(function () {
    Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
});

// Ödemeler (herkese açık - giriş gerektirmez) - Kredi Kartı Ödemesi
Route::prefix('payment')->group(function () {
    Route::get('/{order}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::post('/{order}/start-3d', [PaymentController::class, 'start3DPayment'])->name('payment.start-3d');
    Route::get('/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/{order}/failed', [PaymentController::class, 'failed'])->name('payment.failed');
});

// Kuveyt POS Callback Routes (herkese açık)
Route::prefix('kuveytpos')->group(function () {
    Route::post('/success', [KuveytPosController::class, 'success'])->name('kuveytpos.success');
    Route::post('/fail', [KuveytPosController::class, 'fail'])->name('kuveytpos.fail');
    Route::post('/callback', [KuveytPosController::class, 'callback'])->name('kuveytpos.callback');
    Route::post('/complete-3d', [KuveytPosController::class, 'complete3DSecure'])->name('kuveytpos.complete-3d');
    Route::post('/pay-direct', [KuveytPosController::class, 'payDirect'])->name('kuveytpos.pay-direct');
});

// Hızlı Ödeme Routes (herkese açık)
Route::prefix('quick-payment')->group(function () {
    Route::get('/{payment_number}', [QuickPaymentController::class, 'show'])->name('quick-payment.show');
    Route::post('/{payment_number}/pay', [QuickPaymentController::class, 'pay'])->name('quick-payment.pay');
    Route::get('/success/{payment_number}', [QuickPaymentController::class, 'success'])->name('quick-payment.success');
});

// Auth routes - jetsream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Checkout routes
    Route::prefix('checkout')->group(function () {
        Route::get('/success', function () {
            return Inertia::render('CheckoutSuccess');
        })->name('checkout-success');

        Route::get('/cancel', function () {
            return Inertia::render('CheckoutCancel');
        })->name('checkout-cancel');

        Route::get('subscription/{price_id}', function (Request $request, string $price_id) {
            // return error if no price_id
            if (!$price_id) {
                return redirect()->route('dashboard');
            }
            return $request->user()
                ->newSubscription('default', $price_id)
                // ->trialDays(5)
                // ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('checkout-success'),
                    'cancel_url' => route('checkout-cancel'),
                ]);
        });
    });

    // Siparişler (giriş yapmış kullanıcılar için)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    // Only subscribed users can access here
    Route::middleware([Subscribed::class])->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        // billing portal
        Route::get('/billing-portal', function (Request $request) {
            return $request->user()->redirectToBillingPortal(route('profile.show'));
        })->name('billing-portal');

        // Hızlı Ödeme Yönetimi (Admin için)
        Route::prefix('admin/quick-payments')->group(function () {
            Route::get('/', [QuickPaymentController::class, 'index'])->name('admin.quick-payments.index');
            Route::post('/create', [QuickPaymentController::class, 'create'])->name('admin.quick-payments.create');
        });

        // ADD YOUR SUBSCRIBED ROUTES HERE

    });
});
