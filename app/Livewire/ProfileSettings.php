<?php

namespace App\Livewire;

use App\Rules\SouthAfricanPhone;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ProfileSettings extends Component
{
    public string $name = '';
    public string $surname = '';
    public string $email = '';
    public string $phone = '';

    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public string $profileMessage = '';
    public string $passwordMessage = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->surname = $user->surname ?? '';
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function updateProfile()
    {
        $this->profileMessage = '';

        $this->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255|unique:users,email,'.auth()->id(),
            'phone' => ['required', 'string', new SouthAfricanPhone],
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->profileMessage = 'Profile details updated.';
    }

    public function updatePassword()
    {
        $this->passwordMessage = '';

        $this->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! Hash::check($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->passwordMessage = 'Password updated.';
    }

    public function render()
    {
        return view('livewire.profile-settings');
    }
}
