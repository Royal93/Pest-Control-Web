<div>
    <h2 class="font-display uppercase text-sm mb-4">Payment Method</h2>

    @if ($existing)
        <p class="font-mono text-lg">•••• •••• •••• {{ $existing->last4 }}</p>
        <p class="text-inkMuted text-sm">{{ $existing->cardholder_name }} · Exp {{ $existing->exp_month }}/{{ $existing->exp_year }}</p>
    @else
        <p class="text-inkMuted text-sm">No payment method on file.</p>
    @endif

    <button wire:click="$set('showForm', true)" class="border-2 border-line px-4 py-2 text-sm uppercase font-semibold mt-4">
        {{ $existing ? 'Update Card' : 'Add Payment Method' }}
    </button>

    @if ($showForm)
        <form wire:submit.prevent="save" class="space-y-3 mt-4">
            <div>
                <input type="text" wire:model="cardholderName" placeholder="Cardholder Name" class="border-2 border-line p-3 w-full">
                @error('cardholderName') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <input type="text" wire:model="cardNumber" placeholder="Card Number" class="border-2 border-line p-3 w-full">
                @error('cardNumber') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="flex gap-3">
                <div class="w-1/3">
                    <input type="text" wire:model="expMonth" placeholder="MM" class="border-2 border-line p-3 w-full">
                    @error('expMonth') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/3">
                    <input type="text" wire:model="expYear" placeholder="YY" class="border-2 border-line p-3 w-full">
                    @error('expYear') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="w-1/3">
                    <input type="text" wire:model="cvv" placeholder="CVV" class="border-2 border-line p-3 w-full">
                    @error('cvv') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="bg-primary text-white uppercase text-sm font-semibold px-5 py-3 w-full">Save Card</button>
            <p class="text-xs text-inkFaint border-t-2 border-line pt-3">
                Demo only - no real transaction is processed. Card number is checksum-validated (the same
                check real issuers use to catch typos), and only the last 4 digits are ever stored - the
                CVV is never saved, even temporarily. Connect a real payment gateway via the backend for
                live billing.
            </p>
        </form>
    @endif
</div>
