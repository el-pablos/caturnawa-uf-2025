<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $payment->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }

        .invoice-container {
            width: 850px;
            height: 1124px;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* Dynamic text overlays positioned according to SVG */
        .dynamic-text {
            position: absolute;
            font-family: Arial, sans-serif;
            color: #154C8C;
            font-size: 12px;
            font-weight: normal;
        }

        /* Text positioning based on SVG coordinates with wider header */
        .invoice-number { left: 50px; top: 261px; }
        .order-date { left: 450px; top: 261px; }
        .participant-name { left: 50px; top: 300px; }
        .participant-institution { left: 450px; top: 300px; }
        .participant-email { left: 50px; top: 339px; }
        .participant-phone { left: 450px; top: 339px; }
        .competition-name { left: 50px; top: 378px; }
        .competition-category { left: 450px; top: 378px; }
        .team-name { left: 50px; top: 417px; }
        .team-members { left: 450px; top: 417px; }
        .payment-amount { left: 50px; top: 456px; }
        .payment-method { left: 450px; top: 456px; }

        /* Status and additional info */
        .payment-status { left: 50px; top: 495px; color: #16a34a; font-weight: bold; }
        .payment-date { left: 450px; top: 495px; }
        .transaction-id { left: 50px; top: 534px; }
        .notes { left: 50px; top: 573px; width: 750px; }

        @media print {
            body { margin: 0; }
            .invoice-container { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- SVG Background Template -->
        <svg width="850" height="1124" viewBox="0 0 850 1124" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Header Background - Made wider -->
            <rect x="0" y="0" width="850" height="200" fill="#154C8C"/>

            <!-- Header Title -->
            <text x="50" y="60" fill="white" font-family="Arial" font-size="36" font-weight="bold">INVOICE</text>
            <text x="50" y="90" fill="white" font-family="Arial" font-size="16">UNAS FEST 2025</text>
            <text x="50" y="110" fill="white" font-family="Arial" font-size="14">Festival Kompetisi Nasional</text>

            <!-- Logo Placeholder -->
            <rect x="700" y="30" width="120" height="80" fill="none" stroke="white" stroke-width="2"/>
            <text x="760" y="75" fill="white" font-family="Arial" font-size="12" text-anchor="middle">LOGO</text>

            <!-- Invoice Details Section -->
            <rect x="30" y="230" width="790" height="300" fill="none" stroke="#E5E7EB" stroke-width="1"/>

            <!-- Labels -->
            <text x="50" y="250" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">INVOICE NUMBER</text>
            <text x="450" y="250" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">ORDER DATE</text>

            <text x="50" y="289" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">PARTICIPANT NAME</text>
            <text x="450" y="289" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">INSTITUTION</text>

            <text x="50" y="328" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">EMAIL</text>
            <text x="450" y="328" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">PHONE</text>

            <text x="50" y="367" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">COMPETITION</text>
            <text x="450" y="367" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">CATEGORY</text>

            <text x="50" y="406" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">TEAM NAME</text>
            <text x="450" y="406" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">TEAM MEMBERS</text>

            <text x="50" y="445" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">AMOUNT</text>
            <text x="450" y="445" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">PAYMENT METHOD</text>

            <text x="50" y="484" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">STATUS</text>
            <text x="450" y="484" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">PAYMENT DATE</text>

            <text x="50" y="523" fill="#6B7280" font-family="Arial" font-size="10" font-weight="bold">TRANSACTION ID</text>

            <!-- Separator Lines -->
            <line x1="50" y1="275" x2="800" y2="275" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="314" x2="800" y2="314" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="353" x2="800" y2="353" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="392" x2="800" y2="392" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="431" x2="800" y2="431" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="470" x2="800" y2="470" stroke="#E5E7EB" stroke-width="1"/>
            <line x1="50" y1="509" x2="800" y2="509" stroke="#E5E7EB" stroke-width="1"/>

            <!-- Vertical separator -->
            <line x1="425" y1="230" x2="425" y2="530" stroke="#E5E7EB" stroke-width="1"/>

            <!-- Footer -->
            <text x="50" y="600" fill="#6B7280" font-family="Arial" font-size="12" font-weight="bold">Thank you for participating in UNAS FEST 2025!</text>
            <text x="50" y="620" fill="#6B7280" font-family="Arial" font-size="10">For questions, contact: info@unasfest.com | +62 21 1234 5678</text>

            <!-- Footer line -->
            <line x1="50" y1="650" x2="800" y2="650" stroke="#154C8C" stroke-width="2"/>
        </svg>

        <!-- Dynamic Content Overlays -->
        <div class="dynamic-text invoice-number">{{ $payment->order_id }}</div>
        <div class="dynamic-text order-date">{{ $payment->created_at->format('d M Y') }}</div>

        <div class="dynamic-text participant-name">{{ $payment->user->name ?? 'N/A' }}</div>
        <div class="dynamic-text participant-institution">{{ $payment->user->institution ?? 'N/A' }}</div>

        <div class="dynamic-text participant-email">{{ $payment->user->email ?? 'N/A' }}</div>
        <div class="dynamic-text participant-phone">{{ $payment->user->phone ?? 'N/A' }}</div>

        <div class="dynamic-text competition-name">{{ $payment->competition->name ?? 'N/A' }}</div>
        <div class="dynamic-text competition-category">{{ $payment->competition->category ?? 'N/A' }}</div>

        <div class="dynamic-text team-name">{{ $payment->team_name ?? 'Individual' }}</div>
        <div class="dynamic-text team-members">{{ $payment->team_members ?? 'N/A' }}</div>

        <div class="dynamic-text payment-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
        <div class="dynamic-text payment-method">{{ ucfirst($payment->payment_method) ?? 'N/A' }}</div>

        <div class="dynamic-text payment-status">{{ strtoupper($payment->status) }}</div>
        <div class="dynamic-text payment-date">{{ $payment->updated_at->format('d M Y H:i') }}</div>

        <div class="dynamic-text transaction-id">{{ $payment->transaction_id ?? $payment->order_id }}</div>
    </div>
</body>
</html>