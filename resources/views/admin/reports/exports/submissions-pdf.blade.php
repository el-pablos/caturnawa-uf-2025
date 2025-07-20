<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Submissions - UNAS Fest 2025</title>
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
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status-pending { color: #856404; }
        .status-approved { color: #155724; }
        .status-rejected { color: #721c24; }
        .status-revision { color: #0c5460; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
        }
        .summary-value {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SUBMISSIONS</h1>
        <p>UNAS Fest 2025 - Sistem Manajemen Kompetisi</p>
        <p>Digenerate pada: {{ now()->format('d F Y, H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Data</h3>
        <div class="summary-item">
            <span class="summary-label">Total Submissions:</span>
            <span class="summary-value">{{ $submissions->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Disetujui:</span>
            <span class="summary-value">{{ $submissions->where('status', 'approved')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Menunggu:</span>
            <span class="summary-value">{{ $submissions->where('status', 'pending')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Ditolak:</span>
            <span class="summary-value">{{ $submissions->where('status', 'rejected')->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Revisi:</span>
            <span class="summary-value">{{ $submissions->where('status', 'revision')->count() }}</span>
        </div>
    </div>

    <div class="info-section">
        <h3>Data Submissions</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 15%;">Peserta</th>
                    <th style="width: 15%;">Kompetisi</th>
                    <th style="width: 20%;">Judul Karya</th>
                    <th style="width: 25%;">Deskripsi</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                <tr>
                    <td>{{ $submission->id }}</td>
                    <td>{{ $submission->registration->user->name ?? 'N/A' }}</td>
                    <td>{{ $submission->registration->competition->name ?? 'N/A' }}</td>
                    <td>{{ $submission->title ?? 'N/A' }}</td>
                    <td>{{ Str::limit($submission->description ?? 'N/A', 100) }}</td>
                    <td class="status-{{ $submission->status }}">
                        @switch($submission->status)
                            @case('pending')
                                Menunggu Review
                                @break
                            @case('approved')
                                Disetujui
                                @break
                            @case('rejected')
                                Ditolak
                                @break
                            @case('revision')
                                Perlu Revisi
                                @break
                            @default
                                {{ ucfirst($submission->status) }}
                        @endswitch
                    </td>
                    <td>{{ $submission->created_at ? $submission->created_at->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #666;">Tidak ada data submissions</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem UNAS Fest 2025</p>
        <p>© {{ date('Y') }} UNAS Fest. All rights reserved.</p>
    </div>
</body>
</html>
