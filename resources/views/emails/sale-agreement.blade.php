<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">

@if ($internal)
    <h2 style="color: #D1723C; margin-bottom: 4px;">
        {{ $stage === 'accepted' ? 'Agreement accepted by the customer' : 'Service agreement sent' }}
    </h2>
    <p style="color: #666; margin-top: 0;">
        @if ($stage === 'accepted')
            {{ $sale->accepted_name }} accepted electronically on {{ $sale->accepted_at?->format('d M Y \a\t H:i') }}.
            The customer has been sent the secure payment link.
        @else
            The agreement below was emailed to {{ $sale->email }} for review and acceptance.
        @endif
    </p>
    <p style="color: #666; margin-top: 0;">
        Sales representative: {{ $sale->salesRepName() ?: 'Not assigned' }}
    </p>
@elseif ($stage === 'accepted')
    <h2 style="color: #D1723C;">Thank you, {{ $sale->contact_person }}</h2>
    <p>Your service agreement has been accepted. A copy of the accepted agreement is attached for your records.</p>
    <p><strong>Next step:</strong> please complete your first payment using the secure link below. Your service is activated as soon as the payment is confirmed, and your recurring debits will begin on {{ $sale->firstDebitDate()->format('d M Y') }}.</p>
    <p style="margin: 24px 0;">
        <a href="{{ $sale->paymentUrl() }}" style="background: #D1723C; color: #fff; text-decoration: none; padding: 12px 22px; border-radius: 8px; font-weight: bold; display: inline-block;">
            Make Secure Payment
        </a>
    </p>
@else
    <h2 style="color: #D1723C;">Your Service Agreement</h2>
    <p>Dear {{ $sale->contact_person }},</p>
    <p>Thank you for choosing SP Pest Control. Your personalised service agreement is attached and summarised below. Please review it, then accept it online using the button below.</p>
    <p style="margin: 24px 0;">
        <a href="{{ $sale->agreementUrl() }}" style="background: #D1723C; color: #fff; text-decoration: none; padding: 12px 22px; border-radius: 8px; font-weight: bold; display: inline-block;">
            Review and Accept Agreement
        </a>
    </p>
@endif

@include('emails._sale-summary', ['sale' => $sale])

@unless ($internal)
    <p style="color: #666; font-size: 13px; margin-top: 24px;">
        If the button does not work, copy this link into your browser:<br>
        <span style="word-break: break-all;">{{ $stage === 'accepted' ? $sale->paymentUrl() : $sale->agreementUrl() }}</span>
    </p>
    <p style="color: #666; font-size: 13px;">
        Questions? Reply to this email or call us on 011 394 1191.
    </p>
@endunless

<p style="color: #999; font-size: 12px; margin-top: 24px;">SP Pest Control | nomorepest.co.za</p>
</body>
</html>
