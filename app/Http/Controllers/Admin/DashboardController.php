<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\User;
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
            'customers_count' => Schema::hasTable('users') ? \DB::table('users')->where('role', 'customer')->count() : null,
            'payments_this_month' => Schema::hasTable('payments')
                ? Payment::where('status', 'accepted')->whereMonth('created_at', now()->month)->sum('amount')
                : null,
            'pending_payments' => Schema::hasTable('payments')
                ? Payment::where('status', 'pending')->count()
                : null,
            'marketing_reps_count' => Schema::hasTable('users') ? User::where('role', 'marketing')->count() : null,
            'leads_this_month' => Schema::hasTable('leads') ? Lead::whereMonth('created_at', now()->month)->count() : null,
            'leads_from_reps_this_month' => Schema::hasTable('leads')
                ? Lead::where('source', 'field_rep')->whereMonth('created_at', now()->month)->count()
                : null,
        ];

        $recentLeads = Schema::hasTable('leads')
            ? Lead::with('capturedBy')->latest()->limit(5)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'recentLeads'));
    }
}
