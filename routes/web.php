<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PortalController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/services/residential', [ServiceController::class, 'residential'])->name('services.residential');
Route::get('/services/residential/{pest}', [ServiceController::class, 'pest'])->name('services.pest');
Route::get('/services/commercial', [ServiceController::class, 'commercial'])->name('services.commercial');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');

Route::get('/about', fn () => view('about'))->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
});

require __DIR__.'/auth.php';
