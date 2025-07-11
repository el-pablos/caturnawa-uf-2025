<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $payment->order_id }}</title>
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
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: normal;
            opacity: 0.9;
        }
        
        .invoice-details {
            padding: 30px;
        }
        
        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .invoice-meta div {
            flex: 1;
            min-width: 250px;
        }
        
        .invoice-meta h3 {
            color: #007bff;
            margin: 0 0 15px 0;
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
            width: 140px;
            color: #555;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .payment-summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #007bff;
        }
        
        .payment-summary h3 {
            color: #007bff;
            margin: 0 0 20px 0;
            font-size: 18px;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }
        
        .amount-row.total {
            border-top: 2px solid #007bff;
            margin-top: 15px;
            padding-top: 15px;
            font-weight: bold;
            font-size: 18px;
            color: #007bff;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .team-details {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .team-details h4 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 16px;
        }
        
        .team-member {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .team-member:last-child {
            border-bottom: none;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #666;
        }
        
        .footer p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .qr-section {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0;
            font-size: 14px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .print-button {
                display: none;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-meta {
                flex-direction: column;
            }
            
            .amount-row {
                flex-direction: column;
                text-align: left;
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
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>UNAS FEST 2025</h1>
            <h2>Invoice Pembayaran Kompetisi</h2>
        </div>
        
        <!-- Invoice Details -->
        <div class="invoice-details">
            <!-- Invoice Meta Information -->
            <div class="invoice-meta">
                <div>
                    <h3>Informasi Invoice</h3>
                    <div class="info-row">
                        <span class="info-label">Nomor Invoice:</span>
                        <span class="info-value">{{ $payment->order_id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value">{{ $payment->created_at->format('d F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge {{ $payment->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                                {{ $payment->status === 'paid' ? 'LUNAS' : 'PENDING' }}
                            </span>
                        </span>
                    </div>
                    @if($payment->paid_at)
                    <div class="info-row">
                        <span class="info-label">Dibayar pada:</span>
                        <span class="info-value">{{ $payment->paid_at->format('d F Y H:i') }}</span>
                    </div>
                    @endif
                </div>
                
                <div>
                    <h3>Informasi Peserta</h3>
                    <div class="info-row">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">{{ $payment->registration->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $payment->registration->user->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Institusi:</span>
                        <span class="info-value">{{ $payment->registration->user->institution ?? 'Tidak diisi' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kompetisi:</span>
                        <span class="info-value">{{ $payment->registration->competition->name }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Team Details if available -->
            @if($payment->registration->team_name)
            <div class="team-details">
                <h4>Detail Tim</h4>
                <div class="info-row">
                    <span class="info-label">Nama Tim:</span>
                    <span class="info-value">{{ $payment->registration->team_name }}</span>
                </div>
                
                @if($payment->registration->teamMembers && $payment->registration->teamMembers->count() > 0)
                <h5 style="margin: 15px 0 10px 0; color: #666;">Anggota Tim:</h5>
                @foreach($payment->registration->teamMembers as $index => $member)
                <div class="team-member">
                    <strong>{{ $index + 1 }}. {{ $member->name }}</strong>
                    @if($member->email)
                    <br><small>{{ $member->email }}</small>
                    @endif
                    @if($member->phone)
                    <br><small>{{ $member->phone }}</small>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
            @endif
            
            <!-- Payment Summary -->
            <div class="payment-summary">
                <h3>Ringkasan Pembayaran</h3>
                
                <div class="amount-row">
                    <span>Biaya Pendaftaran {{ $payment->registration->competition->name }}:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="amount-row">
                    <span>Biaya Admin:</span>
                    <span>Rp 0</span>
                </div>
                
                <div class="amount-row total">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
                
                @if($payment->payment_method)
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #dee2e6;">
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran:</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </div>
                    @if($payment->transaction_id)
                    <div class="info-row">
                        <span class="info-label">ID Transaksi:</span>
                        <span class="info-value">{{ $payment->transaction_id }}</span>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- QR Code if available -->
            @if($payment->registration->qr_code)
            <div class="qr-section">
                <h4 style="color: #007bff; margin-bottom: 15px;">E-Ticket QR Code</h4>
                <img src="data:image/png;base64,{{ base64_encode($payment->registration->qr_code) }}" 
                     alt="QR Code" style="max-width: 200px; height: auto;">
                <p style="margin: 10px 0 0 0; font-size: 12px; color: #666;">
                    Tunjukkan QR Code ini saat check-in kompetisi
                </p>
            </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>UNAS FEST 2025 - Dies Natalis ke-76 Universitas Nasional</strong></p>
            <p>Invoice ini adalah bukti sah pembayaran pendaftaran kompetisi</p>
            <p>Dicetak pada: {{ $generated_at->format('d F Y H:i:s') }}</p>
            <p>Untuk informasi lebih lanjut, hubungi panitia kompetisi UNAS Fest 2025</p>
        </div>
    </div>
    
    <!-- Print Button -->
    <div style="text-align: center; margin: 20px 0;">
        <button class="print-button" onclick="window.print()">
            <i class="bi bi-printer"></i> Cetak Invoice
        </button>
    </div>
    
    <script>
        // Auto print functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Add print styles
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    .print-button { display: none !important; }
                    body { margin: 0; padding: 0; }
                    .invoice-container { box-shadow: none; }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>
