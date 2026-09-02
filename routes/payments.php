<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Netcash Pay Now routes
|--------------------------------------------------------------------------
| Add a require line for this file in routes/web.php, e.g.:
|   require __DIR__.'/payments.php';
|
| The four "postback" routes below are the exact URLs you give the client
| to paste into their Netcash account under:
|   Account Profile -> NetConnector -> Pay Now
|
| Use your real domain, e.g.:
|   Notify:   https://sppestcontrol.co.za/payments/notify
|   Accept:   https://sppestcontrol.co.za/payments/accept
|   Decline:  https://sppestcontrol.co.za/payments/decline
|   Redirect: https://sppestcontrol.co.za/payments/redirect
|
| notify/accept/decline/redirect are posted to directly by Netcash's own
| servers (or the customer's browser leaving Netcash's site) — there's no
| CSRF token in that request, so these four paths MUST be added to the
| CSRF exception list. See "CSRF exception" note in the README.
*/

Route::post('/payments/checkout', [PaymentController::class, 'checkout'])->name('payments.checkout');
Route::post('/payments/checkout-subscription', [PaymentController::class, 'checkoutSubscription'])->name('payments.checkout.subscription');

Route::post('/payments/notify', [PaymentController::class, 'notify'])->name('payments.notify');
Route::post('/payments/accept', [PaymentController::class, 'accept'])->name('payments.accept');
Route::post('/payments/decline', [PaymentController::class, 'decline'])->name('payments.decline');
Route::post('/payments/redirect', [PaymentController::class, 'redirect'])->name('payments.redirect');
