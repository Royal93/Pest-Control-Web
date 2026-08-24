<div>
    <h2 class="font-display uppercase text-sm mb-4">Request an Extra Visit</h2>

    @if ($submitted)
        <div class="border-2 border-accent p-4 text-sm">
            <p class="mb-3">Request received - a technician will follow up to schedule this, and confirm any excess-visit rate that applies to your plan.</p>
            <button wire:click="requestAnother" class="text-primary text-sm font-semibold uppercase">Submit another request</button>
        </div>
    @else
        <p class="text-inkMuted text-sm mb-4">
            Seeing activity between your scheduled visits? Let us know what's going on and where -
            we'll follow up to arrange a call-out.
        </p>
        <form wire:submit.prevent="submit" class="space-y-4">
            <textarea wire:model="message" rows="4" placeholder="What are you seeing, and where on the property?" class="w-full border-2 border-line p-3"></textarea>
            @error('message') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            <button type="submit" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">
                Send Request
            </button>
        </form>
    @endif
</div>
