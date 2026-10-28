<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SaleAgreementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * "Set My Password" from the welcome email a new customer gets once their
 * first payment clears. The link is signed, lasts 7 days and stops working
 * as soon as a password has been set with it.
 */
class WelcomePasswordController extends Controller
{
    public function __construct(private SaleAgreementService $agreements)
    {
    }

    public function show(Request $request, User $user)
    {
        $this->ensureValid($request, $user);

        return view('welcome.set-password', ['user' => $user, 'action' => $request->fullUrl()]);
    }

    public function store(Request $request, User $user)
    {
        $this->ensureValid($request, $user);

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->forceFill(['password' => Hash::make($request->input('password'))])->save();

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->route('portal.index')->with('status', 'Your password is set. Welcome to your customer portal.');
    }

    private function ensureValid(Request $request, User $user): void
    {
        if (! $request->hasValidSignature()
            || ! $user->isCustomer()
            || ! hash_equals($this->agreements->passwordFingerprint($user), (string) $request->query('h'))) {
            abort(403, 'This link has expired or has already been used. Please use "Forgot your password?" on the login page.');
        }
    }
}
