<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registrations Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
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
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Registrations Report</h1>
    <p>Generated: {{ now()->format('d F Y H:i:s') }}</p>
    <p>Total Registrations: {{ $registrations->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reg. Number</th>
                <th>Team Name</th>
                <th>User</th>
                <th>Email</th>
                <th>Competition</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Registered At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $registration)
            <tr>
                <td>{{ $registration->id }}</td>
                <td>{{ $registration->registration_number ?? '-' }}</td>
                <td>{{ $registration->team_name ?? '-' }}</td>
                <td>{{ $registration->user->name ?? '-' }}</td>
                <td>{{ $registration->user->email ?? '-' }}</td>
                <td>{{ $registration->competition->name ?? '-' }}</td>
                <td>{{ ucfirst($registration->status) }}</td>
                <td>Rp {{ number_format($registration->amount ?? 0, 0, ',', '.') }}</td>
                <td>{{ $registration->created_at ? $registration->created_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Caturnawa UNAS FEST 2025 - Registration Report</p>
    </div>
</body>
</html>

