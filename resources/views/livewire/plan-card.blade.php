<div>
    <h2 class="font-display uppercase text-sm mb-4">Protection Plan</h2>

    @if ($subscription)
        <p class="font-display uppercase text-lg text-primary">{{ $subscription->plan->name }}</p>
        <p class="font-mono text-inkMuted text-sm mt-1">R{{ number_format($subscription->plan->price, 0) }}/mo</p>
        <p class="text-inkMuted text-sm mt-3">Next visit: technician will confirm by phone.</p>
    @else
        <p class="text-inkMuted text-sm mb-4">No active plan yet. Choose one to subscribe:</p>
        <div class="space-y-2">
            @foreach ($plans as $plan)
                <button wire:click="choosePlan({{ $plan->id }})" class="w-full flex justify-between border-2 border-line bg-bgAlt px-4 py-3 text-sm">
                    <span>{{ $plan->name }}</span>
                    <span>R{{ number_format($plan->price, 0) }}/mo</span>
                </button>
            @endforeach
        </div>
    @endif
</div>
