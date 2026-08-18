<?php

namespace App\Http\Controllers;

class PortalController extends Controller
{
    public function index()
    {
        // The actual dashboard content (plan, payment method, service/billing history)
        // is rendered by Livewire components — see app/Livewire and resources/views/livewire.
        return view('portal.dashboard');
    }
}
