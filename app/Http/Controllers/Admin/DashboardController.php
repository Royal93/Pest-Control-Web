<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Guarded with Schema::hasTable checks so the dashboard never errors out
        // if a table hasn't been migrated yet on a given environment.
        $stats = [
            'plans_count' => Plan::count(),
            'customers_count' => Schema::hasTable('users') ? User::customers()->count() : null,
            'payments_this_month' => Schema::hasTable('payments')
                ? Payment::where('status', 'accepted')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount')
                : null,
            'pending_payments' => Schema::hasTable('payments')
                ? Payment::where('status', 'pending')->count()
                : null,
            'sales_team_count' => Schema::hasTable('users') ? User::salesReps()->count() : null,
            'leads_this_month' => Schema::hasTable('leads')
                ? Lead::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count()
                : null,
            'assigned_leads' => Schema::hasColumn('leads', 'assigned_to')
                ? Lead::whereNotNull('assigned_to')->whereIn('status', Lead::IN_PROGRESS_STATUSES)->count()
                : null,
            'unassigned_leads' => Schema::hasColumn('leads', 'assigned_to')
                ? Lead::whereNull('assigned_to')->whereIn('status', Lead::IN_PROGRESS_STATUSES)->count()
                : null,
            'sales_awaiting' => Schema::hasTable('sales')
                ? Sale::whereIn('status', ['sent', 'accepted'])->count()
                : null,
        ];

        $recentLeads = Schema::hasTable('leads')
            ? Lead::with('assignedTo')->latest()->limit(5)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'recentLeads'));
    }
}
