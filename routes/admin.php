<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
| Required from routes/web.php.
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Public: login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Protected: everything else
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}', [PlanController::class, 'update'])->name('plans.update');

        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

        // The Sales Team: accounts admin creates (role=marketing on the same
        // users table customers use). Route names kept as admin.marketing.*.
        Route::get('/sales-team', [MarketingController::class, 'index'])->name('marketing.index');
        Route::get('/sales-team/create', [MarketingController::class, 'create'])->name('marketing.create');
        Route::post('/sales-team', [MarketingController::class, 'store'])->name('marketing.store');
        Route::get('/sales-team/{rep}', [MarketingController::class, 'show'])->name('marketing.show');
        Route::delete('/sales-team/{rep}', [MarketingController::class, 'destroy'])->name('marketing.destroy');
        Route::redirect('/marketing', '/admin/sales-team');

        // Leads: only admin captures leads, and assigns each one to a sales rep.
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::patch('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
        Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
        Route::get('/leads/{lead}/documents/{document}/download', [LeadController::class, 'downloadDocument'])->name('leads.documents.download');

        // Sales: Capture a Sale -> service agreement -> acceptance -> payment.
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
        Route::get('/sales/{sale}/agreement', [SaleController::class, 'download'])->name('sales.download');
        Route::post('/sales/{sale}/resend', [SaleController::class, 'resend'])->name('sales.resend');
        Route::post('/sales/{sale}/mark-paid', [SaleController::class, 'markPaid'])->name('sales.markPaid');
        Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');

        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::delete('/admins/{admin}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
    });
});
