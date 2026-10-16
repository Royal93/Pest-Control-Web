<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Pest;
use App\Models\Plan;
use App\Rules\SouthAfricanPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /** The leads this rep has captured. Reps only ever see their own. */
    public function index(Request $request)
    {
        $leads = Lead::where('captured_by', Auth::id())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('phone', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('marketing.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function create()
    {
        $pests = Pest::orderBy('name')->pluck('name');
        $plans = Plan::orderBy('name')->pluck('name');

        return view('marketing.leads.create', compact('pests', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', new SouthAfricanPhone],
            'email' => 'nullable|email:rfc|max:255',
            'property_type' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'suburb' => 'nullable|string|max:120',
            'province' => 'nullable|string|max:60',
            'pest_type' => 'nullable|string|max:120',
            'service_interest' => 'nullable|string|max:100',
            'referral_channel' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'next_follow_up_date' => 'nullable|date',
            'consent' => 'required|accepted',
        ], [
            'consent.required' => 'Please confirm the customer agreed to be contacted before saving this lead.',
            'consent.accepted' => 'Please confirm the customer agreed to be contacted before saving this lead.',
        ]);

        Lead::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'property_type' => $validated['property_type'] ?? null,
            'address' => $validated['address'] ?? null,
            'suburb' => $validated['suburb'] ?? null,
            'province' => $validated['province'] ?? null,
            'pest_type' => $validated['pest_type'] ?? null,
            'service_interest' => $validated['service_interest'] ?? null,
            'referral_channel' => $validated['referral_channel'] ?? null,
            'message' => $validated['message'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'next_follow_up_date' => $validated['next_follow_up_date'] ?? null,
            'consent' => true,
            'source' => 'field_rep',
            'status' => 'new',
            'captured_by' => Auth::id(),
        ]);

        return redirect()->route('marketing.leads.index')->with('status', 'Lead saved.');
    }

    public function edit(Lead $lead)
    {
        abort_unless((int) $lead->captured_by === (int) Auth::id(), 403);

        return view('marketing.leads.edit', [
            'lead' => $lead,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        abort_unless((int) $lead->captured_by === (int) Auth::id(), 403);

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', Lead::STATUSES),
            'notes' => 'nullable|string|max:2000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $lead->update($validated);

        return redirect()->route('marketing.leads.index')->with('status', 'Lead updated.');
    }
}
