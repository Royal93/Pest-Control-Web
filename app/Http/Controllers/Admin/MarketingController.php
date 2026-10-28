<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * The Sales Team (shown to the client as "Sales Team"; stored as role = marketing
 * on the users table, kept as-is so existing logins carry on working).
 */
class MarketingController extends Controller
{
    /** The sales team, each with their assigned, converted and sales counts. */
    public function index()
    {
        $reps = User::salesReps()
            ->withCount('assignedLeads as leads_count')
            ->withCount(['assignedLeads as open_count' => fn ($q) => $q->whereIn('status', \App\Models\Lead::IN_PROGRESS_STATUSES)])
            ->withCount(['assignedLeads as converted_count' => fn ($q) => $q->where('status', 'converted')])
            ->withCount(['repSales as sales_count' => fn ($q) => $q->where('status', 'paid')])
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

        return redirect()->route('admin.marketing.index')->with('status', 'Sales team member added. Give them their email and password to log in.');
    }

    /** One rep's assigned leads and sales, for admin to review their activity. */
    public function show(User $rep)
    {
        abort_unless($rep->role === 'marketing', 404);

        $leads = $rep->assignedLeads()->latest('assigned_at')->latest()->paginate(20, ['*'], 'leads_page');
        $sales = $rep->repSales()->latest()->limit(20)->get();

        return view('admin.marketing.show', compact('rep', 'leads', 'sales'));
    }

    public function destroy(User $rep)
    {
        abort_unless($rep->role === 'marketing', 404);

        // Their leads and sales stay on record; only the login is removed. Open
        // leads drop back to unassigned so admin can hand them to someone else.
        $rep->capturedLeads()->update(['captured_by' => null]);
        $rep->assignedLeads()->update(['assigned_to' => null, 'assigned_at' => null]);
        $rep->delete();

        return redirect()->route('admin.marketing.index')->with('status', 'Sales team member removed. Their open leads are now unassigned.');
    }
}
