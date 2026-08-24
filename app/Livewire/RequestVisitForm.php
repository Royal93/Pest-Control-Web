<?php

namespace App\Livewire;

use App\Models\ServiceRequest;
use Livewire\Component;

class RequestVisitForm extends Component
{
    public string $message = '';
    public bool $submitted = false;

    public function submit()
    {
        $this->validate([
            'message' => 'required|string|min:10|max:1000',
        ], [
            'message.min' => 'Please give a bit more detail (at least 10 characters) so we know what to expect.',
        ]);

        $subscription = auth()->user()->subscriptions()->where('status', 'active')->latest()->first();

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'subscription_id' => $subscription?->id,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->submitted = true;
    }

    public function requestAnother()
    {
        $this->submitted = false;
    }

    public function render()
    {
        return view('livewire.request-visit-form');
    }
}
