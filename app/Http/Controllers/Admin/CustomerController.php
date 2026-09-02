<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // NOTE: adjust this if your customer portal model has a different name/namespace
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = "%{$request->input('search')}%";
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        // If a Payment model with a `payable`/user relationship exists by
        // the time this is wired up, pull the customer's payment history
        // here too, e.g.: $customer->load('payments');
        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,' . $customer->id,
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)->with('status', 'Customer updated.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('status', 'Customer removed.');
    }
}
