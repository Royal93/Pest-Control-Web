<?php

namespace App\Livewire;

use Livewire\Component;

class BillingHistory extends Component
{
    public function render()
    {
        $invoices = auth()->user()->invoices()->latest('issued_at')->get();

        return view('livewire.billing-history', compact('invoices'));
    }
}
