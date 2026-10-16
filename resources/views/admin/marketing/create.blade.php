@extends('admin.layouts.app')

@section('title', 'Add Marketing Team Member')

@section('content')
<h1 class="font-display uppercase text-2xl mb-6">Add Marketing Team Member</h1>

@if ($errors->any())
    <div class="mb-6 max-w-xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.marketing.store') }}"
      class="max-w-xl border-2 border-line rounded-xl bg-white p-6 flex flex-col gap-5">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">First name</label>
            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Surname</label>
            <input type="text" name="surname" value="{{ old('surname') }}" required maxlength="100"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Email (this is their login)</label>
        <input type="email" name="email" value="{{ old('email') }}" required maxlength="150"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-xs uppercase font-semibold mb-1">Phone (optional)</label>
        <input type="text" name="phone" value="{{ old('phone') }}" maxlength="30"
               class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Password</label>
            <input type="password" name="password" required minlength="8"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs uppercase font-semibold mb-1">Confirm password</label>
            <input type="password" name="password_confirmation" required minlength="8"
                   class="w-full border-2 border-line rounded-lg px-3 py-2 text-sm">
        </div>
    </div>

    <p class="text-xs text-inkFaint">
        Give this email and password to the new team member yourself. They'll log in at the normal
        customer login page and be taken straight to their marketing dashboard.
    </p>

    <div class="flex gap-3 mt-2">
        <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Add Team Member
        </button>
        <a href="{{ route('admin.marketing.index') }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">
            Cancel
        </a>
    </div>
</form>
@endsection
