@extends('layouts.admin')

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
                <div style="height: 400px;">
                    <canvas id="revenueChart"></canvas>
                </div>
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
                <div style="height: 300px;">
                    <canvas id="competitionChart"></canvas>
                </div>
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
                        <th>Kompetisi</th>
                        <th>Kategori</th>
                        <th>Total Peserta</th>
                        <th>Terkonfirmasi</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        @if(auth()->user()->hasRole('Super Admin'))
                        <th width="100">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($topCompetitions ?? [] as $competition)
                        <tr id="competition-row-{{ $competition->id ?? 0 }}">
                            <td>{{ $competition->name ?? '-' }}</td>
                            <td>{{ $competition->category ?? '-' }}</td>
                            <td>{{ $competition->registrations_count ?? 0 }}</td>
                            <td>{{ $competition->confirmed_registrations_count ?? 0 }}</td>
                            <td>
                                @if($competition->is_active ?? false)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $competition->created_at ? $competition->created_at->format('d/m/Y') : '-' }}</td>
                            @if(auth()->user()->hasRole('Super Admin'))
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.competitions.show', $competition->id ?? 0) }}"
                                       class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                            onclick="deleteCompetitionFromReport({{ $competition->id ?? 0 }})"
                                            title="Hapus dari Laporan">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('Super Admin') ? '7' : '6' }}" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted">Tidak ada data kompetisi</p>
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
        columnDefs: [
            { orderable: false, targets: [] }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
            emptyTable: "Tidak ada data laporan tersedia",
            zeroRecords: "Tidak ada data yang cocok dengan pencarian"
        }
    });
    
    // Load Charts with Real Data
    loadRegistrationChart();
    loadRevenueChart();
    
    // Load Competition Distribution Chart
    loadCompetitionDistribution();
});

// Load competition distribution data
function loadCompetitionDistribution() {
    fetch('/admin/reports/competition-distribution')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                createCompetitionChart(data.data);
            } else {
                document.getElementById('competitionChart').parentElement.innerHTML =
                    '<div class="alert alert-info text-center">Belum ada data kompetisi</div>';
            }
        })
        .catch(error => {
            console.error('Error loading competition distribution:', error);
            document.getElementById('competitionChart').parentElement.innerHTML =
                '<div class="alert alert-danger text-center">Gagal memuat data kompetisi</div>';
        });
}

// Create competition distribution chart
function createCompetitionChart(data) {
    const ctx = document.getElementById('competitionChart').getContext('2d');

    const labels = data.map(item => item.category);
    const values = data.map(item => item.count);
    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, data.length),
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

// Load registration chart with real data
function loadRegistrationChart() {
    fetch('/admin/reports/registration-trend')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createRegistrationChart(data.data);
            } else {
                document.getElementById('registrationChart').parentElement.innerHTML =
                    '<div class="alert alert-info text-center">Belum ada data registrasi</div>';
            }
        })
        .catch(error => {
            console.error('Error loading registration trend:', error);
            document.getElementById('registrationChart').parentElement.innerHTML =
                '<div class="alert alert-danger text-center">Gagal memuat data registrasi</div>';
        });
}

// Create registration chart
function createRegistrationChart(data) {
    const ctx = document.getElementById('registrationChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Registrasi',
                data: data.values,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Load revenue chart with real data
function loadRevenueChart() {
    fetch('/admin/reports/revenue-trend')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createRevenueChart(data.data);
            } else {
                document.getElementById('revenueChart').parentElement.innerHTML =
                    '<div class="alert alert-info text-center">Belum ada data pendapatan</div>';
            }
        })
        .catch(error => {
            console.error('Error loading revenue trend:', error);
            document.getElementById('revenueChart').parentElement.innerHTML =
                '<div class="alert alert-danger text-center">Gagal memuat data pendapatan</div>';
        });
}

// Create revenue chart
function createRevenueChart(data) {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Pendapatan',
                data: data.values,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
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
}

function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('format', format);

    // Export all reports (general export)
    const baseUrl = '{{ url("admin/reports/export") }}';
    window.open(`${baseUrl}/registrations?${params.toString()}`, '_blank');
}

function deleteCompetitionFromReport(competitionId) {
    if (confirm('Apakah Anda yakin ingin menghapus kompetisi ini dari laporan?\n\nPerhatian: Ini akan menghapus kompetisi secara permanen beserta semua data terkait (registrasi, pembayaran, submission).')) {
        fetch(`/admin/competitions/${competitionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                const row = document.getElementById(`competition-row-${competitionId}`);
                if (row) {
                    row.remove();
                }

                // Show success message
                showAlert('success', 'Kompetisi berhasil dihapus dari laporan.');

                // Refresh page after 2 seconds to update statistics
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan saat menghapus kompetisi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus kompetisi.');
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Insert alert at the top of the page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
@endpush
