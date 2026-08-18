<div>
    <h2 class="font-display uppercase text-sm mb-4">Billing History</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left font-mono text-xs uppercase text-inkFaint border-b-2 border-line">
                <th class="py-2">Date</th>
                <th class="py-2">Amount</th>
                <th class="py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr class="border-b-2 border-line">
                    <td class="py-2">{{ $invoice->issued_at?->format('Y-m-d') }}</td>
                    <td class="py-2 text-inkMuted">R{{ number_format($invoice->amount, 2) }}</td>
                    <td class="py-2 text-inkMuted">{{ ucfirst($invoice->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-3 text-inkFaint">No billing history yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
