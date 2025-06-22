@extends('layouts.app')

@section('title', 'Kompetisi Tersedia')
@section('page-title', 'Kompetisi Tersedia')

@section('sidebar-menu')
    <a class="nav-link" href="{{ route('peserta.dashboard') }}">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a class="nav-link active" href="{{ route('peserta.competitions.index') }}">
        <i class="bi bi-trophy me-2"></i>Kompetisi Tersedia
    </a>
    <a class="nav-link" href="{{ route('peserta.registrations.index') }}">
        <i class="bi bi-person-check me-2"></i>Registrasi Saya
    </a>
    <a class="nav-link" href="{{ route('peserta.submissions.index') }}">
        <i class="bi bi-file-earmark-text me-2"></i>Submission Saya
    </a>
@endsection

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="technology" {{ request('category') === 'technology' ? 'selected' : '' }}>Technology</option>
                    <option value="business" {{ request('category') === 'business' ? 'selected' : '' }}>Business</option>
                    <option value="creative" {{ request('category') === 'creative' ? 'selected' : '' }}>Creative</option>
                    <option value="academic" {{ request('category') === 'academic' ? 'selected' : '' }}>Academic</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status Pendaftaran</label>
                <select name="registration_status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="open" {{ request('registration_status') === 'open' ? 'selected' : '' }}>Terbuka</option>
                    <option value="closing_soon" {{ request('registration_status') === 'closing_soon' ? 'selected' : '' }}>Segera Ditutup</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Cari</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Nama kompetisi..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Competitions Grid -->
<div class="row">
    @forelse($competitions as $competition)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card h-100 competition-card">
                @if($competition->image)
                    <img src="{{ $competition->image_url }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $competition->name }}">
                @else
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-trophy text-white" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary">{{ ucfirst($competition->category) }}</span>
                        @if($competition->isRegistrationOpen())
                            <span class="badge bg-success">Pendaftaran Terbuka</span>
                        @elseif($competition->isRegistrationClosingSoon())
                            <span class="badge bg-warning">Segera Ditutup</span>
                        @else
                            <span class="badge bg-secondary">Pendaftaran Ditutup</span>
                        @endif
                    </div>
                    
                    <h5 class="card-title">{{ $competition->name }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($competition->description, 100) }}</p>
                    
                    <div class="row g-2 mb-3 small">
                        <div class="col-6">
                            <i class="bi bi-calendar-event text-muted me-1"></i>
                            <strong>Mulai:</strong><br>
                            {{ $competition->start_date->format('d M Y') }}
                        </div>
                        <div class="col-6">
                            <i class="bi bi-calendar-x text-muted me-1"></i>
                            <strong>Berakhir:</strong><br>
                            {{ $competition->end_date->format('d M Y') }}
                        </div>
                        <div class="col-6">
                            <i class="bi bi-people text-muted me-1"></i>
                            <strong>Peserta:</strong><br>
                            {{ $competition->getRegisteredParticipantsCount() }} orang
                        </div>
                        <div class="col-6">
                            <i class="bi bi-currency-dollar text-muted me-1"></i>
                            <strong>Biaya:</strong><br>
                            @if($competition->price > 0)
                                Rp {{ number_format($competition->price, 0, ',', '.') }}
                            @else
                                Gratis
                            @endif
                        </div>
                    </div>
                    
                    @if($competition->registration_deadline)
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-clock me-1"></i>
                            Batas pendaftaran: {{ $competition->registration_deadline->format('d M Y H:i') }}
                        </div>
                    @endif
                    
                    <div class="mt-auto">
                        @php
                            $userRegistration = $competition->registrations()->where('user_id', auth()->id())->first();
                        @endphp
                        
                        @if($userRegistration)
                            @if($userRegistration->status === 'pending')
                                <button class="btn btn-warning w-100" disabled>
                                    <i class="bi bi-clock me-2"></i>Menunggu Konfirmasi
                                </button>
                            @elseif($userRegistration->status === 'confirmed')
                                <button class="btn btn-success w-100" disabled>
                                    <i class="bi bi-check-circle me-2"></i>Sudah Terdaftar
                                </button>
                            @else
                                <button class="btn btn-secondary w-100" disabled>
                                    <i class="bi bi-x-circle me-2"></i>Registrasi Dibatalkan
                                </button>
                            @endif
                        @else
                            <div class="d-flex gap-2">
                                <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn btn-outline-primary flex-fill">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </a>
                                @if($competition->isRegistrationOpen())
                                    <button class="btn btn-primary flex-fill" onclick="registerCompetition({{ $competition->id }})">
                                        <i class="bi bi-plus-lg me-1"></i>Daftar
                                    </button>
                                @else
                                    <button class="btn btn-secondary flex-fill" disabled>
                                        <i class="bi bi-lock me-1"></i>Ditutup
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak Ada Kompetisi</h5>
                    <p class="text-muted">Belum ada kompetisi yang tersedia saat ini.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($competitions->hasPages())
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $competitions->withQueryString()->links() }}
    </div>
@endif
@endsection

@push('styles')
<style>
.competition-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.competition-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
}

.badge {
    font-size: 0.75rem;
}

.alert-info {
    background-color: #e3f2fd;
    border-color: #bbdefb;
    color: #1976d2;
}
</style>
@endpush

@push('scripts')
<script>
function registerCompetition(competitionId) {
    confirmAction(
        'Daftar Kompetisi',
        'Apakah Anda yakin ingin mendaftar kompetisi ini?',
        function() {
            fetch(`/peserta/competitions/${competitionId}/register`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Berhasil mendaftar kompetisi!');
                    location.reload();
                } else {
                    showError(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                showError('Terjadi kesalahan sistem');
            });
        }
    );
}
</script>
@endpush
