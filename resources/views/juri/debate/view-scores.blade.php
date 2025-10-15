@extends('layouts.juri')

@section('title', 'View Scores')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('juri.debate.index') }}">My Matches</a></li>
                <li class="breadcrumb-item active">View Scores</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">Match {{ $match->match_number }} - Scores</h1>
        <p class="text-muted mb-0">{{ $match->round->round_name }} - {{ $match->round->competition->name }}</p>
    </div>

    <!-- Match Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Status</h6>
                    <span class="badge bg-success fs-6">Completed</span>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Completed At</h6>
                    <p class="mb-0">{{ $match->completed_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scores by Team -->
    <div class="row g-4 mb-4">
        @php
            $teams = [
                ['team' => $match->team1, 'position' => 'OG', 'color' => 'primary', 'name' => 'Opening Government'],
                ['team' => $match->team2, 'position' => 'OO', 'color' => 'danger', 'name' => 'Opening Opposition'],
                ['team' => $match->team3, 'position' => 'CG', 'color' => 'success', 'name' => 'Closing Government'],
                ['team' => $match->team4, 'position' => 'CO', 'color' => 'warning', 'name' => 'Closing Opposition'],
            ];
            
            $teamScores = $scoreData['scores'];
        @endphp

        @foreach($teams as $teamData)
            @php
                $team = $teamData['team'];
                $position = $teamData['position'];
                $scores = $teamScores[$position] ?? collect();
                $teamTotal = $scores->sum('score');
                
                // Determine rank
                $rank = null;
                if ($match->first_place_team_id == $team->id) $rank = ['place' => '1st', 'badge' => 'success'];
                elseif ($match->second_place_team_id == $team->id) $rank = ['place' => '2nd', 'badge' => 'info'];
                elseif ($match->third_place_team_id == $team->id) $rank = ['place' => '3rd', 'badge' => 'warning'];
                elseif ($match->fourth_place_team_id == $team->id) $rank = ['place' => '4th', 'badge' => 'secondary'];
            @endphp

            <div class="col-md-6">
                <div class="card border-{{ $teamData['color'] }} h-100">
                    <div class="card-header bg-{{ $teamData['color'] }} {{ $teamData['color'] == 'warning' ? 'text-dark' : 'text-white' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">{{ $position }} - {{ $teamData['name'] }}</h5>
                                <small>{{ $team->team_name ?? 'Unknown' }}</small>
                            </div>
                            @if($rank)
                                <span class="badge bg-{{ $rank['badge'] }} fs-6">{{ $rank['place'] }} Place</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($scores->isEmpty())
                            <p class="text-muted">No scores recorded</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Speaker</th>
                                            <th>Position</th>
                                            <th class="text-end">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($scores as $score)
                                        <tr>
                                            <td>{{ $score->teamMember->participant->full_name ?? 'Unknown' }}</td>
                                            <td><span class="badge bg-secondary">{{ $score->bp_position }}</span></td>
                                            <td class="text-end"><strong>{{ number_format($score->score, 1) }}</strong></td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-active">
                                            <td colspan="2"><strong>Team Total</strong></td>
                                            <td class="text-end"><strong class="fs-5">{{ number_format($teamTotal, 1) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Final Rankings -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Final Rankings</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-success bg-opacity-10 border-success text-center">
                        <h6 class="text-muted mb-2">1st Place</h6>
                        <h5 class="mb-0">{{ $match->firstPlaceTeam->team_name ?? 'Unknown' }}</h5>
                        <small class="text-muted">3 Team Points</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-info bg-opacity-10 border-info text-center">
                        <h6 class="text-muted mb-2">2nd Place</h6>
                        <h5 class="mb-0">{{ $match->secondPlaceTeam->team_name ?? 'Unknown' }}</h5>
                        <small class="text-muted">2 Team Points</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-warning bg-opacity-10 border-warning text-center">
                        <h6 class="text-muted mb-2">3rd Place</h6>
                        <h5 class="mb-0">{{ $match->thirdPlaceTeam->team_name ?? 'Unknown' }}</h5>
                        <small class="text-muted">1 Team Point</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-secondary bg-opacity-10 border-secondary text-center">
                        <h6 class="text-muted mb-2">4th Place</h6>
                        <h5 class="mb-0">{{ $match->fourthPlaceTeam->team_name ?? 'Unknown' }}</h5>
                        <small class="text-muted">0 Team Points</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('juri.debate.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Matches
                </a>
                @if(!$match->round->isFrozen())
                    <a href="{{ route('juri.debate.score', $match->id) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Scores
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

