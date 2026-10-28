<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; color: #333; max-width: 560px; margin: 0 auto; padding: 24px;">
    <h2 style="color: #D1723C;">Welcome to SP Pest Control</h2>
    <p>Dear {{ $sale->contact_person }},</p>
    <p>We have received your first payment and your service is now active. Thank you for trusting us with your property.</p>

    @if ($setPasswordUrl)
        <p>We have created your customer portal account using <strong>{{ $customer->email }}</strong>. In the portal you can see your plan, service history, invoices and request an extra visit. Please set your password to log in:</p>
        <p style="margin: 24px 0;">
            <a href="{{ $setPasswordUrl }}" style="background: #D1723C; color: #fff; text-decoration: none; padding: 12px 22px; border-radius: 8px; font-weight: bold; display: inline-block;">
                Set My Password
            </a>
        </p>
        <p style="color: #666; font-size: 13px;">This link is valid for 7 days. If it expires, use "Forgot your password?" on the login page with the same email address.</p>
    @else
        <p>Your new service has been added to your existing customer portal account ({{ $customer->email }}). Log in any time at <a href="{{ route('login') }}" style="color: #D1723C;">{{ route('login') }}</a>.</p>
    @endif

    @include('emails._sale-summary', ['sale' => $sale])

    <p style="color: #666; font-size: 13px; margin-top: 24px;">
        Your recurring {{ strtolower($sale->frequencyLabel()) }} debits of R{{ number_format($sale->periodAmount(), 2) }} begin on {{ $sale->firstDebitDate()->format('d M Y') }}.
        Questions? Reply to this email or call us on 011 394 1191.
    </p>
    <p style="color: #999; font-size: 12px; margin-top: 24px;">SP Pest Control | nomorepest.co.za</p>
</body>
</html>
