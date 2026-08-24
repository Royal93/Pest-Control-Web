<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        abort_unless($invoice->user_id === auth()->id(), 403);

        $pdf = Pdf::loadView('pdfs.invoice', ['invoice' => $invoice]);

        return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }
}
