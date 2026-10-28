<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * SP SALES: a rep works the leads admin has assigned to them. Reps no longer
 * capture leads themselves; only admin does, on the admin portal.
 */
class LeadController extends Controller
{
    /** The leads assigned to this rep. Reps only ever see their own. */
    public function index(Request $request)
    {
        $leads = Lead::where('assigned_to', Auth::id())
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('phone', 'like', $term));
            })
            ->latest('assigned_at')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('marketing.leads.index', [
            'leads' => $leads,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function edit(Lead $lead)
    {
        $this->authorizeOwn($lead);

        $lead->load(['documents', 'sales' => fn ($q) => $q->latest()]);

        return view('marketing.leads.edit', [
            'lead' => $lead,
            'statuses' => Lead::STATUSES,
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorizeOwn($lead);

        $validated = $request->validate([
            'status' => 'required|in:'.implode(',', Lead::STATUSES),
            'notes' => 'nullable|string|max:2000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $lead->update($validated);

        return redirect()->route('marketing.leads.edit', $lead)->with('status', 'Lead updated.');
    }

    /** Upload a supporting document (signed quote, ID copy, site photo, etc) for a lead. */
    public function storeDocument(Request $request, Lead $lead)
    {
        $this->authorizeOwn($lead);

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'label.required' => 'Please say what this document is, e.g. "Signed quote" or "ID copy".',
            'file.mimes' => 'The document must be a PDF, JPG or PNG file.',
            'file.max' => 'The file must not be larger than 10 MB.',
        ]);

        $path = $request->file('file')->store('lead-documents/'.$lead->id, 'local');

        if (! $path) {
            return back()->withInput()->withErrors(['file' => 'The file could not be saved. Please try again.']);
        }

        LeadDocument::create([
            'lead_id' => $lead->id,
            'uploaded_by' => Auth::id(),
            'label' => $validated['label'],
            'original_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return redirect()->route('marketing.leads.edit', $lead)->with('status', 'Document uploaded.');
    }

    public function downloadDocument(Lead $lead, LeadDocument $document)
    {
        $this->authorizeOwn($lead);
        abort_unless((int) $document->lead_id === (int) $lead->id, 404);

        $disk = Storage::disk('local');

        abort_unless($disk->exists($document->file_path), 404);

        return $disk->download($document->file_path, $document->downloadName());
    }

    public function destroyDocument(Lead $lead, LeadDocument $document)
    {
        $this->authorizeOwn($lead);
        abort_unless((int) $document->lead_id === (int) $lead->id, 404);

        Storage::disk('local')->delete($document->file_path);
        $document->delete();

        return redirect()->route('marketing.leads.edit', $lead)->with('status', 'Document removed.');
    }

    /** A rep may only act on leads currently assigned to them. */
    private function authorizeOwn(Lead $lead): void
    {
        abort_unless((int) $lead->assigned_to === (int) Auth::id(), 403);
    }
}
