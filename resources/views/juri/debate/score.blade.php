@extends('layouts.juri')

@section('title', 'Score Match')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('juri.debate.index') }}">My Matches</a></li>
                <li class="breadcrumb-item active">Score Match</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">Score Match {{ $match->match_number }}</h1>
        <p class="text-muted mb-0">{{ $match->round->round_name }} - {{ $match->round->competition->name }}</p>
    </div>

    @if($match->round->isFrozen())
        <div class="alert alert-warning">
            <i class="bi bi-lock-fill me-2"></i>
            <strong>Round Frozen:</strong> This round has been frozen. Scores cannot be modified.
        </div>
    @endif

    <!-- Motion -->
    @if($match->round->motion)
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="text-muted mb-2">Motion</h6>
            <p class="mb-0 fs-5">{{ $match->round->motion }}</p>
        </div>
    </div>
    @endif

    <!-- Scoring Form -->
    <form action="{{ route('juri.debate.submit-scores', $match->id) }}" method="POST" id="scoringForm">
        @csrf
        
        <div class="row g-4">
            <!-- Team 1 - OG (Opening Government) -->
            <div class="col-md-6">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">OG - Opening Government</h5>
                        <small>{{ $match->team1->team_name ?? 'TBD' }}</small>
                    </div>
                    <div class="card-body">
                        @if($match->team1 && $match->team1->teamMembers->count() >= 2)
                            <!-- PM (Prime Minister) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>PM (Prime Minister)</strong><br>
                                    <small class="text-muted">{{ $match->team1->teamMembers[0]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team1[speaker1]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="1"
                                       value="{{ old('team1.speaker1', $existingScores['team1']['speaker1'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- DPM (Deputy Prime Minister) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>DPM (Deputy Prime Minister)</strong><br>
                                    <small class="text-muted">{{ $match->team1->teamMembers[1]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team1[speaker2]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="1"
                                       value="{{ old('team1.speaker2', $existingScores['team1']['speaker2'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- Team Score -->
                            <div class="mb-0">
                                <label class="form-label"><strong>Team Score</strong></label>
                                <input type="number" step="0.1" 
                                       name="team1[teamScore]" 
                                       id="teamScore1" 
                                       class="form-control form-control-lg bg-light" 
                                       value="{{ old('team1.teamScore', $existingScores['team1']['teamScore'] ?? '') }}" 
                                       readonly required>
                            </div>
                        @else
                            <p class="text-muted">Team members not available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Team 2 - OO (Opening Opposition) -->
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">OO - Opening Opposition</h5>
                        <small>{{ $match->team2->team_name ?? 'TBD' }}</small>
                    </div>
                    <div class="card-body">
                        @if($match->team2 && $match->team2->teamMembers->count() >= 2)
                            <!-- LO (Leader of Opposition) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>LO (Leader of Opposition)</strong><br>
                                    <small class="text-muted">{{ $match->team2->teamMembers[0]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team2[speaker1]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="2"
                                       value="{{ old('team2.speaker1', $existingScores['team2']['speaker1'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- DLO (Deputy Leader of Opposition) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>DLO (Deputy Leader of Opposition)</strong><br>
                                    <small class="text-muted">{{ $match->team2->teamMembers[1]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team2[speaker2]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="2"
                                       value="{{ old('team2.speaker2', $existingScores['team2']['speaker2'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- Team Score -->
                            <div class="mb-0">
                                <label class="form-label"><strong>Team Score</strong></label>
                                <input type="number" step="0.1" 
                                       name="team2[teamScore]" 
                                       id="teamScore2" 
                                       class="form-control form-control-lg bg-light" 
                                       value="{{ old('team2.teamScore', $existingScores['team2']['teamScore'] ?? '') }}" 
                                       readonly required>
                            </div>
                        @else
                            <p class="text-muted">Team members not available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Team 3 - CG (Closing Government) -->
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">CG - Closing Government</h5>
                        <small>{{ $match->team3->team_name ?? 'TBD' }}</small>
                    </div>
                    <div class="card-body">
                        @if($match->team3 && $match->team3->teamMembers->count() >= 2)
                            <!-- MG (Member of Government) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>MG (Member of Government)</strong><br>
                                    <small class="text-muted">{{ $match->team3->teamMembers[0]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team3[speaker1]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="3"
                                       value="{{ old('team3.speaker1', $existingScores['team3']['speaker1'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- GW (Government Whip) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>GW (Government Whip)</strong><br>
                                    <small class="text-muted">{{ $match->team3->teamMembers[1]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team3[speaker2]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="3"
                                       value="{{ old('team3.speaker2', $existingScores['team3']['speaker2'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- Team Score -->
                            <div class="mb-0">
                                <label class="form-label"><strong>Team Score</strong></label>
                                <input type="number" step="0.1" 
                                       name="team3[teamScore]" 
                                       id="teamScore3" 
                                       class="form-control form-control-lg bg-light" 
                                       value="{{ old('team3.teamScore', $existingScores['team3']['teamScore'] ?? '') }}" 
                                       readonly required>
                            </div>
                        @else
                            <p class="text-muted">Team members not available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Team 4 - CO (Closing Opposition) -->
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">CO - Closing Opposition</h5>
                        <small>{{ $match->team4->team_name ?? 'TBD' }}</small>
                    </div>
                    <div class="card-body">
                        @if($match->team4 && $match->team4->teamMembers->count() >= 2)
                            <!-- MO (Member of Opposition) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>MO (Member of Opposition)</strong><br>
                                    <small class="text-muted">{{ $match->team4->teamMembers[0]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team4[speaker1]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="4"
                                       value="{{ old('team4.speaker1', $existingScores['team4']['speaker1'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- OW (Opposition Whip) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <strong>OW (Opposition Whip)</strong><br>
                                    <small class="text-muted">{{ $match->team4->teamMembers[1]->participant->full_name ?? 'Unknown' }}</small>
                                </label>
                                <input type="number" step="0.1" min="70" max="80" 
                                       name="team4[speaker2]" 
                                       class="form-control form-control-lg speaker-score" 
                                       data-team="4"
                                       value="{{ old('team4.speaker2', $existingScores['team4']['speaker2'] ?? '') }}" 
                                       required>
                            </div>
                            <!-- Team Score -->
                            <div class="mb-0">
                                <label class="form-label"><strong>Team Score</strong></label>
                                <input type="number" step="0.1" 
                                       name="team4[teamScore]" 
                                       id="teamScore4" 
                                       class="form-control form-control-lg bg-light" 
                                       value="{{ old('team4.teamScore', $existingScores['team4']['teamScore'] ?? '') }}" 
                                       readonly required>
                            </div>
                        @else
                            <p class="text-muted">Team members not available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ranking -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Team Ranking</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Drag and drop teams to rank them (1st to 4th place)</p>
                <div id="rankingContainer" class="row g-3">
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-success bg-opacity-10 border-success">
                            <h6 class="mb-2">1st Place</h6>
                            <select name="ranking[0]" class="form-select" required>
                                <option value="">Select Team</option>
                                <option value="0" {{ old('ranking.0', $existingScores['ranking'][0] ?? '') == 0 ? 'selected' : '' }}>OG - {{ $match->team1->team_name ?? 'TBD' }}</option>
                                <option value="1" {{ old('ranking.0', $existingScores['ranking'][0] ?? '') == 1 ? 'selected' : '' }}>OO - {{ $match->team2->team_name ?? 'TBD' }}</option>
                                <option value="2" {{ old('ranking.0', $existingScores['ranking'][0] ?? '') == 2 ? 'selected' : '' }}>CG - {{ $match->team3->team_name ?? 'TBD' }}</option>
                                <option value="3" {{ old('ranking.0', $existingScores['ranking'][0] ?? '') == 3 ? 'selected' : '' }}>CO - {{ $match->team4->team_name ?? 'TBD' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-info bg-opacity-10 border-info">
                            <h6 class="mb-2">2nd Place</h6>
                            <select name="ranking[1]" class="form-select" required>
                                <option value="">Select Team</option>
                                <option value="0" {{ old('ranking.1', $existingScores['ranking'][1] ?? '') == 0 ? 'selected' : '' }}>OG - {{ $match->team1->team_name ?? 'TBD' }}</option>
                                <option value="1" {{ old('ranking.1', $existingScores['ranking'][1] ?? '') == 1 ? 'selected' : '' }}>OO - {{ $match->team2->team_name ?? 'TBD' }}</option>
                                <option value="2" {{ old('ranking.1', $existingScores['ranking'][1] ?? '') == 2 ? 'selected' : '' }}>CG - {{ $match->team3->team_name ?? 'TBD' }}</option>
                                <option value="3" {{ old('ranking.1', $existingScores['ranking'][1] ?? '') == 3 ? 'selected' : '' }}>CO - {{ $match->team4->team_name ?? 'TBD' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-warning bg-opacity-10 border-warning">
                            <h6 class="mb-2">3rd Place</h6>
                            <select name="ranking[2]" class="form-select" required>
                                <option value="">Select Team</option>
                                <option value="0" {{ old('ranking.2', $existingScores['ranking'][2] ?? '') == 0 ? 'selected' : '' }}>OG - {{ $match->team1->team_name ?? 'TBD' }}</option>
                                <option value="1" {{ old('ranking.2', $existingScores['ranking'][2] ?? '') == 1 ? 'selected' : '' }}>OO - {{ $match->team2->team_name ?? 'TBD' }}</option>
                                <option value="2" {{ old('ranking.2', $existingScores['ranking'][2] ?? '') == 2 ? 'selected' : '' }}>CG - {{ $match->team3->team_name ?? 'TBD' }}</option>
                                <option value="3" {{ old('ranking.2', $existingScores['ranking'][2] ?? '') == 3 ? 'selected' : '' }}>CO - {{ $match->team4->team_name ?? 'TBD' }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border rounded bg-secondary bg-opacity-10 border-secondary">
                            <h6 class="mb-2">4th Place</h6>
                            <select name="ranking[3]" class="form-select" required>
                                <option value="">Select Team</option>
                                <option value="0" {{ old('ranking.3', $existingScores['ranking'][3] ?? '') == 0 ? 'selected' : '' }}>OG - {{ $match->team1->team_name ?? 'TBD' }}</option>
                                <option value="1" {{ old('ranking.3', $existingScores['ranking'][3] ?? '') == 1 ? 'selected' : '' }}>OO - {{ $match->team2->team_name ?? 'TBD' }}</option>
                                <option value="2" {{ old('ranking.3', $existingScores['ranking'][3] ?? '') == 2 ? 'selected' : '' }}>CG - {{ $match->team3->team_name ?? 'TBD' }}</option>
                                <option value="3" {{ old('ranking.3', $existingScores['ranking'][3] ?? '') == 3 ? 'selected' : '' }}>CO - {{ $match->team4->team_name ?? 'TBD' }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('juri.debate.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Matches
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" {{ $match->round->isFrozen() ? 'disabled' : '' }}>
                        <i class="bi bi-check-circle me-2"></i>Submit Scores
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate team scores
document.querySelectorAll('.speaker-score').forEach(input => {
    input.addEventListener('input', function() {
        const team = this.dataset.team;
        const speaker1 = parseFloat(document.querySelector(`input[name="team${team}[speaker1]"]`).value) || 0;
        const speaker2 = parseFloat(document.querySelector(`input[name="team${team}[speaker2]"]`).value) || 0;
        const teamScore = speaker1 + speaker2;
        document.getElementById(`teamScore${team}`).value = teamScore.toFixed(1);
    });
});

// Validate ranking (no duplicates)
document.getElementById('scoringForm').addEventListener('submit', function(e) {
    const rankings = [];
    for (let i = 0; i < 4; i++) {
        const value = document.querySelector(`select[name="ranking[${i}]"]`).value;
        if (rankings.includes(value)) {
            e.preventDefault();
            alert('Each team must be ranked exactly once. Please check your rankings.');
            return false;
        }
        rankings.push(value);
    }
});
</script>
@endpush

