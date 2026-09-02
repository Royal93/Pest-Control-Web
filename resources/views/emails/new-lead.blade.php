<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family: sans-serif; color: #333; max-width: 480px; margin: 0 auto; padding: 24px;">
    <h2 style="color: #D1723C;">New Inspection Request</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 16px;">
        <tr>
            <td style="padding: 6px 0; color: #666; width: 140px;">Name</td>
            <td style="padding: 6px 0;"><strong>{{ $lead->name }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #666;">Phone</td>
            <td style="padding: 6px 0;">{{ $lead->phone }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #666;">Email</td>
            <td style="padding: 6px 0;">{{ $lead->email }}</td>
        </tr>
        @if ($lead->property_type)
        <tr>
            <td style="padding: 6px 0; color: #666;">Property Type</td>
            <td style="padding: 6px 0;">{{ $lead->property_type }}</td>
        </tr>
        @endif
        @if ($lead->service_interest)
        <tr>
            <td style="padding: 6px 0; color: #666;">Service</td>
            <td style="padding: 6px 0;">{{ $lead->service_interest }}</td>
        </tr>
        @endif
    </table>

    @if ($lead->message)
        <p style="color: #666; margin-top: 20px; margin-bottom: 4px;">Message</p>
        <p style="background: #F5F1EC; padding: 12px; border-radius: 8px;">{{ $lead->message }}</p>
    @endif

    <p style="color: #999; font-size: 12px; margin-top: 24px;">
        Submitted via the website contact form.
    </p>
</body>
</html>
