<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadClaim;
use App\Services\LeadClaimService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Admin decides which sales rep gets an unassigned lead that reps have claimed. */
class LeadClaimController extends Controller
{
    public function __construct(private LeadClaimService $claims)
    {
    }

    /** Every lead with claims waiting, oldest claim first, grouped by lead. */
    public function index(Request $request)
    {
        $pending = LeadClaim::pending()
            ->with(['lead', 'rep'])
            ->oldest()
            ->get()
            ->groupBy('lead_id');

        $recent = LeadClaim::whereIn('status', ['approved', 'declined'])
            ->with(['lead', 'rep', 'decidedBy'])
            ->latest('decided_at')
            ->limit(15)
            ->get();

        return view('admin.claims.index', compact('pending', 'recent'));
    }

    public function approve(LeadClaim $claim)
    {
        $claim->load(['lead', 'rep']);

        if (! $claim->isPending()) {
            return back()->with('error', 'That claim has already been dealt with.');
        }

        if ($claim->lead->assigned_to !== null) {
            return back()->with('error', $claim->lead->name.' is already assigned. Unassign it first if you want to give it to '.$claim->rep->fullName().'.');
        }

        $this->claims->approve($claim, Auth::guard('admin')->user());

        return back()->with('status', $claim->lead->name.' is now assigned to '.$claim->rep->fullName().'. Any other claims on it were declined, and the reps have been notified.');
    }

    public function decline(LeadClaim $claim)
    {
        $claim->load(['lead', 'rep']);

        if (! $claim->isPending()) {
            return back()->with('error', 'That claim has already been dealt with.');
        }

        $this->claims->decline($claim, Auth::guard('admin')->user());

        return back()->with('status', $claim->rep->fullName().'\'s claim on '.$claim->lead->name.' was declined. The lead stays open to claim.');
    }
}
