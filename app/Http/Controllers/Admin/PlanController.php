<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('name')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|string|max:30',
            'description' => 'nullable|string',
            'meta_json' => 'nullable|json',
        ]);

        $plan->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'billing_cycle' => $validated['billing_cycle'],
            'description' => $validated['description'] ?? null,
            'meta' => $request->filled('meta_json') ? json_decode($validated['meta_json'], true) : $plan->meta,
        ]);

        return redirect()->route('admin.plans.index')->with('status', "{$plan->name} updated.");
    }
}
