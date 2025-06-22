@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard Super Admin')



@section('header-actions')
    <button class="btn btn-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i>Cetak Laporan
    </button>
@endsection

@section('content')
<!-- Statistics Cards - Modern White Design -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-people-fill text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Pengguna</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($stats['total_users']) }}</div>
                        <div class="text-success small">
                            <i class="bi bi-arrow-up"></i> +12% dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-trophy-fill text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Kompetisi</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($stats['total_competitions']) }}</div>
                        <div class="text-info small">
                            <i class="bi bi-activity"></i> {{ $stats['active_competitions'] ?? 0 }} aktif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-success btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-person-check-fill text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Registrasi</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($stats['total_registrations']) }}</div>
                        <div class="text-warning small">
                            <i class="bi bi-clock"></i> {{ $stats['pending_registrations'] ?? 0 }} pending
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-info btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="bi bi-wallet-fill text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <div class="text-muted small">Total Pendapatan</div>
                        <div class="fs-2 fw-bold text-dark">{{ number_format($stats['total_revenue'] ?? 0) }}</div>
                        <div class="text-success small">
                            <i class="bi bi-graph-up"></i> +8% dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-warning btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Registration Trend Chart -->
    <div class="col-lg-8 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-graph-up me-2 text-primary"></i>Tren Pendaftaran & Pendapatan 2025
                    </h6>
                    <div class="badge bg-primary bg-opacity-10 text-primary">
                        UNAS Fest 2025
                    </div>
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
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 text-dark fw-semibold">
                    <i class="bi bi-pie-chart me-2 text-success"></i>Distribusi Pengguna
                </h6>
            </div>
            <div class="card-body" id="user-distribution-container">
                <div class="d-flex justify-content-center align-items-center" style="height: 250px;">
                    <div class="spinner-border text-success" role="status">
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
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-person-fill-add me-2 text-info"></i>Pengguna Terbaru
                    </h6>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0" id="recent-users-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Competitions -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-trophy-fill me-2 text-warning"></i>Kompetisi Aktif
                    </h6>
                    <a href="{{ route('admin.competitions.index') }}" class="btn btn-outline-warning btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0" id="recent-competitions-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-warning" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="col-lg-4 mb-3">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-wallet-fill me-2 text-danger"></i>Pembayaran Terbaru
                    </h6>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-danger btn-sm">
                        Laporan Keuangan
                    </a>
                </div>
            </div>
            <div class="card-body p-0" id="recent-payments-container">
                <div class="d-flex justify-content-center align-items-center py-4">
                    <div class="spinner-border spinner-border-sm text-danger" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .card {
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        padding: 1.25rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        border-radius: 0 0 12px 12px !important;
        padding: 1rem 1.5rem;
    }

    /* Statistics Cards Enhancements */
    .fs-2 {
        font-size: 2.5rem !important;
        line-height: 1.2;
    }

    .rounded-circle {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Chart Container Improvements */
    #chart-container,
    #user-distribution-container,
    #recent-users-container,
    #recent-competitions-container,
    #recent-payments-container {
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chart-container {
        position: relative;
        height: 300px;
        min-height: 300px;
    }

    /* Loading States */
    .spinner-border {
        width: 2rem;
        height: 2rem;
    }

    .spinner-border-sm {
        width: 1.5rem;
        height: 1.5rem;
    }

    /* List Group Enhancements */
    .list-group-item {
        border: none;
        padding: 1rem 1.5rem;
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }

    /* Badge Enhancements */
    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
    }

    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }

    /* Button Enhancements */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    /* Prevent auto-scroll during initial load */
    body.loading {
        overflow: hidden;
    }

    /* Background */
    body {
        background-color: #f8f9fa;
    }

    /* Shadow utilities */
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }

    /* Text utilities */
    .fw-semibold {
        font-weight: 600 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .fs-2 {
            font-size: 2rem !important;
        }

        .card-body {
            padding: 1rem;
        }

        .card-header {
            padding: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading class to prevent auto-scroll during loading
    document.body.classList.add('loading');

    // Load data with proper sequencing to prevent layout shifts
    loadChartData()
        .then(() => loadUserDistribution())
        .then(() => loadRecentData())
        .catch(error => {
            console.error('Error loading dashboard data:', error);
        })
        .finally(() => {
            // Remove loading class after all content is loaded
            setTimeout(() => {
                document.body.classList.remove('loading');
            }, 500);
        });
});

// Load chart data via AJAX
function loadChartData() {
    return fetch('/admin/dashboard/chart-data')
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
            throw error; // Re-throw to handle in promise chain
        });
}

// Load user distribution via AJAX
function loadUserDistribution() {
    return fetch('/admin/dashboard/user-distribution')
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
            throw error; // Re-throw to handle in promise chain
        });
}

// Load recent data via AJAX
function loadRecentData() {
    return fetch('/admin/dashboard/recent-data')
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
            throw error; // Re-throw to handle in promise chain
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
