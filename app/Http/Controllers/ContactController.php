<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

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
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'property_type' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:2000',
        ]);

        Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'property_type' => $validated['property_type'] ?? null,
            'service_interest' => $validated['service'] ?? null,
            'message' => $validated['message'] ?? null,
            'source' => 'contact_form',
        ]);

        return back()->with('success', 'Request received. A technician will follow up shortly to confirm your inspection.');
    }
}
