<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $payment->order_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            margin: 0;
            font-weight: normal;
        }
        
        .receipt-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .receipt-info h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            border-bottom: 1px dotted #ddd;
            padding-bottom: 5px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .competition-details {
            margin-bottom: 20px;
        }
        
        .competition-details h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .payment-details {
            margin-bottom: 20px;
        }
        
        .payment-details h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .amount-section {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .amount-section h3 {
            color: #28a745;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-section img {
            max-width: 150px;
            height: auto;
        }
        
        .team-members {
            margin-top: 15px;
        }
        
        .team-members h4 {
            margin: 0 0 10px 0;
            color: #555;
            font-size: 14px;
        }
        
        .member-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .member-list li {
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>STRUK PEMBAYARAN PENDAFTARAN KOMPETISI</h2>
    </div>

    <div class="receipt-info">
        <h3>📄 Informasi Struk</h3>
        <div class="info-row">
            <span class="info-label">Order ID:</span>
            <span class="info-value">{{ $payment->order_id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Pembayaran:</span>
            <span class="info-value">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i:s') : '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status-badge status-success">{{ $payment->status_label }}</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Metode Pembayaran:</span>
            <span class="info-value">{{ $payment->payment_method }}</span>
        </div>
    </div>

    <div class="competition-details">
        <h3>🏆 Detail Kompetisi</h3>
        <div class="info-row">
            <span class="info-label">Nama Kompetisi:</span>
            <span class="info-value">{{ $registration->competition->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kategori:</span>
            <span class="info-value">{{ $registration->competition->category }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nomor Registrasi:</span>
            <span class="info-value">{{ $registration->registration_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama Peserta:</span>
            <span class="info-value">{{ $registration->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $registration->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">No. Telepon:</span>
            <span class="info-value">{{ $registration->phone ?: $registration->user->phone }}</span>
        </div>
        
        @if($registration->team_members && count($registration->team_members) > 0)
        <div class="team-members">
            <h4>👥 Anggota Tim:</h4>
            <ul class="member-list">
                @foreach($registration->team_members as $member)
                <li>{{ $member['name'] }} - {{ $member['email'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="amount-section">
        <h3>💰 Total Pembayaran</h3>
        <div class="amount">Rp {{ number_format($registration->amount, 0, ',', '.') }}</div>
    </div>

    @if($registration->qr_code)
    <div class="qr-section">
        <h3>🎫 E-Ticket QR Code</h3>
        <img src="data:image/png;base64,{{ base64_encode($registration->qr_code) }}" alt="QR Code">
        <p style="margin: 10px 0 0 0; font-size: 10px; color: #666;">
            Tunjukkan QR Code ini saat check-in kompetisi
        </p>
    </div>
    @endif

    <div class="footer">
        <p><strong>{{ config('app.name') }}</strong></p>
        <p>Struk ini adalah bukti sah pembayaran pendaftaran kompetisi</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Untuk informasi lebih lanjut, hubungi panitia kompetisi</p>
    </div>
</body>
</html>
