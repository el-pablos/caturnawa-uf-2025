@extends('layouts.app')

@section('title', $competition->name . ' - Babak Kompetisi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-2">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('competitions.index') }}">Kompetisi</a></li>
                                    <li class="breadcrumb-item active">{{ $competition->name }}</li>
                                </ol>
                            </nav>
                            <h1 class="h3 mb-1 fw-bold text-primary">{{ $competition->name }}</h1>
                            <p class="text-muted mb-0">{{ $competition->short_description }}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-flex flex-column align-items-md-end">
                                <span class="badge bg-{{ $competition->registration_status == 'open' ? 'success' : 'secondary' }} mb-2">
                                    {{ ucfirst($competition->registration_status) }}
                                </span>
                                <small class="text-muted">
                                    {{ $registrations->count() }} Tim Terdaftar
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Rounds Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-trophy me-2"></i>
                        Babak Kompetisi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($rounds as $round)
                        <div class="col-md-4">
                            <div class="card h-100 border-2 border-{{ $round->status == 'completed' ? 'success' : ($round->status == 'ongoing' ? 'warning' : 'light') }}">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        @if($round->status == 'completed')
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                                        @elseif($round->status == 'ongoing')
                                            <i class="bi bi-clock-fill text-warning" style="font-size: 2rem;"></i>
                                        @else
                                            <i class="bi bi-circle text-muted" style="font-size: 2rem;"></i>
                                        @endif
                                    </div>
                                    <h6 class="card-title fw-bold">{{ $round->name }}</h6>
                                    <p class="card-text text-muted small">{{ $round->description }}</p>
                                    
                                    @if($round->start_date)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $round->start_date->format('d M Y') }}
                                        </small>
                                    </div>
                                    @endif

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('competitions.rounds.show', [$competition, $round]) }}" 
                                           class="btn btn-{{ $round->status == 'completed' ? 'success' : 'primary' }} btn-sm">
                                            @if($round->status == 'completed')
                                                <i class="bi bi-eye me-1"></i>
                                                Lihat Hasil
                                            @else
                                                <i class="bi bi-list me-1"></i>
                                                Lihat Detail
                                            @endif
                                        </a>
                                        
                                        @if($round->round_type == 'final' && $round->status == 'completed')
                                        <a href="{{ route('competitions.rounds.final-results', $competition) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-trophy me-1"></i>
                                            Hasil Final
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-trophy text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">Belum Ada Babak Kompetisi</h5>
                                <p class="text-muted">Babak kompetisi akan ditambahkan oleh panitia.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $registrations->count() }}</h4>
                    <small class="text-muted">Tim Terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $rounds->count() }}</h4>
                    <small class="text-muted">Total Babak</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $rounds->where('status', 'completed')->count() }}</h4>
                    <small class="text-muted">Babak Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-clock-fill text-info" style="font-size: 2rem;"></i>
                    <h4 class="mt-2 mb-1">{{ $rounds->where('status', 'ongoing')->count() }}</h4>
                    <small class="text-muted">Sedang Berlangsung</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Registered Teams Preview -->
    @if($registrations->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>
                        Tim Terdaftar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tim</th>
                                    <th>Ketua Tim</th>
                                    <th>Asal Institusi</th>
                                    <th>Status</th>
                                    <th>Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrations->take(10) as $registration)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $registration->team_name ?? $registration->user->name }}</strong>
                                    </td>
                                    <td>{{ $registration->user->name }}</td>
                                    <td>{{ $registration->user->university ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $registration->status == 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $registration->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($registrations->count() > 10)
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Menampilkan 10 dari {{ $registrations->count() }} tim terdaftar
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}
</style>
@endpush
@endsection
