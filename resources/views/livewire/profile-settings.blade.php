<div class="space-y-8">
    <div>
        <h2 class="font-display uppercase text-sm mb-4">Account Details</h2>

        @if ($profileMessage)
            <div class="border-2 border-accent p-3 mb-4 text-sm">{{ $profileMessage }}</div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="flex flex-col gap-2 text-sm text-inkMuted">
                    Name
                    <input type="text" wire:model="name" class="border-2 border-line p-3">
                    @error('name') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col gap-2 text-sm text-inkMuted">
                    Surname
                    <input type="text" wire:model="surname" class="border-2 border-line p-3">
                    @error('surname') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </label>
            </div>
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Email
                <input type="email" wire:model="email" class="border-2 border-line p-3">
                @error('email') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            </label>
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Phone Number
                <input type="tel" wire:model="phone" class="border-2 border-line p-3">
                @error('phone') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            </label>
            <button type="submit" class="bg-primary text-white uppercase text-sm font-semibold px-6 py-3">
                Save Details
            </button>
        </form>
    </div>

    <div class="border-t-2 border-line pt-8">
        <h2 class="font-display uppercase text-sm mb-4">Change Password</h2>

        @if ($passwordMessage)
            <div class="border-2 border-accent p-3 mb-4 text-sm">{{ $passwordMessage }}</div>
        @endif

        <form wire:submit.prevent="updatePassword" class="space-y-4">
            <label class="flex flex-col gap-2 text-sm text-inkMuted">
                Current Password
                <input type="password" wire:model="current_password" class="border-2 border-line p-3">
                @error('current_password') <span class="text-signal text-xs">{{ $message }}</span> @enderror
            </label>
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="flex flex-col gap-2 text-sm text-inkMuted">
                    New Password
                    <input type="password" wire:model="new_password" class="border-2 border-line p-3">
                    @error('new_password') <span class="text-signal text-xs">{{ $message }}</span> @enderror
                </label>
                <label class="flex flex-col gap-2 text-sm text-inkMuted">
                    Confirm New Password
                    <input type="password" wire:model="new_password_confirmation" class="border-2 border-line p-3">
                </label>
            </div>
            <button type="submit" class="border-2 border-line uppercase text-sm font-semibold px-6 py-3">
                Update Password
            </button>
        </form>
    </div>
</div>
