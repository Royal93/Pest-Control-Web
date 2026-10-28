{{-- Shared summary table used by every sale email. Expects $sale. --}}
<table style="width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 14px;">
    <tr>
        <td style="padding: 6px 0; color: #666; width: 170px;">Agreement</td>
        <td style="padding: 6px 0;"><strong>{{ $sale->reference() }}</strong></td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Customer / business</td>
        <td style="padding: 6px 0;">{{ $sale->customer_name }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Service address</td>
        <td style="padding: 6px 0;">{{ $sale->service_address }}, {{ $sale->suburb }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Service</td>
        <td style="padding: 6px 0;">{{ $sale->serviceLabel() }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Monthly subscription</td>
        <td style="padding: 6px 0;">R{{ number_format((float) $sale->monthly_subscription, 2) }}</td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Payment frequency</td>
        <td style="padding: 6px 0;">{{ $sale->frequencyLabel() }} (R{{ number_format($sale->periodAmount(), 2) }} per debit)</td>
    </tr>
    @if ((float) $sale->activation_fee > 0)
    <tr>
        <td style="padding: 6px 0; color: #666;">Activation / installation</td>
        <td style="padding: 6px 0;">R{{ number_format((float) $sale->activation_fee, 2) }} (once off)</td>
    </tr>
    @endif
    <tr>
        <td style="padding: 6px 0; color: #666;">First payment</td>
        <td style="padding: 6px 0;"><strong>R{{ number_format($sale->initialPaymentAmount(), 2) }}</strong></td>
    </tr>
    <tr>
        <td style="padding: 6px 0; color: #666;">Service starts</td>
        <td style="padding: 6px 0;">{{ $sale->inception_date?->format('d M Y') }}</td>
    </tr>
</table>
