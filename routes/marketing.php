<?php

use App\Http\Controllers\Marketing\DashboardController;
use App\Http\Controllers\Marketing\LeadController;
use App\Http\Controllers\Marketing\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SP SALES portal (sales reps)
|--------------------------------------------------------------------------
| Same login as customers (the 'web' guard, the users table). The 'role'
| middleware sends anyone who isn't role=marketing back to their own
| dashboard. Lives at /sales; route names stay marketing.* so nothing else
| has to change. Reps no longer capture leads: admin captures and assigns
| them, and reps work them here and Capture a Sale. Required from web.php.
*/

Route::prefix('sales')->name('marketing.')->middleware(['auth', 'role:marketing'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
    Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');

    Route::post('/leads/{lead}/documents', [LeadController::class, 'storeDocument'])->name('leads.documents.store');
    Route::get('/leads/{lead}/documents/{document}/download', [LeadController::class, 'downloadDocument'])->name('leads.documents.download');
    Route::delete('/leads/{lead}/documents/{document}', [LeadController::class, 'destroyDocument'])->name('leads.documents.destroy');

    Route::get('/my-sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/my-sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/my-sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/my-sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/my-sales/{sale}/agreement', [SaleController::class, 'download'])->name('sales.download');
});

// Old SP MARKETING bookmarks keep working.
Route::redirect('/marketing', '/sales');
Route::get('/marketing/{any}', fn (string $any) => redirect('/sales/'.$any))->where('any', '.*');
