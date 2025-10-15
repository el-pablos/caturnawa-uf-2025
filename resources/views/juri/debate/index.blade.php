@extends('layouts.juri')

@section('title', 'My Debate Matches')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-1">My Debate Matches</h1>
        <p class="text-muted mb-0">View and score your assigned debate matches</p>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Stage</label>
                    <select name="stage" class="form-select" onchange="this.form.submit()">
                        <option value="">All Stages</option>
                        <option value="PRELIMINARY" {{ request('stage') == 'PRELIMINARY' ? 'selected' : '' }}>Preliminary</option>
                        <option value="SEMIFINAL" {{ request('stage') == 'SEMIFINAL' ? 'selected' : '' }}>Semifinal</option>
                        <option value="FINAL" {{ request('stage') == 'FINAL' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Round Number</label>
                    <select name="round_number" class="form-select" onchange="this.form.submit()">
                        <option value="">All Rounds</option>
                        @for($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ request('round_number') == $i ? 'selected' : '' }}>Round {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Matches List -->
    @if(empty($matches) || count($matches) == 0)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <p class="text-muted mt-3">No matches assigned yet.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($matches as $match)
            <div class="col-md-6">
                <div class="card border {{ $match['completed_at'] ? 'border-success' : 'border-warning' }} h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $match['round']['round_name'] ?? 'Unknown Round' }}</strong>
                            <span class="badge bg-{{ $match['round']['stage'] == 'PRELIMINARY' ? 'primary' : ($match['round']['stage'] == 'SEMIFINAL' ? 'warning' : 'success') }} ms-2">
                                {{ $match['round']['stage'] ?? 'Unknown' }}
                            </span>
                        </div>
                        @if($match['completed_at'])
                            <span class="badge bg-success">Scored</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Match Info -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Match {{ $match['match_number'] }}</h6>
                            @if(isset($match['room_name']) && $match['room_name'])
                                <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $match['room_name'] }}</p>
                            @endif
                            @if(isset($match['scheduled_at']) && $match['scheduled_at'])
                                <p class="mb-1"><i class="bi bi-clock me-2"></i>{{ \Carbon\Carbon::parse($match['scheduled_at'])->format('d M Y, H:i') }}</p>
                            @endif
                        </div>

                        <!-- Teams -->
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Teams (BP Format)</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block">OG</small>
                                        <strong class="small">{{ $match['team1']['team_name'] ?? 'TBD' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block">OO</small>
                                        <strong class="small">{{ $match['team2']['team_name'] ?? 'TBD' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block">CG</small>
                                        <strong class="small">{{ $match['team3']['team_name'] ?? 'TBD' }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 border rounded bg-light">
                                        <small class="text-muted d-block">CO</small>
                                        <strong class="small">{{ $match['team4']['team_name'] ?? 'TBD' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2">
                            @if($match['completed_at'])
                                <a href="{{ route('juri.debate.view-scores', $match['id']) }}" class="btn btn-outline-success">
                                    <i class="bi bi-eye me-2"></i>View Scores
                                </a>
                                @if(!($match['round']['is_frozen'] ?? false))
                                    <a href="{{ route('juri.debate.score', $match['id']) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil me-2"></i>Edit Scores
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('juri.debate.score', $match['id']) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil-square me-2"></i>Score Match
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

