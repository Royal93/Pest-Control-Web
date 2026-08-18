<?php

namespace App\Livewire;

use Livewire\Component;

class ServiceHistory extends Component
{
    public function render()
    {
        $subscription = auth()->user()->subscriptions()->where('status', 'active')->first();
        $visits = $subscription ? $subscription->serviceVisits()->latest('visit_date')->get() : collect();

        return view('livewire.service-history', compact('visits'));
    }
}
