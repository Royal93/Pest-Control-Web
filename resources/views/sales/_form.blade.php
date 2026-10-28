{{--
    Capture a Sale questionnaire, shared by the admin portal and SP SALES.
    Expects: $action, $prefill (array), $pricing (array), $cancelUrl,
             optional $lead, optional $reps (admin only) and $selectedRepId.
--}}
@php
    $v = fn (string $field, $default = '') => old($field, $prefill[$field] ?? $default);
    $input = 'w-full border-2 border-line rounded-lg px-3 py-2 text-sm bg-white';
    $label = 'block text-xs uppercase font-semibold mb-1';
@endphp

@if ($errors->any())
    <div class="mb-6 max-w-3xl border-2 border-red-500 bg-red-50 text-ink px-4 py-3 rounded-lg text-sm">
        <p class="font-semibold mb-1">Please fix the following:</p>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $action }}" id="saleForm" class="max-w-3xl flex flex-col gap-6">
    @csrf
    @if (!empty($lead))
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
    @endif

    {{-- 1. Customer --}}
    <section class="border-2 border-line rounded-xl bg-white p-6">
        <h2 class="font-display uppercase text-sm mb-4">1. Customer details</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}" for="customer_name">Customer / business</label>
                <input id="customer_name" type="text" name="customer_name" value="{{ $v('customer_name') }}" required maxlength="255" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="contact_person">Contact person</label>
                <input id="contact_person" type="text" name="contact_person" value="{{ $v('contact_person') }}" required maxlength="255" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="phone">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ $v('phone') }}" required placeholder="e.g. 082 123 4567" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ $v('email') }}" required maxlength="255" class="{{ $input }}">
                <p class="text-xs text-inkFaint mt-1">The agreement is sent to this address.</p>
            </div>
            <div>
                <label class="{{ $label }}" for="service_address">Service address</label>
                <input id="service_address" type="text" name="service_address" value="{{ $v('service_address') }}" required maxlength="255" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="suburb">Suburb / city</label>
                <input id="suburb" type="text" name="suburb" value="{{ $v('suburb') }}" required maxlength="120" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="property_type">Property</label>
                <select id="property_type" name="property_type" required class="{{ $input }}">
                    <option value="">Select…</option>
                    @foreach (\App\Models\Sale::PROPERTY_TYPES as $key => $text)
                        <option value="{{ $key }}" @selected($v('property_type') === $key)>{{ $text }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <span class="{{ $label }}">Children or pets on premises?</span>
                <div class="flex gap-6 py-2 text-sm">
                    <label class="flex items-center gap-2"><input type="radio" name="children_or_pets" value="yes" required @checked($v('children_or_pets') === 'yes')> Yes</label>
                    <label class="flex items-center gap-2"><input type="radio" name="children_or_pets" value="no" @checked($v('children_or_pets') === 'no')> No</label>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Service --}}
    <section class="border-2 border-line rounded-xl bg-white p-6">
        <h2 class="font-display uppercase text-sm mb-4">2. Service</h2>
        <span class="{{ $label }}">Service selected</span>
        <div class="flex flex-wrap gap-3 mb-5">
            @foreach (\App\Models\Sale::SERVICES as $key => $text)
                <label class="flex items-center gap-2 border-2 border-line rounded-lg px-4 py-2 text-sm cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white">
                    <input type="radio" name="service" value="{{ $key }}" required @checked($v('service') === $key) class="accent-primary">
                    {{ $text }}
                </label>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div data-show-for="rodent both">
                <label class="{{ $label }}" for="bait_stations">Rodent: proposed bait stations</label>
                <input id="bait_stations" type="number" name="bait_stations" value="{{ $v('bait_stations') }}" min="1" max="200" class="{{ $input }}">
            </div>
            <div data-show-for="cockroach both" class="md:col-span-2">
                <label class="{{ $label }}" for="treatment_areas">Cockroach: treatment areas</label>
                <textarea id="treatment_areas" name="treatment_areas" rows="2" maxlength="1000" placeholder="e.g. Kitchen, bathrooms, scullery" class="{{ $input }}">{{ $v('treatment_areas') }}</textarea>
            </div>
        </div>
    </section>

    {{-- 3. Dates and charges --}}
    <section class="border-2 border-line rounded-xl bg-white p-6">
        <h2 class="font-display uppercase text-sm mb-4">3. Dates and charges</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}" for="agreement_date">Agreement date</label>
                <input id="agreement_date" type="date" name="agreement_date" value="{{ $v('agreement_date', now()->toDateString()) }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="inception_date">Inception contract date</label>
                <input id="inception_date" type="date" name="inception_date" value="{{ $v('inception_date', now()->toDateString()) }}" required class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="activation_fee">Total activation or installation fee (R)</label>
                <input id="activation_fee" type="number" name="activation_fee" value="{{ $v('activation_fee') }}" min="0" step="0.01" class="{{ $input }}">
            </div>
            <div>
                <label class="{{ $label }}" for="monthly_subscription">Total monthly subscription (R)</label>
                <input id="monthly_subscription" type="number" name="monthly_subscription" value="{{ $v('monthly_subscription') }}" min="5" step="0.01" required class="{{ $input }}">
            </div>
            <div class="md:col-span-2">
                <span class="{{ $label }}">Payment frequency</span>
                <div class="flex flex-wrap gap-3">
                    @foreach (\App\Models\Sale::FREQUENCIES as $key => $freq)
                        <label class="flex items-center gap-2 border-2 border-line rounded-lg px-4 py-2 text-sm cursor-pointer has-[:checked]:bg-primary has-[:checked]:text-white">
                            <input type="radio" name="payment_frequency" value="{{ $key }}" required @checked($v('payment_frequency', 'monthly') === $key) class="accent-primary">
                            {{ $freq['label'] }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <p class="text-xs text-inkFaint mt-3">Prices fill in from Plans &amp; Pricing when you choose a service. You can change them for this customer.</p>

        <div id="saleSummary" class="mt-5 bg-bgAlt rounded-lg p-4 text-sm grid sm:grid-cols-3 gap-3">
            <div><div class="text-xs uppercase text-inkFaint">Each debit</div><div class="font-display text-lg" data-sum="period">R0.00</div></div>
            <div><div class="text-xs uppercase text-inkFaint">First payment</div><div class="font-display text-lg text-primary" data-sum="initial">R0.00</div></div>
            <div><div class="text-xs uppercase text-inkFaint">Recurring debits start</div><div class="font-display text-lg" data-sum="debit">-</div></div>
        </div>
    </section>

    @if (isset($reps))
        <section class="border-2 border-line rounded-xl bg-white p-6">
            <h2 class="font-display uppercase text-sm mb-4">4. Sales representative</h2>
            <select name="sales_rep_id" class="{{ $input }} max-w-sm">
                <option value="">None (sold by the office)</option>
                @foreach ($reps as $rep)
                    <option value="{{ $rep->id }}" @selected((string) old('sales_rep_id', $selectedRepId ?? '') === (string) $rep->id)>{{ $rep->fullName() }}</option>
                @endforeach
            </select>
            <p class="text-xs text-inkFaint mt-1">Shown on the agreement, and the rep gets a copy of every email.</p>
        </section>
    @endif

    <section class="border-2 border-line rounded-xl bg-white p-6">
        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="explained" value="1" required class="mt-1" @checked(old('explained'))>
            <span>I have explained the selected service and charges to the customer. When I submit, the personalised service agreement is emailed to the customer to accept online.</span>
        </label>
        <div class="flex flex-wrap gap-3 mt-5">
            <button type="submit" class="border-2 border-primary bg-primary text-white uppercase text-sm font-semibold px-6 py-2 rounded-lg">
                Send Agreement to Customer
            </button>
            <a href="{{ $cancelUrl }}" class="border-2 border-line uppercase text-sm font-semibold px-6 py-2 rounded-lg">Cancel</a>
        </div>
    </section>
</form>

<script>
(function () {
    const form = document.getElementById('saleForm');
    if (!form) return;

    const pricing = @json($pricing);
    const months = { monthly: 1, quarterly: 3, biannual: 6, annual: 12 };
    const fee = form.querySelector('#activation_fee');
    const monthly = form.querySelector('#monthly_subscription');
    const inception = form.querySelector('#inception_date');
    // Only auto-fill prices the user hasn't typed over themselves.
    let touched = { fee: fee.value !== '', monthly: monthly.value !== '' };
    fee.addEventListener('input', () => touched.fee = true);
    monthly.addEventListener('input', () => touched.monthly = true);

    const checked = (name) => (form.querySelector('input[name="' + name + '"]:checked') || {}).value;
    const rand = (n) => 'R' + (Math.round(n * 100) / 100).toLocaleString('en-ZA', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    function toggleSections() {
        const service = checked('service');
        form.querySelectorAll('[data-show-for]').forEach((el) => {
            const show = !!service && el.dataset.showFor.split(' ').includes(service);
            el.style.display = show ? '' : 'none';
            el.querySelectorAll('input, textarea').forEach((f) => f.required = show);
        });
    }

    function applyPricing() {
        const p = pricing[checked('service')];
        if (!p) return;
        if (!touched.fee) fee.value = p.activation.toFixed(2);
        if (!touched.monthly && p.monthly > 0) monthly.value = p.monthly.toFixed(2);
    }

    function summarise() {
        const m = months[checked('payment_frequency')] || 1;
        const period = (parseFloat(monthly.value) || 0) * m;
        const initial = period + (parseFloat(fee.value) || 0);
        form.querySelector('[data-sum="period"]').textContent = rand(period);
        form.querySelector('[data-sum="initial"]').textContent = rand(initial);

        let start = inception.value ? new Date(inception.value + 'T00:00:00') : new Date();
        const today = new Date(); today.setHours(0, 0, 0, 0);
        if (start < today) start = today;
        const day = start.getDate();
        start.setDate(1);
        start.setMonth(start.getMonth() + m);
        const last = new Date(start.getFullYear(), start.getMonth() + 1, 0).getDate();
        start.setDate(Math.min(day, last));
        form.querySelector('[data-sum="debit"]').textContent = start.toLocaleDateString('en-ZA', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    form.addEventListener('change', (e) => {
        if (e.target.name === 'service') { toggleSections(); applyPricing(); }
        summarise();
    });
    form.addEventListener('input', summarise);

    toggleSections();
    if (!touched.fee && !touched.monthly) applyPricing();
    summarise();
})();
</script>
