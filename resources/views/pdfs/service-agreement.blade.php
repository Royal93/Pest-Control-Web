@php
    $box = fn (bool $checked) => '<span class="box">'.($checked ? '&#9746;' : '&#9744;').'</span>';
    $money = fn ($v) => 'R'.number_format((float) $v, 2);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 32px 40px 48px; }
        body { font-family: Helvetica, Arial, sans-serif; color: #333333; font-size: 11px; line-height: 1.4; }
        .box { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { border-bottom: 3px solid #D1723C; padding-bottom: 10px; margin-bottom: 16px; width: 100%; }
        .header td { vertical-align: middle; }
        .brand { color: #D1723C; font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .sub { color: #8C8C8C; font-size: 9px; text-transform: uppercase; letter-spacing: 1px; margin: 2px 0 0; }
        .doc-title { text-align: right; }
        .doc-title h1 { margin: 0; font-size: 16px; text-transform: uppercase; color: #2C343A; }
        .doc-title p { margin: 2px 0 0; color: #8C8C8C; font-size: 10px; }
        .status { display: inline-block; padding: 2px 8px; border-radius: 8px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .status-sent { background: #FEF3C7; color: #B45309; }
        .status-accepted, .status-paid { background: #DCFCE7; color: #15803D; }
        .status-cancelled { background: #FEE2E2; color: #B91C1C; }
        table.form { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.form td { border: 1px solid #BFBFBF; padding: 6px 8px; width: 50%; vertical-align: top; }
        .label { color: #666666; }
        .value { font-weight: bold; }
        h2 { text-align: center; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; background: #F5F1EC; border: 1px solid #BFBFBF; padding: 5px; margin: 18px auto 8px; width: 55%; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        .summary td { padding: 5px 8px; border-bottom: 1px solid #E5E5E5; }
        .summary td.amt { text-align: right; font-weight: bold; }
        .summary tr.total td { border-bottom: none; border-top: 2px solid #D1723C; font-size: 12px; }
        ol.terms { padding-left: 16px; margin: 0; }
        ol.terms li { margin-bottom: 5px; text-align: justify; }
        .declaration { margin: 0 0 8px; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; font-size: 8px; color: #8C8C8C; border-top: 1px solid #D9D9D9; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="footer">
        SP Pest Control | The White House, c/o Kerk St &amp; Monument Rd, Kempton Park | 011 394 1191 | admin@nomorepest.co.za | {{ $sale->reference() }}
    </div>

    <table class="header">
        <tr>
            <td style="width: 60%;">
                <table><tr>
                    @if ($logo)
                        <td style="padding-right: 10px;"><img src="{{ $logo }}" style="width: 52px; height: 52px;"></td>
                    @endif
                    <td>
                        <p class="brand">SP Pest Control</p>
                        <p class="sub">nomorepest.co.za</p>
                    </td>
                </tr></table>
            </td>
            <td class="doc-title">
                <h1>Service Agreement</h1>
                <p>{{ $sale->reference() }}</p>
                <p><span class="status status-{{ $sale->status }}">
                    {{ $sale->isAwaitingAcceptance() ? 'Awaiting acceptance' : ($sale->isCancelled() ? 'Cancelled' : 'Accepted') }}
                </span></p>
            </td>
        </tr>
    </table>

    <table class="form">
        <tr>
            <td><span class="label">Customer / business:</span> <span class="value">{{ $sale->customer_name }}</span></td>
            <td><span class="label">Contact person:</span> <span class="value">{{ $sale->contact_person }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Phone:</span> <span class="value">{{ $sale->phone }}</span></td>
            <td><span class="label">Email:</span> <span class="value">{{ $sale->email }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Service address:</span> <span class="value">{{ $sale->service_address }}</span></td>
            <td><span class="label">Suburb / city:</span> <span class="value">{{ $sale->suburb }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Property:</span> <span class="value">{{ $sale->propertyLabel() }}</span></td>
            <td>
                <span class="label">Service selected:</span>
                {!! $box($sale->service === 'cockroach') !!} Cockroach control
                {!! $box($sale->service === 'rodent') !!} Rodent control
                {!! $box($sale->service === 'both') !!} Both
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Children or pets on premises?</span>
                {!! $box($sale->children_or_pets) !!} Yes
                {!! $box(! $sale->children_or_pets) !!} No
            </td>
            <td><span class="label">Rodent only, proposed bait stations:</span> <span class="value">{{ $sale->includesRodent() ? $sale->bait_stations : 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Agreement date:</span> <span class="value">{{ $sale->agreement_date?->format('d M Y') }}</span></td>
            <td><span class="label">Roach only, treatment areas:</span> <span class="value">{{ $sale->includesCockroach() ? $sale->treatment_areas : 'N/A' }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Inception contract date:</span> <span class="value">{{ $sale->inception_date?->format('d M Y') }}</span></td>
            <td><span class="label">Total activation or installation fee:</span> <span class="value">{{ $money($sale->activation_fee) }}</span></td>
        </tr>
        <tr>
            <td><span class="label">Total monthly subscription:</span> <span class="value">{{ $money($sale->monthly_subscription) }}</span></td>
            <td>
                <span class="label">Payment frequency:</span>
                {!! $box($sale->payment_frequency === 'monthly') !!} Monthly
                {!! $box($sale->payment_frequency === 'quarterly') !!} Quarterly
                {!! $box($sale->payment_frequency === 'biannual') !!} Biannual
                {!! $box($sale->payment_frequency === 'annual') !!} Annual
            </td>
        </tr>
    </table>

    <h2>Payment Summary</h2>
    <table class="summary">
        @if ((float) $sale->activation_fee > 0)
            <tr><td>Activation / installation fee (once off)</td><td class="amt">{{ $money($sale->activation_fee) }}</td></tr>
        @endif
        <tr><td>First {{ strtolower($sale->frequencyLabel()) }} subscription period ({{ $sale->monthsPerPeriod() }} {{ $sale->monthsPerPeriod() === 1 ? 'month' : 'months' }} at {{ $money($sale->monthly_subscription) }})</td><td class="amt">{{ $money($sale->periodAmount()) }}</td></tr>
        <tr class="total"><td>First payment, via secure payment link</td><td class="amt">{{ $money($sale->initialPaymentAmount()) }}</td></tr>
        <tr><td colspan="2" style="border-bottom: none; color: #666666;">
            Thereafter {{ $money($sale->periodAmount()) }} debited {{ strtolower($sale->frequencyLabel()) }}, starting {{ $sale->firstDebitDate()->format('d M Y') }}. All amounts include VAT where applicable.
        </td></tr>
    </table>

    <h2>Service Terms &amp; Conditions</h2>
    <ol class="terms">
        <li>SP Pest Control will provide only the selected service(s) at the address above. Cockroach treatments are generally scheduled approximately every three months; rodent maintenance is scheduled every month.</li>
        <li>Subscription charges are billed and debited monthly unless a different payment frequency (quarterly, biannual or annual) is expressly agreed and recorded above. Initial activation/installation charges are separate.</li>
        <li>The customer must provide safe access, disclose relevant hazards and follow reasonable treatment and safety instructions. Bait-station quantities and treatment requirements may be adjusted after site assessment, with any additional charge approved before proceeding.</li>
        <li>This agreement covers the selected pests only. Other pests, structural repairs, damage caused by infestation and services outside the agreed scope are excluded unless separately quoted and accepted.</li>
        <li>The initial contract term is twelve (12) months. After the initial term, the agreement continues month-to-month and may be cancelled on thirty (30) days' written notice. Statutory early-cancellation rights remain unaffected. Any price change requires prior written notice and must comply with applicable law.</li>
        <li>A secure payment link will be sent for the first initial payment. Following successful payment, recurring monthly debits will commence on the agreed debit date, unless another frequency is recorded above. Recurring debits require the customer's express authorisation through the payment link or a separate debit-order mandate. Service activation follows payment confirmation.</li>
    </ol>

    <h2>Customer Acceptance</h2>
    <p class="declaration">I confirm that the information above is correct, the selected service and charges have been explained, and I have received or can access these terms before accepting.</p>
    <table class="form">
        <tr>
            <td><span class="label">Customer name:</span> <span class="value">{{ $sale->accepted_name ?: '' }}</span></td>
            <td>
                <span class="label">Accepted electronically:</span>
                {!! $box((bool) $sale->accepted_at) !!} Yes
                {!! $box(! $sale->accepted_at) !!} No
            </td>
        </tr>
        <tr>
            <td><span class="label">Acceptance date/time:</span> <span class="value">{{ $sale->accepted_at?->format('d M Y H:i') }}</span></td>
            <td><span class="label">Sales representative:</span> <span class="value">{{ $sale->salesRepName() ?: 'SP Pest Control' }}</span></td>
        </tr>
    </table>
    @if ($sale->accepted_at)
        <p style="font-size: 8px; color: #8C8C8C;">Accepted online via the customer's private agreement link. Recorded IP address: {{ $sale->accepted_ip }}.</p>
    @endif

</body>
</html>
