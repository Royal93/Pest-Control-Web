<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Services\SaleAgreementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * The customer's side of a sale, reached through the private link in their
 * email (no login needed): review, accept electronically, then pay.
 */
class AgreementController extends Controller
{
    public function __construct(private SaleAgreementService $agreements)
    {
    }

    public function show(string $token)
    {
        $sale = $this->findOrFail($token);

        return view('agreements.show', compact('sale'));
    }

    public function accept(Request $request, string $token)
    {
        $sale = $this->findOrFail($token);

        if (! $sale->isAwaitingAcceptance()) {
            return redirect()->route('agreements.show', $token);
        }

        $validated = $request->validate([
            'accepted_name' => 'required|string|min:3|max:255',
            'confirm' => 'accepted',
        ], [
            'accepted_name.required' => 'Please type your full name to accept the agreement.',
            'confirm.accepted' => 'Please tick the box to confirm you accept the agreement.',
        ]);

        $this->agreements->accept($sale, trim($validated['accepted_name']), $request->ip(), $request->userAgent());

        return redirect()->route('agreements.pay', $token)
            ->with('status', 'Thank you. Your agreement is accepted and a copy has been emailed to you.');
    }

    /** Payment summary with the button that goes to Netcash. */
    public function pay(string $token)
    {
        $sale = $this->findOrFail($token);

        if ($sale->isAwaitingAcceptance() || $sale->isPaid()) {
            return redirect()->route('agreements.show', $token);
        }

        return view('agreements.pay', compact('sale'));
    }

    public function checkout(string $token)
    {
        $sale = $this->findOrFail($token);

        if (! $sale->isAwaitingPayment()) {
            return redirect()->route('agreements.show', $token);
        }

        return view('payments.redirect', ['netcash' => $this->agreements->startPayment($sale)]);
    }

    public function download(string $token)
    {
        $sale = $this->findOrFail($token);

        if (! $sale->agreement_path || ! Storage::disk('local')->exists($sale->agreement_path)) {
            $this->agreements->generatePdf($sale);
        }

        return Storage::disk('local')->download($sale->agreement_path, $sale->pdfFileName());
    }

    private function findOrFail(string $token): Sale
    {
        abort_unless(strlen($token) === 48, 404);

        return Sale::where('token', $token)->firstOrFail();
    }
}
