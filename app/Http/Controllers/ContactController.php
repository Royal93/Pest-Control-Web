<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadNotification;
use App\Models\Lead;
use App\Rules\SouthAfricanPhone;
use Illuminate\Http\Request;
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

        Mail::to(config('mail.lead_notify_address', 'gorongaroyal@gmail.com'))
            ->send(new NewLeadNotification($lead));

        return back()->with('success', 'Request received. A technician will follow up shortly to confirm your inspection.');
    }
}
