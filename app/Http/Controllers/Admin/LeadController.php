<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $base = Lead::with('capturedBy');

        $counts = [
            'in_progress' => (clone $base)->whereIn('status', Lead::IN_PROGRESS_STATUSES)->count(),
            'converted' => (clone $base)->where('status', 'converted')->count(),
            'not_interested' => (clone $base)->where('status', 'not_interested')->count(),
        ];

        $query = match ($tab) {
            'converted' => (clone $base)->where('status', 'converted'),
            'not_interested' => (clone $base)->where('status', 'not_interested'),
            default => (clone $base)->whereIn('status', Lead::IN_PROGRESS_STATUSES),
        };

        $query
            ->when($request->filled('source'), fn ($q) => $q->where('source', $request->input('source')))
            ->when($tab === 'in_progress' && $request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest();

        $leads = $query->paginate(25)->withQueryString();

        // One query for every customer email, so each row's "has an account?"
        // check is an in-memory lookup rather than a query per lead.
        $customerEmailsLower = User::where('role', 'customer')->pluck('email')
            ->map(fn ($e) => strtolower($e))->all();

        return view('admin.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::STATUSES,
            'tab' => $tab,
            'counts' => $counts,
            'customerEmailsLower' => $customerEmailsLower,
        ]);
    }

    public function show(Lead $lead)
    {
        $lead->load(['capturedBy', 'documents.uploadedBy']);

        $hasAccount = $lead->email
            ? User::where('role', 'customer')->whereRaw('LOWER(email) = ?', [strtolower($lead->email)])->exists()
            : false;

        return view('admin.leads.show', [
            'lead' => $lead,
            'statuses' => Lead::STATUSES,
            'hasAccount' => $hasAccount,
        ]);
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
