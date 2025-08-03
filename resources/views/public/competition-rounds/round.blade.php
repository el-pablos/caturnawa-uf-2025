@extends('layouts.public')

@section('title', $competition->name . ' - ' . $round->name)

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
                        <h1 class="text-white mb-0">{{ $round->name }}</h1>
                        <p class="text-white-50 mb-0">{{ $competition->name }}</p>
                    </div>
                </div>
                <a href="{{ route('matalomba.show', $competition->slug) }}" class="btn btn-outline-light btn-auto w-100">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Leaderboard Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Leaderboard
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($leaderboard->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Rank</th>
                                            <th>Team</th>
                                            <th>Participant</th>
                                            <th>Victory Point</th>
                                            <th>Avg Score</th>
                                            <th>Matches</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaderboard as $index => $team)
                                        <tr class="{{ $index < 3 ? 'table-' . ['warning', 'light', 'info'][$index] : '' }}">
                                            <td>
                                                <strong>{{ $index + 1 }}</strong>
                                                @if($index == 0)
                                                    <i class="bi bi-trophy-fill text-warning ms-1"></i>
                                                @elseif($index == 1)
                                                    <i class="bi bi-award-fill text-secondary ms-1"></i>
                                                @elseif($index == 2)
                                                    <i class="bi bi-award-fill text-warning ms-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $team['team_name'] }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $team['registration']->institution }}</small>
                                            </td>
                                            <td>
                                                @if($team['participants'] && count($team['participants']) > 0)
                                                    @foreach($team['participants'] as $participant)
                                                        <div class="small">{{ $participant['name'] }}</div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">{{ $team['victory_points'] }}</span>
                                            </td>
                                            <td>{{ $team['average_score'] ?? '-' }}</td>
                                            <td>{{ $team['matches_played'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-graph-up text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Leaderboard not yet available</h5>
                                <p class="text-muted">Leaderboard will appear after matches begin</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Matches Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event me-2"></i>Round
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($matches->count() > 0)
                            <div class="row">
                                @foreach($matches as $match)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm hover-card">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="bi bi-play-circle text-primary" style="font-size: 3rem;"></i>
                                            </div>
                                            
                                            <h5 class="card-title">{{ $match->match_name }}</h5>
                                            
                                            @if($match->room_name)
                                                <p class="card-text text-muted">{{ $match->room_name }}</p>
                                            @endif
                                            
                                            @if($match->motion)
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <strong>Motion:</strong><br>
                                                        "{{ Str::limit($match->motion, 100) }}"
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            @if($match->scheduled_at)
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $match->scheduled_at->format('d M Y, H:i') }}
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <div class="mb-3">
                                                <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'ongoing' ? 'warning' : 'secondary') }}">
                                                    {{ $match->status_name }}
                                                </span>
                                            </div>
                                            
                                            <a href="{{ route('matalomba.match', [$competition->slug, $round->round_type, $match->match_name]) }}" 
                                               class="btn btn-primary">
                                                <i class="bi bi-eye me-2"></i>Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">Matches not yet scheduled</h5>
                                <p class="text-muted">Match schedule will be announced by the committee</p>
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
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.table-responsive {
    border-radius: 10px;
}
</style>
@endpush
