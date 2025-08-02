<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Registrasi - Caturnawa UNAS FEST 2025</title>
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
        
        .status.confirmed {
            background-color: #28a745;
        }
        
        .status.pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status.cancelled {
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
            grid-template-columns: repeat(3, 1fr);
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Caturnawa UNAS FEST 2025</h1>
        <h2>Laporan Registrasi Peserta</h2>
    </div>
    
    <div class="info">
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y H:i:s') }}</p>
        <p><strong>Total Data:</strong> {{ count($registrations) }} registrasi</p>
    </div>
    
    <div class="summary">
        <h3>Ringkasan Registrasi</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $registrations->where('status', 'confirmed')->count() }}</div>
                <div class="summary-label">Terkonfirmasi</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $registrations->where('status', 'pending')->count() }}</div>
                <div class="summary-label">Pending</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $registrations->where('status', 'cancelled')->count() }}</div>
                <div class="summary-label">Dibatalkan</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 20%">Nama</th>
                <th style="width: 15%">Tim/Peserta</th>
                <th style="width: 20%">Universitas</th>
                <th style="width: 20%">Sekolah</th>
                <th style="width: 15%">Kompetisi</th>
                <th style="width: 15%">Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $registration)
                @php
                    $isUniversity = false;
                    $isSchool = false;
                    $institution = $registration->institution ?? $registration->user->institution ?? '-';

                    // Determine if it's university or school based on institution name
                    $universityKeywords = ['universitas', 'institut', 'politeknik', 'akademi', 'sekolah tinggi', 'university', 'college'];
                    $schoolKeywords = ['sma', 'smk', 'man', 'smp', 'mts', 'ma ', 'sekolah menengah'];

                    foreach ($universityKeywords as $keyword) {
                        if (stripos($institution, $keyword) !== false) {
                            $isUniversity = true;
                            break;
                        }
                    }

                    if (!$isUniversity) {
                        foreach ($schoolKeywords as $keyword) {
                            if (stripos($institution, $keyword) !== false) {
                                $isSchool = true;
                                break;
                            }
                        }
                    }

                    $teamName = $registration->team_name ?? 'Individu';
                    $paymentStatus = 'Belum Bayar';

                    if ($registration->payment) {
                        if (in_array($registration->payment->transaction_status, ['settlement', 'capture'])) {
                            $paymentStatus = 'Lunas';
                        } elseif ($registration->payment->transaction_status == 'pending') {
                            $paymentStatus = 'Pending';
                        } else {
                            $paymentStatus = 'Gagal';
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $registration->user->name ?? '-' }}</td>
                    <td>{{ $teamName }}</td>
                    <td>{{ $isUniversity ? $institution : '-' }}</td>
                    <td>{{ $isSchool ? $institution : '-' }}</td>
                    <td>{{ $registration->competition->name ?? '-' }}</td>
                    <td>
                        @if($paymentStatus == 'Lunas')
                            <span class="status confirmed">{{ $paymentStatus }}</span>
                        @elseif($paymentStatus == 'Pending')
                            <span class="status pending">{{ $paymentStatus }}</span>
                        @else
                            <span class="status cancelled">{{ $paymentStatus }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data registrasi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Caturnawa UNAS FEST 2025</p>
        <p>© {{ date('Y') }} Universitas Nasional. All rights reserved.</p>
    </div>
</body>
</html>
