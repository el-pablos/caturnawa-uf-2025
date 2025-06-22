@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Super Admin')

@section('sidebar-menu')
    <a class="nav-link active" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer me-2"></i>Dashboard
    </a>
    
    @can('users.view')
    <a class="nav-link" href="{{ route('admin.users.index') }}">
        <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
    </a>
    @endcan
    
    <a class="nav-link" href="#">
        <i class="bi bi-person-vcard-fill me-2"></i>Kelola Role
    </a>
    
    <a class="nav-link" href="{{ route('admin.competitions.index') }}">
        <i class="bi bi-trophy-fill me-2"></i>Kelola Kompetisi
    </a>
    
    <a class="nav-link" href="#">
        <i class="bi bi-award-fill me-2"></i>Penilaian
    </a>
    
    <a class="nav-link" href="#">
        <i class="bi bi-file-earmark-text-fill me-2"></i>Karya Peserta
    </a>
    
    <a class="nav-link" href="#">
        <i class="bi bi-wallet-fill me-2"></i>Pembayaran
    </a>
    
    <a class="nav-link" href="#">
        <i class="bi bi-flag-fill me-2"></i>Laporan
    </a>
    
    @can('settings.view')
    <a class="nav-link" href="#">
        <i class="bi bi-gear-fill me-2"></i>Pengaturan
    </a>
    @endcan
@endsection

@section('header-actions')
    <button class="btn btn-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Cetak Laporan
    </button>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stats-number">{{ $stats['total_users'] }}</div>
                    <div class="fw-semibold">Total Pengguna</div>
                </div>
                <div class="fs-1 opacity-50">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['total_competitions'] }}</div>
                        <div class="fw-semibold">Total Kompetisi</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                        <div class="fw-semibold">Total Pendapatan</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number">{{ $stats['total_registrations'] }}</div>
                        <div class="fw-semibold">Total Penilaian</div>
                    </div>
                    <div class="fs-1 opacity-50">
                        <i class="bi bi-award-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Registration Trend Chart -->
    <div class="col-lg-8 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Tren Pendaftaran & Pendapatan
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                        Tahun 2025
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Tahun 2025</a></li>
                        <li><a class="dropdown-item" href="#">Tahun 2024</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body" id="chart-container">
                <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Distribution Chart -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Distribusi Pengguna
                </h5>
            </div>
            <div class="card-body" id="user-distribution-container">
                <div class="d-flex justify-content-center align-items-center" style="height: 250px;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Section -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-person-fill-add me-2"></i>Pengguna Terbaru
                </h6>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0" id="recent-users-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Active Competitions -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-trophy-fill me-2"></i>Kompetisi Aktif
                </h6>
                <a href="{{ route('admin.competitions.index') }}" class="btn btn-sm btn-success">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0" id="recent-competitions-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Payments -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="bi bi-wallet-fill me-2"></i>Pembayaran Terbaru
                </h6>
                <a href="#" class="btn btn-sm btn-warning">
                    Laporan Keuangan
                </a>
            </div>
            <div class="card-body p-0" id="recent-payments-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load data with staggered timing to prevent overload
    setTimeout(() => loadChartData(), 500);
    setTimeout(() => loadUserDistribution(), 1000);
    setTimeout(() => loadRecentData(), 1500);
});

// Load chart data via AJAX
function loadChartData() {
    fetch('/admin/dashboard/chart-data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const chartContainer = document.getElementById('chart-container');
                chartContainer.innerHTML = '<canvas id="trendChart" height="100"></canvas>';

                const trendCtx = document.getElementById('trendChart').getContext('2d');
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: data.data.months,
                        datasets: [{
                            label: 'Pendaftaran',
                            data: data.data.registrations,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y'
                        }, {
                            label: 'Pendapatan (Rp)',
                            data: data.data.revenues,
                            borderColor: '#f093fb',
                            backgroundColor: 'rgba(240, 147, 251, 0.1)',
                            fill: true,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Bulan'
                                }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Jumlah Pendaftaran'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Pendapatan (Rp)'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            document.getElementById('chart-container').innerHTML =
                '<div class="alert alert-danger">Gagal memuat data grafik</div>';
        });
}

// Load user distribution via AJAX
function loadUserDistribution() {
    fetch('/admin/dashboard/user-distribution')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('user-distribution-container');
                container.innerHTML = `
                    <canvas id="userDistributionChart"></canvas>
                    <div class="mt-3" id="user-legend"></div>
                `;

                const userDistCtx = document.getElementById('userDistributionChart').getContext('2d');
                new Chart(userDistCtx, {
                    type: 'doughnut',
                    data: {
                        labels: data.data.labels,
                        datasets: [{
                            data: data.data.data,
                            backgroundColor: data.data.colors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Create legend
                let legendHtml = '';
                data.data.labels.forEach((label, index) => {
                    legendHtml += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle me-2"
                                     style="width: 12px; height: 12px; background-color: ${data.data.colors[index]}"></div>
                                <span>${label}</span>
                            </div>
                            <span class="fw-semibold">${data.data.data[index]}</span>
                        </div>
                    `;
                });
                document.getElementById('user-legend').innerHTML = legendHtml;
            }
        })
        .catch(error => {
            console.error('Error loading user distribution:', error);
            document.getElementById('user-distribution-container').innerHTML =
                '<div class="alert alert-danger">Gagal memuat distribusi pengguna</div>';
        });
}

// Load recent data via AJAX
function loadRecentData() {
    fetch('/admin/dashboard/recent-data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadRecentUsers(data.data.recent_users);
                loadRecentCompetitions(data.data.recent_competitions);
                loadRecentPayments(data.data.recent_payments);
            }
        })
        .catch(error => {
            console.error('Error loading recent data:', error);
        });
}

function loadRecentUsers(users) {
    const container = document.getElementById('recent-users-container');
    if (users.length === 0) {
        container.innerHTML = `
            <div class="list-group-item text-center text-muted py-4">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                Belum ada pengguna baru
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush">';
    users.forEach(user => {
        html += `
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <img src="/storage/avatars/default.png" width="40" height="40"
                         class="rounded-circle me-3" alt="Avatar">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${user.name}</div>
                        <small class="text-muted">${user.roles[0]?.name || 'No Role'}</small>
                    </div>
                    <small class="text-muted">${formatDate(user.created_at)}</small>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function loadRecentCompetitions(competitions) {
    const container = document.getElementById('recent-competitions-container');
    if (competitions.length === 0) {
        container.innerHTML = `
            <div class="list-group-item text-center text-muted py-4">
                <i class="bi bi-trophy fs-1 d-block mb-2 opacity-50"></i>
                Belum ada kompetisi aktif
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush">';
    competitions.forEach(competition => {
        const statusBadge = competition.is_active
            ? '<span class="badge bg-success">Aktif</span>'
            : '<span class="badge bg-secondary">Tidak Aktif</span>';

        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${competition.name}</div>
                        <small class="text-muted">${competition.category}</small>
                        <div class="mt-1">${statusBadge}</div>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function loadRecentPayments(payments) {
    const container = document.getElementById('recent-payments-container');
    if (payments.length === 0) {
        container.innerHTML = `
            <div class="list-group-item text-center text-muted py-4">
                <i class="bi bi-wallet fs-1 d-block mb-2 opacity-50"></i>
                Belum ada pembayaran
            </div>
        `;
        return;
    }

    let html = '<div class="list-group list-group-flush">';
    payments.forEach(payment => {
        const statusClass = getPaymentStatusClass(payment.transaction_status);
        const statusLabel = getPaymentStatusLabel(payment.transaction_status);

        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${payment.registration.user.name}</div>
                        <small class="text-muted">${payment.registration.competition.name}</small>
                        <div class="mt-1">
                            <span class="badge bg-${statusClass}">${statusLabel}</span>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-semibold">Rp ${formatNumber(payment.gross_amount)}</div>
                        <small class="text-muted">${formatDate(payment.created_at)}</small>
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Helper functions
function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 1) return 'Kemarin';
    if (diffDays < 7) return `${diffDays} hari lalu`;
    return date.toLocaleDateString('id-ID');
}

function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function getPaymentStatusClass(status) {
    const statusMap = {
        'pending': 'warning',
        'settlement': 'success',
        'capture': 'success',
        'deny': 'danger',
        'cancel': 'secondary',
        'expire': 'danger',
        'failure': 'danger'
    };
    return statusMap[status] || 'secondary';
}

function getPaymentStatusLabel(status) {
    const statusMap = {
        'pending': 'Menunggu',
        'settlement': 'Berhasil',
        'capture': 'Berhasil',
        'deny': 'Ditolak',
        'cancel': 'Dibatalkan',
        'expire': 'Kadaluarsa',
        'failure': 'Gagal'
    };
    return statusMap[status] || 'Unknown';
}
</script>
@endpush
