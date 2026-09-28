@extends('admin.layouts.app')

@section('title', 'Upload Invoice')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Upload Invoice</h1>

@if ($errors->any())
    <div class="mb-6 max-w-xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.invoices.store') }}" enctype="multipart/form-data"
      class="max-w-xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf

    <div>
        <label for="user_id" class="block text-xs uppercase font-semibold mb-1">Customer</label>
        <select id="user_id" name="user_id" required class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
            <option value="">Choose a customer...</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((string) old('user_id', $selected ?: '') === (string) $customer->id)>
                    {{ trim($customer->name.' '.$customer->surname) }} ({{ $customer->email }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="invoice_number" class="block text-xs uppercase font-semibold mb-1">Invoice number (optional)</label>
        <input id="invoice_number" type="text" name="invoice_number" value="{{ old('invoice_number') }}" maxlength="50"
               placeholder="e.g. INV-2026-001"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="amount" class="block text-xs uppercase font-semibold mb-1">Amount (R)</label>
            <input id="amount" type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0" max="999999.99" required
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label for="status" class="block text-xs uppercase font-semibold mb-1">Status</label>
            <select id="status" name="status" required class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
                <option value="pending" @selected(old('status', 'pending') === 'pending')>Pending</option>
                <option value="paid" @selected(old('status') === 'paid')>Paid</option>
            </select>
        </div>
    </div>

    <div>
        <label for="issued_at" class="block text-xs uppercase font-semibold mb-1">Invoice date</label>
        <input id="issued_at" type="date" name="issued_at" value="{{ old('issued_at', now()->format('Y-m-d')) }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label for="description" class="block text-xs uppercase font-semibold mb-1">Description (optional)</label>
        <input id="description" type="text" name="description" value="{{ old('description') }}" maxlength="255"
               placeholder="e.g. Quarterly pest control service"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label for="file" class="block text-xs uppercase font-semibold mb-1">Invoice file</label>
        <input id="file" type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white">
        <p class="text-xs text-inkFaint mt-1">PDF, JPG or PNG, up to 5 MB. Only this customer and the admins can open it.</p>
    </div>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Upload Invoice
        </button>
        <a href="{{ $selected ? route('admin.customers.show', $selected) : route('admin.invoices.index') }}"
           class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</form>
@endsection
