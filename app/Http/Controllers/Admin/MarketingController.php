<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MarketingController extends Controller
{
    /** The marketing/sales team, each with a running count of leads they've captured. */
    public function index()
    {
        $reps = User::where('role', 'marketing')
            ->withCount('capturedLeads as leads_count')
            ->withCount(['capturedLeads as converted_count' => fn ($q) => $q->where('status', 'converted')])
            ->orderBy('name')
            ->get();

        return view('admin.marketing.index', compact('reps'));
    }

    public function create()
    {
        return view('admin.marketing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'marketing',
        ]);

        return redirect()->route('admin.marketing.index')->with('status', 'Marketing team member added. Give them their email and password to log in.');
    }

    /** One rep's own captured leads, for the admin to review their field activity. */
    public function show(User $rep)
    {
        abort_unless($rep->role === 'marketing', 404);

        $leads = $rep->capturedLeads()->latest()->paginate(20);

        return view('admin.marketing.show', compact('rep', 'leads'));
    }

    public function destroy(User $rep)
    {
        abort_unless($rep->role === 'marketing', 404);

        // The leads they captured stay on record; only the login is removed.
        $rep->capturedLeads()->update(['captured_by' => null]);
        $rep->delete();

        return redirect()->route('admin.marketing.index')->with('status', 'Marketing team member removed.');
    }
}
