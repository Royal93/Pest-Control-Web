<?php

use App\Http\Controllers\Marketing\DashboardController;
use App\Http\Controllers\Marketing\LeadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Marketing / field-sales routes
|--------------------------------------------------------------------------
| Same login as customers (the 'web' guard, the users table) — the 'role'
| middleware just sends anyone who isn't role=marketing back to their own
| dashboard instead. Required from routes/web.php.
*/

Route::prefix('marketing')->name('marketing.')->middleware(['auth', 'role:marketing'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
    Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
});
