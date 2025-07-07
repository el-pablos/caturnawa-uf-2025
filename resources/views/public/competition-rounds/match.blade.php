@extends('layouts.public')

@section('title', $competition->name . ' - ' . $round->name . ' - ' . $match->match_name)

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    @if($competition->image)
                        <img src="{{ asset('storage/competitions/' . $competition->image) }}" 
                             alt="{{ $competition->name }}" class="me-3" style="height: 60px;">
                    @endif
                    <div>
                        <h1 class="text-white mb-0">{{ $match->match_name }}</h1>
                        <p class="text-white-50 mb-0">{{ $round->name }} - {{ $competition->name }}</p>
                    </div>
                </div>
                <a href="{{ route('matalomba.round', [$competition->slug, $round->round_type]) }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Match Info -->
        @if($match->motion || $match->scheduled_at || $match->room_name)
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Informasi Pertandingan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($match->motion)
                        <div class="mb-3">
                            <h6 class="text-primary">Mosi:</h6>
                            <p class="fs-5 fst-italic">"{{ $match->motion }}"</p>
                        </div>
                        @endif
                        
                        <div class="row">
                            @if($match->scheduled_at)
                            <div class="col-md-6">
                                <h6 class="text-primary">Waktu:</h6>
                                <p><i class="bi bi-clock me-2"></i>{{ $match->scheduled_at->format('d M Y, H:i') }} WIB</p>
                            </div>
                            @endif
                            
                            @if($match->room_name)
                            <div class="col-md-6">
                                <h6 class="text-primary">Ruangan:</h6>
                                <p><i class="bi bi-geo-alt me-2"></i>{{ $match->room_name }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'ongoing' ? 'warning' : 'secondary') }} fs-6">
                                {{ $match->status_name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Match Results -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Hasil Pertandingan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($matchResults->count() > 0)
                            @foreach($matchResults as $roomName => $teamMatchups)
                            <div class="mb-5">
                                @if($matchResults->count() > 1)
                                <h6 class="text-primary mb-3">{{ $roomName }}</h6>
                                @endif
                                
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Adjudicators</th>
                                                <th>Nama Tim</th>
                                                <th>Posisi Tim</th>
                                                <th>Nama Peserta</th>
                                                <th>Score</th>
                                                <th>Victory Point</th>
                                                <th>Individual</th>
                                                <th>Tim Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($teamMatchups->sortBy('ranking') as $matchup)
                                            <tr class="{{ $matchup->ranking == 1 ? 'table-warning' : '' }}">
                                                <td>
                                                    @if($matchup->jury)
                                                        {{ $matchup->jury->name }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $matchup->registration->team_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $matchup->registration->institution }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $matchup->position }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $matchup->position_name }}</small>
                                                </td>
                                                <td>
                                                    @if($matchup->registration->team_members && count($matchup->registration->team_members) > 0)
                                                        @foreach($matchup->registration->team_members as $member)
                                                            <div class="small">
                                                                {{ $member['name'] }}
                                                                @if(isset($member['role']) && $member['role'])
                                                                    <span class="text-muted">({{ $member['role'] }})</span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($matchup->individual_scores && count($matchup->individual_scores) > 0)
                                                        @foreach($matchup->individual_scores as $score)
                                                            <div class="small">{{ $score }}</div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-success fs-6">{{ $matchup->victory_points }}</span>
                                                </td>
                                                <td>
                                                    @if($matchup->individual_scores && count($matchup->individual_scores) > 0)
                                                        @foreach($matchup->individual_scores as $score)
                                                            <div class="small">{{ $score }}</div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($matchup->team_score)
                                                        <strong>{{ $matchup->team_score }}</strong>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-data text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Hasil pertandingan belum tersedia</h5>
                                <p class="text-muted">Hasil akan diumumkan setelah pertandingan selesai</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.table-responsive {
    border-radius: 10px;
}

.fst-italic {
    font-style: italic;
}
</style>
@endpush
