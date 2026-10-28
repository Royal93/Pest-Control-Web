<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Lead;
use App\Models\Sale;
use App\Services\SaleAgreementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/** Capture a Sale on SP SALES. A rep sells to leads admin has assigned to them. */
class SaleController extends Controller
{
    public function __construct(private SaleAgreementService $agreements)
    {
    }

    public function index(Request $request)
    {
        $sales = Sale::where('sales_rep_id', Auth::id())
            ->when(array_key_exists((string) $request->input('status'), Sale::STATUSES), fn ($q) => $q->where('status', $request->input('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('marketing.sales.index', compact('sales'));
    }

    public function create(Request $request)
    {
        $lead = Lead::find($request->input('lead'));

        if (! $lead) {
            return redirect()->route('marketing.leads.index')
                ->with('status', 'Open one of your leads and choose "Capture a Sale" from there.');
        }

        $this->authorizeOwnLead($lead);

        return view('marketing.sales.create', [
            'lead' => $lead,
            'prefill' => $this->agreements->prefillFromLead($lead),
            'pricing' => $this->agreements->pricingDefaults(),
        ]);
    }

    public function store(StoreSaleRequest $request)
    {
        $data = $request->validated();
        $lead = Lead::findOrFail($data['lead_id']);
        $this->authorizeOwnLead($lead);

        $sale = $this->agreements->create($data, $request->user(), null, $lead);

        return redirect()->route('marketing.sales.show', $sale)
            ->with('status', $sale->sent_at
                ? 'Agreement '.$sale->reference().' emailed to '.$sale->email.'. You will get a copy when they accept and pay.'
                : 'Sale saved, but the email could not be sent. Please let the office know.');
    }

    public function show(Sale $sale)
    {
        $this->authorizeOwnSale($sale);
        $sale->load('lead');

        return view('marketing.sales.show', compact('sale'));
    }

    public function download(Sale $sale)
    {
        $this->authorizeOwnSale($sale);

        if (! $sale->agreement_path || ! Storage::disk('local')->exists($sale->agreement_path)) {
            $this->agreements->generatePdf($sale);
        }

        return Storage::disk('local')->download($sale->agreement_path, $sale->pdfFileName());
    }

    private function authorizeOwnLead(Lead $lead): void
    {
        abort_unless((int) $lead->assigned_to === (int) Auth::id(), 403);
    }

    private function authorizeOwnSale(Sale $sale): void
    {
        abort_unless((int) $sale->sales_rep_id === (int) Auth::id(), 403);
    }
}
