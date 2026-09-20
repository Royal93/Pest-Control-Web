<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadNotification;
use App\Models\Lead;
use App\Rules\SouthAfricanPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', new SouthAfricanPhone],
            'email' => 'required|email:rfc|max:255',
            'property_type' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:2000',
        ]);

        $lead = Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'property_type' => $validated['property_type'] ?? null,
            'service_interest' => $validated['service'] ?? null,
            'message' => $validated['message'] ?? null,
            'source' => 'contact_form',
        ]);

        // The lead is already saved. If the notification email fails, the client must still
        // see success, and the failure goes to storage/logs/laravel.log for us to fix.
        $notify = env('LEAD_NOTIFY_ADDRESS') ?: config('mail.lead_notify_address', 'admin@nomorepest.co.za');

        try {
            Mail::to($notify)->send(new NewLeadNotification($lead));
        } catch (\Throwable $e) {
            Log::error('Lead notification email failed', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Request received. A technician will follow up shortly to confirm your inspection.');
    }
}
