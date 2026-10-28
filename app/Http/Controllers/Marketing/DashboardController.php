<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $repId = Auth::id();

        // Admin captures and assigns leads; a rep's dashboard is the leads assigned to them.
        $base = Lead::where('assigned_to', $repId);

        $stats = [
            'total' => (clone $base)->count(),
            'this_week' => (clone $base)->where('assigned_at', '>=', now()->startOfWeek())->count(),
            'converted' => (clone $base)->where('status', 'converted')->count(),
            'follow_ups_due' => (clone $base)->whereNotNull('next_follow_up_date')
                ->whereDate('next_follow_up_date', '<=', now()->toDateString())
                ->whereNotIn('status', ['converted', 'not_interested'])
                ->count(),
        ];

        $recentLeads = (clone $base)->latest('assigned_at')->latest()->limit(8)->get();

        return view('marketing.dashboard', compact('stats', 'recentLeads'));
    }
}
