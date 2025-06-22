@extends('layouts.app')

@section('title', 'Laporan')

@section('page-title', 'Laporan')



@section('header-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" onclick="exportReport('excel')">
            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
        </button>
        <button type="button" class="btn btn-danger" onclick="exportReport('pdf')">
            <i class="bi bi-file-earmark-pdf me-2"></i>Export PDF
        </button>
    </div>
@endsection

@section('content')
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filter Laporan
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="competition_id" class="form-label">Kompetisi</label>
                    <select class="form-select" id="competition_id" name="competition_id">
                        <option value="">Semua Kompetisi</option>
                        @foreach($competitions ?? [] as $competition)
                            <option value="{{ $competition->id }}" 
                                {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                                {{ $competition->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="date_from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_registrations'] ?? 0 }}</h4>
                        <p class="mb-0">Total Registrasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['paid_registrations'] ?? 0 }}</h4>
                        <p class="mb-0">Registrasi Terbayar</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-wallet fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['active_competitions'] ?? 0 }}</h4>
                        <p class="mb-0">Kompetisi Aktif</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-trophy fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Tren Pendaftaran & Pendapatan
                </h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Distribusi Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <canvas id="competitionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-table me-2"></i>Detail Laporan
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="reportsTable">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kompetisi</th>
                        <th>Peserta</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Metode Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports ?? [] as $report)
                        <tr>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $report->competition->name ?? '-' }}</td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td>{{ $report->user->email ?? '-' }}</td>
                            <td>
                                @if($report->status == 'paid')
                                    <span class="badge bg-success">Terbayar</span>
                                @elseif($report->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($report->amount ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $report->payment_method ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Tidak ada data laporan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    $('#reportsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
        }
    });
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Pendapatan',
                data: [12000000, 19000000, 15000000, 25000000, 22000000, 30000000],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    
    // Competition Chart
    const competitionCtx = document.getElementById('competitionChart').getContext('2d');
    new Chart(competitionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Programming', 'Design', 'Business', 'Essay'],
            datasets: [{
                data: [30, 25, 20, 25],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function exportReport(format) {
    const params = new URLSearchParams(window.location.search);

    window.open(`{{ route('admin.reports.export', ['type' => '']) }}`.replace('', format) + `?${params.toString()}`, '_blank');
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush
