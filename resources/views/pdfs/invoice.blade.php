<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333333; font-size: 13px; }
        .header { border-bottom: 3px solid #D1723C; padding-bottom: 16px; margin-bottom: 24px; display: table; width: 100%; }
        .header h1 { color: #D1723C; font-size: 20px; margin: 0 0 4px; text-transform: uppercase; }
        .header p { margin: 0; color: #8C8C8C; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.details th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #8C8C8C; border-bottom: 2px solid #D1723C; padding: 8px 0; }
        table.details td { padding: 10px 0; border-bottom: 1px solid #D9D9D9; }
        .total-row td { font-weight: bold; font-size: 16px; border-bottom: none; padding-top: 16px; }
        .status-paid { color: #7BAE3C; font-weight: bold; }
        .status-pending { color: #D1723C; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 10px; color: #8C8C8C; border-top: 1px solid #D9D9D9; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SP Pest Control</h1>
        <p>Invoice #{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <table class="details">
        <tr>
            <th>Customer</th>
            <th>Date Issued</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>{{ $invoice->user->name }} {{ $invoice->user->surname }}</td>
            <td>{{ $invoice->issued_at?->format('d F Y') }}</td>
            <td class="status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</td>
        </tr>
    </table>

    <table class="details">
        <tr>
            <th>Description</th>
            <th style="text-align:right;">Amount</th>
        </tr>
        <tr>
            <td>{{ $invoice->subscription?->plan?->name ?? 'Service' }}</td>
            <td style="text-align:right;">R{{ number_format($invoice->amount, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td style="text-align:right;">R{{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        SP Pest Control &middot; Generated {{ now()->format('d F Y, H:i') }}
    </div>
</body>
</html>
