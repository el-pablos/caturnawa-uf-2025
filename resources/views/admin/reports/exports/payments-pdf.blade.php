<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran - UNAS Fest 2025</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }
        
        .header h2 {
            margin: 5px 0 0 0;
            color: #7f8c8d;
            font-size: 16px;
            font-weight: normal;
        }
        
        .info {
            margin-bottom: 20px;
        }
        
        .info p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status.paid {
            background-color: #28a745;
        }
        
        .status.pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status.failed {
            background-color: #dc3545;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .summary-label {
            font-size: 10px;
            color: #7f8c8d;
        }
        
        .amount {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UNAS FEST 2025</h1>
        <h2>Laporan Pembayaran</h2>
    </div>
    
    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y H:i:s') }}</p>
        <p><strong>Total Transaksi:</strong> {{ count($payments) }} pembayaran</p>
    </div>
    
    <div class="summary">
        <h3>Ringkasan Pembayaran</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $payments->where('status', 'paid')->count() }}</div>
                <div class="summary-label">Berhasil</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $payments->where('status', 'pending')->count() }}</div>
                <div class="summary-label">Pending</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $payments->where('status', 'failed')->count() }}</div>
                <div class="summary-label">Gagal</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</div>
                <div class="summary-label">Total Pendapatan</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Peserta</th>
                <th style="width: 20%">Kompetisi</th>
                <th style="width: 15%">Jumlah</th>
                <th style="width: 10%">Status</th>
                <th style="width: 15%">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $payment->registration->user->name ?? '-' }}</td>
                    <td>{{ $payment->registration->competition->name ?? '-' }}</td>
                    <td class="amount">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->status == 'paid')
                            <span class="status paid">Berhasil</span>
                        @elseif($payment->status == 'pending')
                            <span class="status pending">Pending</span>
                        @else
                            <span class="status failed">Gagal</span>
                        @endif
                    </td>
                    <td>{{ $payment->payment_method ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data pembayaran
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem UNAS Fest 2025</p>
        <p>© {{ date('Y') }} Universitas Nasional. All rights reserved.</p>
    </div>
</body>
</html>
