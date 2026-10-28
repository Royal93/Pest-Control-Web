<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\User;
use App\Services\SaleAgreementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    public function __construct(private SaleAgreementService $agreements)
    {
    }

    public function index(Request $request)
    {
        $status = $request->input('status');

        $counts = Sale::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $sales = Sale::with('salesRep')
            ->when(array_key_exists((string) $status, Sale::STATUSES), fn ($q) => $q->where('status', $status))
            ->when(ctype_digit((string) $request->input('rep')), fn ($q) => $q->where('sales_rep_id', (int) $request->input('rep')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->input('search').'%';
                $q->where(fn ($w) => $w->where('customer_name', 'like', $term)
                    ->orWhere('contact_person', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term));
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.sales.index', [
            'sales' => $sales,
            'counts' => $counts,
            'reps' => User::salesReps()->orderBy('name')->get(),
        ]);
    }

    /** Capture a Sale from the admin portal: from a lead (?lead=ID) or as a brand-new sale. */
    public function create(Request $request)
    {
        $lead = $request->filled('lead') ? Lead::with('assignedTo')->find($request->input('lead')) : null;

        return view('admin.sales.create', [
            'lead' => $lead,
            'prefill' => $this->agreements->prefillFromLead($lead),
            'pricing' => $this->agreements->pricingDefaults(),
            'reps' => User::salesReps()->orderBy('name')->get(),
            'selectedRepId' => $lead?->assigned_to,
        ]);
    }

    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();
        $lead = ! empty($data['lead_id']) ? Lead::find($data['lead_id']) : null;
        $rep = ! empty($data['sales_rep_id']) ? User::find($data['sales_rep_id']) : null;

        $sale = $this->agreements->create($data, $rep, Auth::guard('admin')->user(), $lead);

        return redirect()->route('admin.sales.show', $sale)
            ->with('status', $sale->sent_at
                ? 'Agreement '.$sale->reference().' emailed to '.$sale->email.'.'
                : 'Sale saved, but the email could not be sent. Check the mail settings, then use "Resend Agreement".');
    }

    public function show(Sale $sale)
    {
        $sale->load(['lead', 'salesRep', 'capturedByAdmin', 'customer', 'payments' => fn ($q) => $q->latest()]);

        return view('admin.sales.show', compact('sale'));
    }

    public function download(Sale $sale)
    {
        if (! $sale->agreement_path || ! Storage::disk('local')->exists($sale->agreement_path)) {
            $this->agreements->generatePdf($sale);
        }

        return Storage::disk('local')->download($sale->agreement_path, $sale->pdfFileName());
    }

    /** Resend the agreement (or, once accepted, the payment link) to the customer. */
    public function resend(Sale $sale)
    {
        abort_if($sale->isPaid() || $sale->isCancelled(), 422, 'This agreement can no longer be resent.');

        $sent = $this->agreements->sendAgreement($sale);

        return back()->with($sent ? 'status' : 'error', $sent
            ? ($sale->isAwaitingPayment() ? 'Payment link resent to ' : 'Agreement resent to ').$sale->email.'.'
            : 'The email could not be sent. Please check the mail settings and try again.');
    }

    /**
     * For a first payment made outside the payment link (EFT, cash at the office),
     * or if the Netcash notification never arrived: activates exactly as Netcash would.
     */
    public function markPaid(Sale $sale)
    {
        abort_unless($sale->isAwaitingPayment() || $sale->isAwaitingAcceptance(), 422, 'Only an open agreement can be marked as paid.');

        $this->agreements->markPaid($sale);

        return back()->with('status', 'First payment recorded. '.$sale->contact_person.' is now an active customer and has been sent their portal login.');
    }

    public function cancel(Sale $sale)
    {
        abort_if($sale->isPaid(), 422, 'A paid agreement cannot be cancelled here.');

        $sale->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        // Put the lead back into the pipeline if this was its only open agreement.
        if ($sale->lead && $sale->lead->status === 'quoted'
            && ! $sale->lead->sales()->whereIn('status', ['sent', 'accepted'])->exists()) {
            $sale->lead->update(['status' => 'contacted']);
        }

        return back()->with('status', 'Agreement '.$sale->reference().' cancelled. The customer link no longer works.');
    }
}
