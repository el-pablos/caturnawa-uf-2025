<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $registration->competition->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        
        .ticket-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border: 2px solid #007bff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .ticket-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 5px,
                white 5px,
                white 10px
            );
        }
        
        .ticket-header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .ticket-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: normal;
            opacity: 0.9;
        }
        
        .ticket-body {
            padding: 30px 25px;
        }
        
        .event-info {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #007bff;
        }
        
        .event-info h3 {
            color: #007bff;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .event-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .participant-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .participant-info > div {
            flex: 1;
            min-width: 250px;
            margin-bottom: 15px;
        }
        
        .info-group h4 {
            color: #007bff;
            margin: 0 0 10px 0;
            font-size: 16px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .team-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .team-section h4 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .team-member {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .team-member:last-child {
            border-bottom: none;
        }
        
        .member-number {
            background: #007bff;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-right: 15px;
        }
        
        .qr-section {
            text-align: center;
            margin: 25px 0;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #007bff;
        }
        
        .qr-section h4 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        
        .qr-code {
            margin: 15px 0;
        }
        
        .qr-instructions {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
            font-style: italic;
        }
        
        .ticket-footer {
            background: #f8f9fa;
            padding: 20px 25px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #666;
        }
        
        .ticket-footer p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .important-note {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        
        .important-note h5 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .ticket-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
        
        @media (max-width: 768px) {
            .participant-info {
                flex-direction: column;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .info-label {
                width: auto;
                margin-bottom: 2px;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Ticket Header -->
        <div class="ticket-header">
            <h1>E-TICKET</h1>
            <h2>UNAS FEST 2025 - Dies Natalis ke-76</h2>
        </div>
        
        <!-- Ticket Body -->
        <div class="ticket-body">
            <!-- Event Information -->
            <div class="event-info">
                <h3>{{ $registration->competition->name }}</h3>
                <p><strong>Status:</strong> <span class="status-badge">TERKONFIRMASI</span></p>
                <p><strong>ID Registrasi:</strong> {{ $registration->id }}</p>
                @if($registration->confirmed_at)
                <p><strong>Dikonfirmasi pada:</strong> {{ $registration->confirmed_at->format('d F Y H:i') }}</p>
                @endif
            </div>
            
            <!-- Participant Information -->
            <div class="participant-info">
                <div class="info-group">
                    <h4>Informasi Peserta</h4>
                    <div class="info-row">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">{{ $registration->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $registration->user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Institusi:</span>
                        <span class="info-value">{{ $registration->user->institution ?? 'Tidak diisi' }}</span>
                    </div>
                    @if($registration->participant_category)
                    <div class="info-row">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value">{{ ucfirst($registration->participant_category) }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="info-group">
                    <h4>Detail Kompetisi</h4>
                    <div class="info-row">
                        <span class="info-label">Kompetisi:</span>
                        <span class="info-value">{{ $registration->competition->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value">{{ $registration->competition->category ?? 'Umum' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tipe:</span>
                        <span class="info-value">{{ $registration->competition->type === 'team' ? 'Tim' : 'Individu' }}</span>
                    </div>
                    @if($registration->competition->max_participants)
                    <div class="info-row">
                        <span class="info-label">Max Peserta:</span>
                        <span class="info-value">{{ $registration->competition->max_participants }} orang</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Team Information if applicable -->
            @if($registration->team_name && $registration->teamMembers && $registration->teamMembers->count() > 0)
            <div class="team-section">
                <h4>Informasi Tim: {{ $registration->team_name }}</h4>
                @foreach($registration->teamMembers as $index => $member)
                <div class="team-member">
                    <div class="member-number">{{ $index + 1 }}</div>
                    <div>
                        <strong>{{ $member->name }}</strong>
                        @if($member->email)
                        <br><small>{{ $member->email }}</small>
                        @endif
                        @if($member->phone)
                        <br><small>{{ $member->phone }}</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            
            <!-- QR Code Section -->
            @if($registration->qr_code)
            <div class="qr-section">
                <h4>QR Code Check-in</h4>
                <div class="qr-code">
                    <img src="data:image/png;base64,{{ base64_encode($registration->qr_code) }}" 
                         alt="QR Code" style="max-width: 200px; height: auto;">
                </div>
                <div class="qr-instructions">
                    Tunjukkan QR Code ini kepada panitia saat check-in kompetisi
                </div>
            </div>
            @endif
            
            <!-- Important Notes -->
            <div class="important-note">
                <h5>Catatan Penting:</h5>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>E-ticket ini wajib dibawa saat mengikuti kompetisi</li>
                    <li>Tunjukkan QR Code kepada panitia untuk proses check-in</li>
                    <li>Pastikan data yang tertera sudah benar</li>
                    <li>Hubungi panitia jika ada kesalahan data</li>
                </ul>
            </div>
        </div>
        
        <!-- Ticket Footer -->
        <div class="ticket-footer">
            <p><strong>UNAS FEST 2025 - Dies Natalis ke-76 Universitas Nasional</strong></p>
            <p>E-ticket ini adalah bukti sah partisipasi dalam kompetisi</p>
            <p>Dicetak pada: {{ $generated_at->format('d F Y H:i:s') }}</p>
            <p>Untuk informasi lebih lanjut, hubungi panitia UNAS Fest 2025</p>
        </div>
    </div>
</body>
</html>
