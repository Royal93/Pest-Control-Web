<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDocument;
use App\Models\Pest;
use App\Models\Plan;
use App\Models\User;
use App\Rules\SouthAfricanPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    /**
     * Every lead in the system, split into three tabs so what still needs work
     * never gets lost among the ones that are already resolved:
     *   - in_progress: new, contacted, quoted
     *   - converted:   became a customer (also flags whether they've registered a login yet)
     *   - not_interested
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'in_progress');
        if (! in_array($tab, ['in_progress', 'converted', 'not_interested'], true)) {
            $tab = 'in_progress';
        }

        $base = Lead::with(['assignedTo', 'capturedBy']);

        $counts = [
            'in_progress' => (clone $base)->whereIn('status', Lead::IN_PROGRESS_STATUSES)->count(),
            'converted' => (clone $base)->where('status', 'converted')->count(),
            'not_interested' => (clone $base)->where('status', 'not_interested')->count(),
        ];

        $unassignedCount = Lead::whereNull('assigned_to')->whereIn('status', Lead::IN_PROGRESS_STATUSES)->count();

        $query = match ($tab) {
            'converted' => (clone $base)->where('status', 'converted'),
            'not_interested' => (clone $base)->where('status', 'not_interested'),
            default => (clone $base)->whereIn('status', Lead::IN_PROGRESS_STATUSES),
        };

        $query
            ->when($request->filled('source'), fn ($q) => $q->where('source', $request->input('source')))
            ->when($request->input('rep') === 'unassigned', fn ($q) => $q->whereNull('assigned_to'))
            ->when(ctype_digit((string) $request->input('rep')), fn ($q) => $q->where('assigned_to', (int) $request->input('rep')))
            ->when($tab === 'in_progress' && $request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest();

        $leads = $query->paginate(25)->withQueryString();

        // One query for every customer email, so each row's "has an account?"
        // check is an in-memory lookup rather than a query per lead.
        $customerEmailsLower = User::customers()->pluck('email')
            ->map(fn ($e) => strtolower($e))->all();

        return view('admin.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::STATUSES,
            'tab' => $tab,
            'counts' => $counts,
            'unassignedCount' => $unassignedCount,
            'customerEmailsLower' => $customerEmailsLower,
            'reps' => User::salesReps()->orderBy('name')->get(),
        ]);
    }

    /** Admin captures a lead and assigns it to a sales rep. Only admin can capture leads. */
    public function create(Request $request)
    {
        return view('admin.leads.create', [
            'reps' => User::salesReps()->orderBy('name')->get(),
            'pests' => Pest::orderBy('name')->pluck('name'),
            'plans' => Plan::orderBy('name')->pluck('name'),
            'selectedRep' => $request->input('rep'),
        ]);
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
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'marketing')],
            'consent' => 'required|accepted',
        ], [
            'consent.required' => 'Please confirm the customer agreed to be contacted before saving this lead.',
            'consent.accepted' => 'Please confirm the customer agreed to be contacted before saving this lead.',
            'assigned_to.exists' => 'Please choose a sales representative from the list.',
        ]);

        $lead = Lead::create([
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
            'source' => 'admin',
            'status' => 'new',
            'assigned_to' => $validated['assigned_to'] ?? null,
            'assigned_at' => ! empty($validated['assigned_to']) ? now() : null,
            'captured_by_admin' => Auth::guard('admin')->id(),
        ]);

        $lead->load('assignedTo');

        $message = $lead->assignedTo
            ? 'Lead saved and assigned to '.$lead->assignedToName().'. It now shows on their SP SALES dashboard.'
            : 'Lead saved. It is not assigned to a sales rep yet.';

        return $request->boolean('add_another')
            ? redirect()->route('admin.leads.create', ['rep' => $lead->assigned_to])->with('status', $message)
            : redirect()->route('admin.leads.show', $lead)->with('status', $message);
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedTo', 'capturedBy', 'capturedByAdmin', 'documents.uploadedBy', 'sales.salesRep']);

        $hasAccount = $lead->email
            ? User::customers()->whereRaw('LOWER(email) = ?', [strtolower($lead->email)])->exists()
            : false;

        return view('admin.leads.show', [
            'lead' => $lead,
            'statuses' => Lead::STATUSES,
            'hasAccount' => $hasAccount,
            'reps' => User::salesReps()->orderBy('name')->get(),
        ]);
    }

    /** Assign, reassign or unassign a lead. The rep sees it on SP SALES immediately. */
    public function assign(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'marketing')],
        ]);

        $repId = $validated['assigned_to'] ?? null;

        if ((int) $lead->assigned_to !== (int) $repId) {
            $lead->update([
                'assigned_to' => $repId,
                'assigned_at' => $repId ? now() : null,
            ]);
        }

        $lead->load('assignedTo');

        return back()->with('status', $lead->assignedTo
            ? $lead->name.' is assigned to '.$lead->assignedToName().'.'
            : $lead->name.' is no longer assigned to a sales rep.');
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', Lead::STATUSES),
        ]);

        $lead->update($validated);

        return back()->with('status', $lead->name.' marked as '.$lead->statusLabel().'.');
    }

    public function downloadDocument(Lead $lead, LeadDocument $document)
    {
        abort_unless((int) $document->lead_id === (int) $lead->id, 404);

        $disk = Storage::disk('local');

        abort_unless($disk->exists($document->file_path), 404);

        return $disk->download($document->file_path, $document->downloadName());
    }
}
