<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Guarded with Schema::hasTable checks since some of these (payments,
        // the customer users table) may not exist yet depending on what's
        // been built so far — this keeps the dashboard from erroring out
        // rather than assuming everything is already wired up.
        $stats = [
            'plans_count' => Plan::count(),
            'customers_count' => Schema::hasTable('users') ? \DB::table('users')->count() : null,
            'payments_this_month' => Schema::hasTable('payments')
                ? Payment::where('status', 'accepted')->whereMonth('created_at', now()->month)->sum('amount')
                : null,
            'pending_payments' => Schema::hasTable('payments')
                ? Payment::where('status', 'pending')->count()
                : null,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
