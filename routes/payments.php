<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Netcash Pay Now routes
|--------------------------------------------------------------------------
| Required from routes/web.php.
|
| The four "postback" routes below are the exact URLs to paste into the
| Netcash account under: Account Profile -> NetConnector -> Pay Now
|   Notify:   https://nomorepest.co.za/payments/notify
|   Accept:   https://nomorepest.co.za/payments/accept
|   Decline:  https://nomorepest.co.za/payments/decline
|   Redirect: https://nomorepest.co.za/payments/redirect
|
| They are posted to by Netcash (or the customer's browser leaving Netcash),
| with no CSRF token, so they are excluded from CSRF in bootstrap/app.php.
|
| Service agreement payments start from /agreement/{token}/checkout (see
| routes/web.php). The two generic checkout routes need a login.
*/

Route::middleware('auth')->group(function () {
    Route::post('/payments/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::post('/payments/checkout-subscription', [PaymentController::class, 'checkoutSubscription'])->name('payments.checkout.subscription');
});

Route::post('/payments/notify', [PaymentController::class, 'notify'])->name('payments.notify');
Route::post('/payments/accept', [PaymentController::class, 'accept'])->name('payments.accept');
Route::post('/payments/decline', [PaymentController::class, 'decline'])->name('payments.decline');
Route::post('/payments/redirect', [PaymentController::class, 'redirect'])->name('payments.redirect');
