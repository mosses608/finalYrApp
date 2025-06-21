<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [App\Http\Controllers\Pages\PageController::class, 'login'])->name('login');
    Route::get('/register', [App\Http\Controllers\Pages\PageController::class, 'register'])->name('register');
    Route::post('/user-registration', [App\Http\Controllers\Users\UserController::class, 'storeUser'])->name('user.register');
    Route::get('/verify-email', [App\Http\Controllers\Users\UserController::class, 'emailVerify'])->name('email.verify');
    Route::post('/verify-otp', [App\Http\Controllers\Users\UserController::class, 'otpVerify'])->name('verify.myEmail');
    Route::post('/authenticate', [App\Http\Controllers\Auth\AuthenticateController::class, 'auth'])->name('authenticate.user');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Pages\PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/logout', [App\Http\Controllers\Auth\AuthenticateController::class, 'logout'])->name('logout.invalidate');
    Route::get('/schedule-pickupt', [App\Http\Controllers\Pages\PageController::class, 'schedulePickUp'])->name('schedule.pickup');
    Route::post('/create-schedules', [App\Http\Controllers\Pages\PageController::class, 'storeSchedules'])->name('store.schedules');
    Route::get('/pickup-requests', [App\Http\Controllers\Pages\PageController::class, 'pickUpRequests'])->name('pickup.requests');
    Route::get('/view-request/{encryptedId}', [App\Http\Controllers\Pages\PageController::class, 'viewRequest'])->name('view.request');
    Route::put('/accept-request', [App\Http\Controllers\Pages\PageController::class, 'acceptRequest'])->name('accept.request');
    Route::get('/request-details/{encryptedId}', [App\Http\Controllers\Pages\PageController::class, 'requestDetails'])->name('my.request.details');
    Route::get('/my-wallet', [App\Http\Controllers\Pages\PageController::class, 'myWallet'])->name('my.wallet');
    Route::get('/recycle-exchange', [App\Http\Controllers\Pages\PageController::class, 'recycleExchange'])->name('recycle.exchange');
    Route::post('/recyclables', [App\Http\Controllers\Pages\PageController::class, 'recyclablePost'])->name('recyclable.post');

    // PAYMENTS
    Route::post('/checkout', [App\Http\Controllers\Payment\PaymentController::class, 'checkoutPay'])->name('pay.ckechout');
    Route::get('/success', [App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\Payment\PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/wallet-pay', [App\Http\Controllers\Payment\PaymentController::class, 'walletPay'])->name('wallet.pay');
    Route::post('/recharge-wallet', [App\Http\Controllers\Payment\PaymentController::class, 'rechargeWallet'])->name('recharge.wallet');
    Route::get('/success_url', [App\Http\Controllers\Payment\PaymentController::class, 'successUrl'])->name('success_url');
    Route::get('/cancel_url', [App\Http\Controllers\Payment\PaymentController::class, ' '])->name('cancel_url');
});
