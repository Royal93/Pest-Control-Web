<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = AdminUser::orderBy('name')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:admin_users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        AdminUser::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.admins.index')->with('status', 'Admin added.');
    }

    public function destroy(AdminUser $admin)
    {
        // Two safety rails: can't delete yourself while logged in as that
        // account, and can't delete the last remaining admin — either of
        // those would lock everyone out of the dashboard.
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', "You can't remove your own account while logged in as it.");
        }

        if (AdminUser::count() <= 1) {
            return back()->with('error', 'At least one admin account must remain.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('status', 'Admin removed.');
    }
}
