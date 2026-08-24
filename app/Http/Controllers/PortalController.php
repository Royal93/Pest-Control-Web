<?php

namespace App\Http\Controllers;

class PortalController extends Controller
{
    public function index()
    {
        $subscription = auth()->user()
            ->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->latest()
            ->first();

        $nextVisit = $subscription
            ? $subscription->serviceVisits()->where('status', 'pending')->orderBy('visit_date')->first()
            : null;

        // The individual dashboard cards (plan, payment method, service/billing
        // history, profile, request form) are all separate Livewire components -
        // see app/Livewire and resources/views/livewire.
        return view('portal.dashboard', compact('subscription', 'nextVisit'));
    }
}
