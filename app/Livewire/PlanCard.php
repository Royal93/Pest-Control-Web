<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Subscription;
use Livewire\Component;

class PlanCard extends Component
{
    public ?Subscription $subscription = null;

    public function mount()
    {
        $this->subscription = auth()->user()->subscriptions()->where('status', 'active')->with('plan')->first();
    }

    public function choosePlan($planId)
    {
        $plan = Plan::findOrFail($planId);

        $this->subscription = auth()->user()->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.plan-card', [
            'plans' => Plan::all(),
        ]);
    }
}
