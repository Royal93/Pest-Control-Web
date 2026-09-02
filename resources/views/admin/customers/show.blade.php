@extends('admin.layouts.app')

@section('title', $customer->name)

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">{{ $customer->name }}</h1>

<form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="max-w-xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5 mb-6">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    {{-- TODO once subscriptions/orders are linked to customers: list their
         active plan(s) and payment history here. --}}

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Save Changes
        </button>
        <a href="{{ route('admin.customers.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Back
        </a>
    </div>
</form>

<form method="POST" action="{{ route('admin.customers.destroy', $customer) }}"
      onsubmit="return confirm('Remove this customer? This cannot be undone.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 text-sm font-semibold">Remove Customer</button>
</form>
@endsection
