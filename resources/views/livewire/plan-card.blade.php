<div>
    <h2 class="font-display uppercase text-sm mb-4">Protection Plan</h2>

    @if ($subscription && !$showPicker)
        <p class="font-display uppercase text-lg text-primary">{{ $subscription->plan->name }}</p>
        <p class="font-mono text-inkMuted text-sm mt-1">R{{ number_format($subscription->plan->price, 0) }}/mo</p>
        <p class="text-inkMuted text-sm mt-3">Next visit: technician will confirm by phone.</p>

        <div class="flex gap-4 mt-4">
            <button wire:click="openPicker" class="text-primary text-sm font-semibold uppercase">Change Plan</button>
            <button wire:click="confirmCancel" class="text-signal text-sm font-semibold uppercase">Cancel Plan</button>
        </div>

        @if ($confirmingCancel)
            <div class="border-2 border-signal p-4 mt-4 text-sm">
                <p class="mb-3">Cancel {{ $subscription->plan->name }}? This stops future scheduled visits under this plan.</p>
                <div class="flex gap-3">
                    <button wire:click="cancelSubscription" class="bg-signal text-white text-xs uppercase font-semibold px-4 py-2">Yes, Cancel</button>
                    <button wire:click="abortCancel" class="border-2 border-line text-xs uppercase font-semibold px-4 py-2">Keep Plan</button>
                </div>
            </div>
        @endif
    @else
        <p class="text-inkMuted text-sm mb-4">
            {{ $subscription ? 'Choose a new plan:' : 'No active plan yet. Choose one to subscribe:' }}
        </p>
        <div class="space-y-2">
            @foreach ($plans as $plan)
                <button wire:click="choosePlan({{ $plan->id }})" class="w-full flex justify-between border-2 border-line bg-bgAlt px-4 py-3 text-sm">
                    <span>{{ $plan->name }}</span>
                    <span>R{{ number_format($plan->price, 0) }}/mo</span>
                </button>
            @endforeach
        </div>
        @if ($subscription)
            <button wire:click="cancelPickerView" class="text-inkMuted text-xs uppercase font-semibold mt-3">Cancel</button>
        @endif
    @endif
</div>
