<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [App\Http\Controllers\Pages\PageController::class, 'login'])->name('login');
    Route::get('/register', [App\Http\Controllers\Pages\PageController::class, 'register'])->name('register');
    Route::post('/user-registration', [App\Http\Controllers\Users\UserController::class, 'storeUser'])->name('user.register');
    Route::get('/verify-email', [App\Http\Controllers\Users\UserController::class, 'emailVerify'])->name('email.verify');
    Route::post('/verify-otp', [App\Http\Controllers\Users\UserController::class, 'otpVerify'])->name('verify.myEmail');
    Route::post('/authenticate', [App\Http\Controllers\Auth\AuthenticateController::class, 'auth'])->name('authenticate.user');
    Route::get('/forgot-password', [App\Http\Controllers\Users\UserController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/send-email', [App\Http\Controllers\Users\UserController::class, 'sendEmail'])->name('send.email');
    Route::get('/change-password', [App\Http\Controllers\Users\UserController::class, 'changePassword'])->name('change.password');
    Route::post('/password-reset', [App\Http\Controllers\Users\UserController::class, 'passwordResset'])->name('reset.password');
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
    Route::get('/transactions', [App\Http\Controllers\Pages\PageController::class, 'transactions'])->name('transactions.view');
    Route::get('/pick-up-schedule', [App\Http\Controllers\Pages\PageController::class, 'schedulePickUpDay'])->name('schedule.pickups.day');
    Route::post('/store-pick-ups', [App\Http\Controllers\Pages\PageController::class, 'storePickUpsData'])->name('store.pickups');
    Route::post('/store-schedules', [App\Http\Controllers\Pages\PageController::class, 'storeSchedulesPickUp'])->name('store.pickup.schedules');
    Route::get('/contracts/{encryptedId}', [App\Http\Controllers\Pages\PageController::class, 'contracts'])->name('view.contracts');
    Route::post('/contract-create', [App\Http\Controllers\Pages\PageController::class, 'createContract'])->name('contract.approve');
    Route::get('/pickup-locations', [App\Http\Controllers\Pages\PageController::class, 'pickUpLocations'])->name('pickup.locations');
    Route::get('/push-notifications', [App\Http\Controllers\Pages\PageController::class, 'pushNotifications'])->name('push.notifications');
    Route::get('/prediction-reports', [App\Http\Controllers\Pages\PageController::class, 'runPrediction'])->name('prediction.reports');
    Route::get('/notification-center', [App\Http\Controllers\Pages\PageController::class, 'notificationCenter'])->name('push.notifications');
    Route::post('/notifications', [App\Http\Controllers\Pages\PageController::class, 'setNotifications'])->name('set.reminders');
    Route::get('/transaction-download', [App\Http\Controllers\Pages\PageController::class, 'downloadPDF'])->name('transactions.pdf');
    Route::get('/complaints', [App\Http\Controllers\Pages\PageController::class, 'complaints'])->name('complaints');
    Route::post('/send-complaints', [App\Http\Controllers\Pages\PageController::class, 'saveComplainnts'])->name('send.complaints');
    Route::get('/manage-complaints', [App\Http\Controllers\Pages\PageController::class, 'manageComplaints'])->name('manage.complaints');
    Route::put('/send-feedbacks', [App\Http\Controllers\Pages\PageController::class, 'sendFeedbacks'])->name('send.feedbacks');
    Route::get('/download-report', [App\Http\Controllers\Pages\PageController::class, 'downloadReport'])->name('report.pdf');



    // USER MGT
    Route::get('/user-management', [App\Http\Controllers\Users\UserController::class, 'userManagement'])->name('user.management');
    Route::post('/store.staff', [App\Http\Controllers\Users\UserController::class, 'storeStaff'])->name('store.staff');
    Route::get('/residents-view', [App\Http\Controllers\Users\UserController::class, 'viewResidents'])->name('residents.view');
    Route::get('/profile', [App\Http\Controllers\Users\UserController::class, 'profile'])->name('profile');
    Route::put('/update-profile', [App\Http\Controllers\Users\UserController::class, 'updateProfile'])->name('update.profile');
    Route::get('/admin-profile', [App\Http\Controllers\Users\UserController::class, 'adminProfile'])->name('admin.profile');
    Route::put('/update-admin-profile', [App\Http\Controllers\Users\UserController::class, 'updateAdminProfile'])->name('update.admin.profile');
    Route::put('/delete-user', [App\Http\Controllers\Users\UserController::class, 'deleteUser'])->name('delete.user');
    Route::put('/block-user', [App\Http\Controllers\Users\UserController::class, 'blockUser'])->name('block.user');


    Route::post('/update-location', [App\Http\Controllers\Pages\PageController::class, 'updateLocation'])->name('update.location');

    Route::get('/track-truck', [App\Http\Controllers\Pages\PageController::class, 'trackTruck'])->name('track.trucks');
    // PAYMENTS
    Route::post('/checkout', [App\Http\Controllers\Payment\PaymentController::class, 'checkoutPay'])->name('pay.ckechout');
    Route::get('/success', [App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\Payment\PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/wallet-pay', [App\Http\Controllers\Payment\PaymentController::class, 'walletPay'])->name('wallet.pay');
    Route::post('/recharge-wallet', [App\Http\Controllers\Payment\PaymentController::class, 'rechargeWallet'])->name('recharge.wallet');
    Route::get('/success_url', [App\Http\Controllers\Payment\PaymentController::class, 'successUrl'])->name('success_url');
    Route::get('/cancel_url', [App\Http\Controllers\Payment\PaymentController::class, ' '])->name('cancel_url');
});