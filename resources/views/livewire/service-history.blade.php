<div>
    <h2 class="font-display uppercase text-sm mb-4">Service History</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left font-mono text-xs uppercase text-inkFaint border-b-2 border-line">
                <th class="py-2">Date</th>
                <th class="py-2">Notes</th>
                <th class="py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($visits as $visit)
                <tr class="border-b-2 border-line">
                    <td class="py-2">{{ $visit->visit_date?->format('Y-m-d') ?? 'Pending' }}</td>
                    <td class="py-2 text-inkMuted">{{ $visit->technician_notes }}</td>
                    <td class="py-2 text-inkMuted">{{ ucfirst($visit->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-3 text-inkFaint">No services scheduled yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
