<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ServiceReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\WelcomePasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/services/residential', [ServiceController::class, 'residential'])->name('services.residential');
Route::get('/services/residential/{pest}', [ServiceController::class, 'pest'])->name('services.pest');
Route::get('/services/commercial', [ServiceController::class, 'commercial'])->name('services.commercial');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::get('/about', fn () => view('about'))->name('about');
Route::get('/privacy', fn () => view('privacy'))->name('privacy');
Route::get('/terms', fn () => view('terms'))->name('terms');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Service agreements: the customer's private link from their email (no login needed).
Route::prefix('agreement/{token}')->name('agreements.')->group(function () {
    Route::get('/', [AgreementController::class, 'show'])->name('show');
    Route::post('/accept', [AgreementController::class, 'accept'])->middleware('throttle:10,1')->name('accept');
    Route::get('/pay', [AgreementController::class, 'pay'])->name('pay');
    Route::post('/checkout', [AgreementController::class, 'checkout'])->middleware('throttle:10,1')->name('checkout');
    Route::get('/download', [AgreementController::class, 'download'])->name('download');
});

// New customers set their password from the welcome email (signed link, 7 days).
Route::get('/welcome/{user}/set-password', [WelcomePasswordController::class, 'show'])->name('welcome.password');
Route::post('/welcome/{user}/set-password', [WelcomePasswordController::class, 'store'])->middleware('throttle:10,1')->name('welcome.password.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // role:customer keeps marketing/sales reps (same login table, different
    // role) out of the customer portal — they're sent to their own dashboard
    // instead by the 'role' middleware. Ordinary customers are unaffected.
    Route::middleware('role:customer')->group(function () {
        Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');

        Route::get('/portal/service-visits/{serviceVisit}/report', [ServiceReportController::class, 'download'])->name('portal.service-visit.report');
        Route::get('/portal/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('portal.invoice.download');
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/marketing.php';
require __DIR__.'/payments.php';
