<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Subscription;
use Livewire\Component;

class PlanCard extends Component
{
    public ?Subscription $subscription = null;
    public bool $showPicker = false;
    public bool $confirmingCancel = false;

    public function mount()
    {
        $this->loadSubscription();
    }

    protected function loadSubscription()
    {
        $this->subscription = auth()->user()
            ->subscriptions()
            ->where('status', 'active')
            ->with('plan')
            ->latest()
            ->first();
    }

    public function openPicker()
    {
        $this->showPicker = true;
    }

    public function cancelPickerView()
    {
        $this->showPicker = false;
    }

    public function choosePlan($planId)
    {
        $plan = Plan::findOrFail($planId);

        // Cancel any existing active subscription first, so history is preserved
        // rather than overwritten.
        if ($this->subscription) {
            $this->subscription->update(['status' => 'cancelled']);
        }

        auth()->user()->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'active',
            'started_at' => now(),
        ]);

        $this->showPicker = false;
        $this->loadSubscription();
    }

    public function confirmCancel()
    {
        $this->confirmingCancel = true;
    }

    public function abortCancel()
    {
        $this->confirmingCancel = false;
    }

    public function cancelSubscription()
    {
        if ($this->subscription) {
            $this->subscription->update(['status' => 'cancelled']);
        }
        $this->confirmingCancel = false;
        $this->loadSubscription();
    }

    public function render()
    {
        return view('livewire.plan-card', [
            'plans' => Plan::all(),
        ]);
    }
}
