<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadClaim;
use App\Services\LeadClaimService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Open Leads on SP SALES: leads admin has captured but not assigned yet.
 * Every rep sees them and can claim them; admin approves one claim, and only
 * then does that rep get the lead (and its contact details) in My Leads.
 * Phone, email and street address are never sent to this page.
 */
class OpenLeadController extends Controller
{
    public function __construct(private LeadClaimService $claims)
    {
    }

    public function index(Request $request)
    {
        $tab = $request->input('tab') === 'claims' ? 'claims' : 'open';
        $repId = Auth::id();

        // Only the columns that are safe to show before approval.
        $openLeads = Lead::openForClaims()
            ->select(['id', 'name', 'suburb', 'province', 'property_type', 'pest_type', 'service_interest', 'status', 'created_at'])
            ->with(['claims' => fn ($q) => $q->where('user_id', $repId)])
            ->withCount(['claims as pending_claims_count' => fn ($q) => $q->where('status', 'pending')])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)
                    ->orWhere('suburb', 'like', $term)
                    ->orWhere('pest_type', 'like', $term));
            })
            ->latest()
            ->paginate(20, ['*'], 'page')
            ->withQueryString();

        $myClaims = LeadClaim::where('user_id', $repId)
            ->with(['lead:id,name,suburb,pest_type,assigned_to'])
            ->latest('updated_at')
            ->paginate(20, ['*'], 'claims_page')
            ->withQueryString();

        return view('marketing.open-leads.index', [
            'tab' => $tab,
            'openLeads' => $openLeads,
            'myClaims' => $myClaims,
            'pendingCount' => LeadClaim::pending()->where('user_id', $repId)->count(),
        ]);
    }

    public function claim(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        if (! $lead->isOpenForClaims()) {
            return back()->with('status', $lead->name.' has already been given to a sales rep, so it can no longer be claimed.');
        }

        if (LeadClaim::pending()->where('lead_id', $lead->id)->where('user_id', Auth::id())->exists()) {
            return back()->with('status', 'You have already claimed '.$lead->name.'. Admin will let you know.');
        }

        $this->claims->claim($lead, $request->user(), $validated['note'] ?? null);

        return back()->with('status', 'Claim sent for '.$lead->name.'. You will get a notification once admin decides.');
    }

    public function withdraw(Lead $lead)
    {
        $claim = LeadClaim::pending()->where('lead_id', $lead->id)->where('user_id', Auth::id())->first();

        if (! $claim) {
            return back()->with('status', 'There is no waiting claim on '.$lead->name.' to withdraw.');
        }

        $this->claims->withdraw($claim);

        return back()->with('status', 'Your claim on '.$lead->name.' was withdrawn.');
    }
}
