<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /** All invoices that an admin has uploaded, newest first. */
    public function index(Request $request)
    {
        $invoices = Invoice::query()
            ->with('user')
            ->whereNotNull('file_path')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%'.$request->input('search').'%';

                $query->where(function ($q) use ($term) {
                    $q->where('invoice_number', 'like', $term)
                        ->orWhereHas('user', function ($u) use ($term) {
                            $u->where('name', 'like', $term)
                                ->orWhere('surname', 'like', $term)
                                ->orWhere('email', 'like', $term);
                        });
                });
            })
            ->latest('issued_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    /** The upload form. Pass ?customer=ID to preselect the customer. */
    public function create(Request $request)
    {
        $customers = User::query()
            ->orderBy('name')
            ->orderBy('surname')
            ->get(['id', 'name', 'surname', 'email']);

        $selected = $request->integer('customer');

        return view('admin.invoices.create', compact('customers', 'selected'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'invoice_number' => 'nullable|string|max:50',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'status' => 'required|in:pending,paid',
            'issued_at' => 'required|date',
            'description' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'user_id.required' => 'Please choose a customer.',
            'user_id.exists' => 'That customer could not be found.',
            'file.required' => 'Please choose the invoice file to upload.',
            'file.uploaded' => 'The file could not be uploaded. It may be larger than the server allows, so try a smaller file.',
            'file.mimes' => 'The invoice must be a PDF, JPG or PNG file.',
            'file.max' => 'The invoice file must not be larger than 5 MB.',
        ]);

        $customer = User::findOrFail($validated['user_id']);

        // Private disk (storage/app/private): not reachable by URL, only through the download routes.
        $path = $request->file('file')->store('invoices/'.$customer->id, 'local');

        if (! $path) {
            return back()->withInput()->withErrors(['file' => 'The file could not be saved. Please try again.']);
        }

        Invoice::create([
            'user_id' => $customer->id,
            'invoice_number' => $validated['invoice_number'] ?? null,
            'amount' => $validated['amount'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
            'issued_at' => $validated['issued_at'],
            'file_path' => $path,
        ]);

        return redirect()
            ->route('admin.customers.show', $customer)
            ->with('status', 'Invoice uploaded for '.trim($customer->name.' '.$customer->surname).'.');
    }

    public function download(Invoice $invoice)
    {
        $disk = Storage::disk('local');

        abort_unless($invoice->hasFile() && $disk->exists($invoice->file_path), 404);

        return $disk->download($invoice->file_path, $invoice->downloadName());
    }

    public function destroy(Invoice $invoice)
    {
        // Only uploaded invoices can be removed here. Billing records made by the system are left alone.
        abort_unless($invoice->hasFile(), 403);

        Storage::disk('local')->delete($invoice->file_path);
        $invoice->delete();

        return back()->with('status', 'Invoice removed.');
    }
}
