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
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
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
            <div class="card-body">
                <canvas id="userDistributionChart"></canvas>
                <div class="mt-3">
                    @foreach($userDistribution['labels'] as $index => $label)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-2" 
                                 style="width: 12px; height: 12px; background-color: {{ $userDistribution['colors'][$index] ?? '#6c757d' }}"></div>
                            <span>{{ $label }}</span>
                        </div>
                        <span class="fw-semibold">{{ $userDistribution['data'][$index] }}</span>
                    </div>
                    @endforeach
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
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentData['recent_users'] as $user)
                    <div class="list-group-item">
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->avatar_url }}" width="40" height="40" 
                                 class="rounded-circle me-3" alt="Avatar">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->getRoleNames()->first() }}</small>
                            </div>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada pengguna baru
                    </div>
                    @endforelse
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
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentData['recent_competitions'] as $competition)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $competition->name }}</div>
                                <small class="text-muted">{{ ucfirst($competition->category) }}</small>
                                <div class="mt-1">
                                    @if($competition->isRegistrationOpen())
                                        <span class="badge bg-success">Pendaftaran Terbuka</span>
                                    @else
                                        <span class="badge bg-secondary">Pendaftaran Ditutup</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">{{ $competition->getRegisteredParticipantsCount() }} peserta</div>
                                <small class="text-muted">Rp {{ number_format($competition->price, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada kompetisi aktif
                    </div>
                    @endforelse
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
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentData['recent_payments'] as $payment)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $payment->registration->user->name }}</div>
                                <small class="text-muted">{{ $payment->registration->competition->name }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $payment->status_class }}">{{ $payment->status_label }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</div>
                                <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-wallet fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada pembayaran
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['months']),
            datasets: [{
                label: 'Pendaftaran',
                data: @json($chartData['registrations']),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Pendapatan (Rp)',
                data: @json($chartData['revenues']),
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
    
    // User Distribution Chart
    const userDistCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userDistCtx, {
        type: 'doughnut',
        data: {
            labels: @json($userDistribution['labels']),
            datasets: [{
                data: @json($userDistribution['data']),
                backgroundColor: @json($userDistribution['colors']),
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
});
</script>
@endpush
