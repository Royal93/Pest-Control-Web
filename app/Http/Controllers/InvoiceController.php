<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download(Invoice $invoice)
    {
        // A customer can only ever download their own invoices.
        abort_unless((int) $invoice->user_id === (int) auth()->id(), 403);

        // An invoice file uploaded by an admin is sent as it is.
        if ($invoice->hasFile()) {
            $disk = Storage::disk('local');

            abort_unless($disk->exists($invoice->file_path), 404);

            return $disk->download($invoice->file_path, $invoice->downloadName());
        }

        // Otherwise generate the PDF from the invoice record, as before.
        $pdf = Pdf::loadView('pdfs.invoice', ['invoice' => $invoice]);

        return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }
}
