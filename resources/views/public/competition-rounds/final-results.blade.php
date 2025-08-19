@extends('layouts.public')

@section('title', $competition->name . ' - Final Results')

@section('content')
<div class="container-fluid py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        <!-- Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    @if($competition->image)
                        <img src="{{ asset('storage/competitions/' . $competition->image) }}" 
                             alt="{{ $competition->name }}" class="me-3" style="height: 80px;">
                    @endif
                    <div>
                        <h1 class="text-white mb-0">{{ $competition->name }}</h1>
                        <p class="text-white-50 mb-0">Final Results & Rankings</p>
                    </div>
                </div>
                <a href="{{ route('matalomba.show', $competition->slug) }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>Back to Competition
                </a>
            </div>
        </div>

        <!-- Competition Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Competition Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Competition:</strong><br>
                                <span class="text-muted">{{ $competition->name }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Category:</strong><br>
                                <span class="text-muted">{{ $competition->category }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Total Participants:</strong><br>
                                <span class="text-muted">{{ $finalResults->count() }} {{ $competition->is_team_competition ? 'Teams' : 'Participants' }}</span>
                            </div>
                        </div>
                        
                        @if($competition->theme)
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Theme:</strong><br>
                                <div class="alert alert-info mt-2 mb-0">{{ $competition->theme }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Final Results Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>Final Results
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($finalResults->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 80px;" class="text-center">Rank</th>
                                            <th style="min-width: 200px;">{{ $competition->is_team_competition ? 'Team' : 'Participant' }}</th>
                                            @if($competition->is_team_competition)
                                                <th style="min-width: 150px;">Members</th>
                                            @endif
                                            <th style="min-width: 150px;">Institution</th>
                                            <th class="text-center" style="width: 120px;">Total Score</th>
                                            <th class="text-center" style="width: 80px;">Grade</th>
                                            <th class="text-center" style="width: 100px;">Evaluations</th>
                                            <th class="text-center" style="width: 120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($finalResults as $result)
                                        <tr class="{{ $result['rank'] <= 3 ? 'table-' . ['warning', 'light', 'info'][$result['rank'] - 1] : '' }} hover-row">
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <strong class="fs-5">{{ $result['rank'] }}</strong>
                                                    @if($result['rank'] == 1)
                                                        <i class="bi bi-trophy-fill text-warning ms-2"></i>
                                                    @elseif($result['rank'] == 2)
                                                        <i class="bi bi-award-fill text-secondary ms-2"></i>
                                                    @elseif($result['rank'] == 3)
                                                        <i class="bi bi-award-fill text-warning ms-2"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($result['registration']->logo_instansi)
                                                        <img src="{{ asset('storage/' . $result['registration']->logo_instansi) }}" 
                                                             class="me-2" alt="Logo" style="max-height: 40px; max-width: 50px;">
                                                    @endif
                                                    <div>
                                                        <strong class="text-primary">{{ $result['team_name'] }}</strong>
                                                        @if($result['registration']->registration_number)
                                                            <br><small class="text-muted">{{ $result['registration']->registration_number }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            @if($competition->is_team_competition)
                                            <td>
                                                @if($result['participants'] && $result['participants']->count() > 0)
                                                    @foreach($result['participants'] as $participant)
                                                        <div class="small">{{ $participant->name }}</div>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">{{ $result['registration']->user->name }}</span>
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                <span class="text-muted">{{ $result['registration']->institution ?: $result['registration']->user->institution ?: 'Unknown' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <strong class="text-success fs-5">{{ number_format($result['total_score'], 2) }}</strong>
                                                    @if($competition->isSpcCompetition())
                                                        <br><small class="text-muted">/100</small>
                                                    @elseif($competition->isEdcCompetition() || $competition->isKdbiCompetition())
                                                        <br><small class="text-muted">/100</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $result['grade'] == 'A+' || $result['grade'] == 'A' ? 'success' : ($result['grade'] == 'A-' || $result['grade'] == 'B+' || $result['grade'] == 'B' ? 'primary' : ($result['grade'] == 'B-' || $result['grade'] == 'C+' || $result['grade'] == 'C' ? 'warning' : 'secondary')) }} fs-6">
                                                    {{ $result['grade'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $result['scores_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('matalomba.match', [$competition->slug, 'final', 'detail' . urlencode($result['team_name'])]) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Details
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Statistics Summary -->
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <i class="bi bi-award text-warning" style="font-size: 2rem;"></i>
                                            <h5 class="card-title mt-2">Highest Score</h5>
                                            <p class="card-text fs-4 text-success">
                                                {{ $finalResults->count() > 0 ? number_format($finalResults->first()['total_score'], 2) : '0' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-info">
                                        <div class="card-body text-center">
                                            <i class="bi bi-graph-up text-info" style="font-size: 2rem;"></i>
                                            <h5 class="card-title mt-2">Average Score</h5>
                                            <p class="card-text fs-4 text-primary">
                                                {{ $finalResults->count() > 0 ? number_format($finalResults->avg('total_score'), 2) : '0' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="bi bi-people text-success" style="font-size: 2rem;"></i>
                                            <h5 class="card-title mt-2">Total Participants</h5>
                                            <p class="card-text fs-4 text-success">{{ $finalResults->count() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <i class="bi bi-clipboard-data text-primary" style="font-size: 2rem;"></i>
                                            <h5 class="card-title mt-2">Total Evaluations</h5>
                                            <p class="card-text fs-4 text-primary">{{ $finalResults->sum('scores_count') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">No results available yet</h5>
                                <p class="text-muted">Final results will appear after all evaluations are completed</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($competition->isSpcCompetition())
        <!-- SPC Scoring Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>SPC Final Score Calculation</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Scoring Components:</strong>
                                <ul class="mt-2">
                                    <li>Scientific Paper Evaluation (Semifinal): <strong>60%</strong></li>
                                    <li>Presentation Evaluation (Final): <strong>40%</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>Final Score Formula:</strong>
                                <div class="mt-2 p-2 bg-light rounded">
                                    <code>Final Score = (Paper Score × 60%) + (Presentation Score × 40%)</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($competition->isEdcCompetition() || $competition->isKdbiCompetition())
        <!-- Debate Scoring Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>{{ $competition->isEdcCompetition() ? 'EDC' : 'KDBI' }} Scoring Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Scoring Range:</strong>
                                <ul class="mt-2">
                                    <li>Minimum Score: <strong>50 points</strong></li>
                                    <li>Maximum Score: <strong>100 points</strong></li>
                                    <li>Scoring based on debate performance across multiple rounds</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>Grade Scale:</strong>
                                <ul class="mt-2 small">
                                    <li>A+: 96-100 | A: 91-95 | A-: 86-90</li>
                                    <li>B+: 81-85 | B: 76-80 | B-: 71-75</li>
                                    <li>C+: 66-70 | C: 61-65 | C-: 56-60</li>
                                    <li>D: 50-55 | F: Below 50</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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

.table th, .table td {
    vertical-align: middle;
}

.table-bordered {
    border: 2px solid #dee2e6;
}

.hover-row {
    transition: background-color 0.2s ease;
}

.hover-row:hover {
    background-color: #f8f9fa !important;
}

.badge {
    font-size: 0.8em;
}

.fs-6 {
    font-size: 1rem !important;
}

.stats-card {
    transition: transform 0.2s ease;
}

.stats-card:hover {
    transform: translateY(-2px);
}
</style>
@endpush