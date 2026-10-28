<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h2 style="color: #7BAE3C; margin-bottom: 4px;">Payment received</h2>
    <p style="color: #666; margin-top: 0;">
        The first payment for {{ $sale->reference() }} has cleared and the service is now active.
        @if ($payment)
            Netcash reference: {{ $payment->reference }}.
        @endif
    </p>
    <p style="color: #666;">Sales representative: {{ $sale->salesRepName() ?: 'Not assigned' }}</p>

    @if ($customer)
        <p style="color: #666;">Customer portal account: {{ $customer->email }}</p>
    @else
        <p style="color: #B45E2E;"><strong>Note:</strong> no customer login was linked because {{ $sale->email }} already belongs to a staff account. Please contact the customer for a different email address.</p>
    @endif

    @include('emails._sale-summary', ['sale' => $sale])

    <p style="color: #999; font-size: 12px; margin-top: 24px;">SP Pest Control | nomorepest.co.za</p>
</body>
</html>
