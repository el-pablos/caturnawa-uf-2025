@extends('layouts.juri')

@section('title', 'Penilaian Babak Kompetisi')

@section('page-title', 'Penilaian Babak Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</div>

@if($competitions->count() > 0)
    @foreach($competitions as $competition)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-juri-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                            </h5>
                            <small class="opacity-75">{{ $competition->short_description }}</small>
                        </div>
                        <span class="badge bg-light text-dark">
                            {{ $competition->rounds->count() }} Babak
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if($competition->rounds->count() > 0)
                        <div class="row">
                            @foreach($competition->rounds as $round)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            @if($round->round_type == 'penyisihan')
                                                <i class="bi bi-play-circle text-primary me-2" style="font-size: 1.5rem;"></i>
                                            @elseif($round->round_type == 'semifinal')
                                                <i class="bi bi-award text-warning me-2" style="font-size: 1.5rem;"></i>
                                            @else
                                                <i class="bi bi-trophy text-success me-2" style="font-size: 1.5rem;"></i>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $round->name }}</h6>
                                                <small class="text-muted">{{ $round->round_type_name }}</small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge bg-{{ $round->status == 'completed' ? 'success' : ($round->status == 'ongoing' ? 'warning' : 'secondary') }}">
                                                {{ $round->status_name }}
                                            </span>
                                        </div>

                                        @if($round->start_date)
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    {{ $round->start_date->format('d M Y') }}
                                                </small>
                                            </div>
                                        @endif

                                        <!-- Show matches for this round -->
                                        @php
                                            $juryMatches = $round->matches()->whereHas('teamMatchups', function($query) {
                                                $query->where('jury_id', auth()->id());
                                            })->get();
                                        @endphp

                                        @if($juryMatches->count() > 0)
                                            <div class="mt-3">
                                                <h6 class="text-primary mb-2">Pertandingan Anda:</h6>
                                                @foreach($juryMatches as $match)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <strong>{{ $match->match_name }}</strong>
                                                            @if($match->room_name)
                                                                <br><small class="text-muted">{{ $match->room_name }}</small>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            @php
                                                                $scoredCount = $match->teamMatchups()
                                                                    ->where('jury_id', auth()->id())
                                                                    ->whereNotNull('team_score')
                                                                    ->count();
                                                                $totalCount = $match->teamMatchups()
                                                                    ->where('jury_id', auth()->id())
                                                                    ->count();
                                                            @endphp
                                                            
                                                            @if($scoredCount == $totalCount && $totalCount > 0)
                                                                <span class="badge bg-success">Selesai</span>
                                                            @elseif($scoredCount > 0)
                                                                <span class="badge bg-warning">{{ $scoredCount }}/{{ $totalCount }}</span>
                                                            @else
                                                                <span class="badge bg-secondary">Belum Dinilai</span>
                                                            @endif
                                                            
                                                            <a href="{{ route('juri.scoring.match', $match) }}" 
                                                               class="btn btn-sm btn-primary ms-2">
                                                                <i class="bi bi-pencil-square"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center text-muted">
                                                <small>Tidak ada pertandingan yang ditugaskan</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <h6 class="text-muted mt-2">Belum ada babak kompetisi</h6>
                            <p class="text-muted small">Babak kompetisi akan muncul setelah dijadwalkan oleh admin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard-data text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Belum Ada Tugas Penilaian</h5>
                    <p class="text-muted">Anda belum ditugaskan untuk menilai kompetisi apapun. Silakan hubungi admin jika ada pertanyaan.</p>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
.bg-juri-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.card {
    border-radius: 15px;
    border: none;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush
