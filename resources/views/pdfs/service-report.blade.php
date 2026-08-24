<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333333; font-size: 13px; }
        .header { border-bottom: 3px solid #D1723C; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { color: #D1723C; font-size: 20px; margin: 0 0 4px; text-transform: uppercase; }
        .header p { margin: 0; color: #8C8C8C; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        .row { margin-bottom: 14px; }
        .label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #8C8C8C; margin-bottom: 3px; }
        .value { font-size: 14px; }
        .notes-box { border: 2px solid #D1723C; border-radius: 8px; padding: 14px; margin-top: 20px; }
        .footer { margin-top: 40px; font-size: 10px; color: #8C8C8C; border-top: 1px solid #D9D9D9; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SP Pest Control</h1>
        <p>Service Report</p>
    </div>

    <div class="row">
        <div class="label">Customer</div>
        <div class="value">{{ $visit->subscription->user->name }} {{ $visit->subscription->user->surname }}</div>
    </div>

    <div class="row">
        <div class="label">Plan</div>
        <div class="value">{{ $visit->subscription->plan->name }}</div>
    </div>

    <div class="row">
        <div class="label">Visit Date</div>
        <div class="value">{{ $visit->visit_date?->format('d F Y') ?? 'Pending' }}</div>
    </div>

    <div class="row">
        <div class="label">Status</div>
        <div class="value">{{ ucfirst($visit->status) }}</div>
    </div>

    <div class="notes-box">
        <div class="label">Technician Notes</div>
        <div class="value">{{ $visit->technician_notes ?: 'No notes recorded for this visit yet.' }}</div>
    </div>

    <div class="footer">
        SP Pest Control &middot; Generated {{ now()->format('d F Y, H:i') }}
    </div>
</body>
</html>
