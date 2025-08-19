@extends('layouts.public')

@section('title', $registration->team_name . ' - ' . $competition->name)

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
                        <h1 class="text-white mb-0">{{ $registration->team_name ?: $registration->user->name }}</h1>
                        <p class="text-white-50 mb-0">{{ $competition->name }} - {{ $round->name }}</p>
                    </div>
                </div>
                <a href="{{ route('matalomba.round', [$competition->slug, $round->round_type]) }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left me-2"></i>Back to {{ $round->name }}
                </a>
            </div>
        </div>

        <!-- Team Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>Team Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    @if($registration->logo_instansi)
                                        <img src="{{ asset('storage/' . $registration->logo_instansi) }}" 
                                             class="me-3" alt="Logo" style="max-height: 60px; max-width: 80px;">
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $registration->team_name ?: $registration->user->name }}</h5>
                                        <p class="text-muted mb-0">{{ $registration->institution }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end">
                                    <h4 class="text-success mb-1">{{ number_format($totalScore, 2) }}</h4>
                                    <small class="text-muted">Total Score</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($registration->teamMembers && $registration->teamMembers->count() > 0)
                        <hr>
                        <h6>Team Members:</h6>
                        <div class="row">
                            @foreach($registration->teamMembers as $member)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-circle text-primary me-2"></i>
                                    <div>
                                        <strong>{{ $member->name }}</strong>
                                        @if($member->role)
                                            <br><small class="text-muted">{{ $member->role }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Scoring Details -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clipboard-data me-2"></i>Scoring Details
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($scores->count() > 0 && $juries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="min-width: 200px;">Criteria</th>
                                            @foreach($juries as $jury)
                                                <th class="text-center" style="min-width: 150px;">{{ $jury->name }}</th>
                                            @endforeach
                                            @if($juries->count() > 1)
                                                <th class="text-center bg-warning" style="min-width: 100px;">Average</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($competition->isSpcCompetition())
                                            <!-- SPC Scoring Display -->
                                            @foreach(['naskah' => 'Nilai Naskah (Semifinal)', 'presentasi' => 'Nilai Presentasi (Final)'] as $criteriaType => $criteriaName)
                                                <tr>
                                                    <td><strong>{{ $criteriaName }}</strong></td>
                                                    @php
                                                        $criteriaScores = [];
                                                    @endphp
                                                    @foreach($juries as $jury)
                                                        @php
                                                            $juryScore = $scores->where('jury_id', $jury->id)
                                                                ->where(function($score) use ($criteriaType) {
                                                                    return isset($score->criteria_scores[$criteriaType]) ||
                                                                           str_contains(strtolower($score->comments ?? ''), $criteriaType);
                                                                })->first();
                                                            $score = $juryScore ? ($juryScore->criteria_scores[$criteriaType] ?? $juryScore->total_score) : 0;
                                                            $criteriaScores[] = $score;
                                                        @endphp
                                                        <td class="text-center">
                                                            {{ $score > 0 ? number_format($score, 2) : '-' }}
                                                        </td>
                                                    @endforeach
                                                    @if($juries->count() > 1)
                                                        <td class="text-center bg-warning">
                                                            <strong>{{ number_format(array_sum($criteriaScores) / count(array_filter($criteriaScores)), 2) }}</strong>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            
                                            <!-- Total Score Row -->
                                            <tr class="table-info">
                                                <td><strong>Total Keseluruhan</strong></td>
                                                @foreach($juries as $jury)
                                                    @php
                                                        $juryTotalScore = $scores->where('jury_id', $jury->id)->avg('total_score') ?? 0;
                                                    @endphp
                                                    <td class="text-center">
                                                        <strong>{{ number_format($juryTotalScore, 2) }}</strong>
                                                    </td>
                                                @endforeach
                                                @if($juries->count() > 1)
                                                    <td class="text-center bg-success text-white">
                                                        <strong>{{ number_format($totalScore, 2) }}</strong>
                                                    </td>
                                                @endif
                                            </tr>
                                            
                                        @elseif($competition->isEdcCompetition() || $competition->isKdbiCompetition())
                                            <!-- Debate Scoring Display -->
                                            @foreach(\App\Models\Score::getEdcCriteria() as $criteriaKey => $criteriaName)
                                                <tr>
                                                    <td><strong>{{ $criteriaName }}</strong></td>
                                                    @php
                                                        $criteriaScores = [];
                                                    @endphp
                                                    @foreach($juries as $jury)
                                                        @php
                                                            $juryScore = $scores->where('jury_id', $jury->id)->first();
                                                            $score = $juryScore && isset($juryScore->criteria_scores[$criteriaKey]) 
                                                                ? $juryScore->criteria_scores[$criteriaKey] 
                                                                : 0;
                                                            $criteriaScores[] = $score;
                                                        @endphp
                                                        <td class="text-center">
                                                            {{ $score > 0 ? number_format($score, 0) : '-' }}
                                                        </td>
                                                    @endforeach
                                                    @if($juries->count() > 1)
                                                        <td class="text-center bg-warning">
                                                            <strong>{{ number_format(array_sum($criteriaScores) / count(array_filter($criteriaScores)), 0) }}</strong>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            
                                            <!-- Average Score Row -->
                                            <tr class="table-info">
                                                <td><strong>Average Score</strong></td>
                                                @foreach($juries as $jury)
                                                    @php
                                                        $juryAvgScore = $scores->where('jury_id', $jury->id)->avg('total_score') ?? 0;
                                                    @endphp
                                                    <td class="text-center">
                                                        <strong>{{ number_format($juryAvgScore, 0) }}</strong>
                                                    </td>
                                                @endforeach
                                                @if($juries->count() > 1)
                                                    <td class="text-center bg-success text-white">
                                                        <strong>{{ number_format($totalScore, 0) }}</strong>
                                                    </td>
                                                @endif
                                            </tr>
                                            
                                        @else
                                            <!-- Default Scoring Display -->
                                            <tr>
                                                <td><strong>Overall Score</strong></td>
                                                @foreach($juries as $jury)
                                                    @php
                                                        $juryScore = $scores->where('jury_id', $jury->id)->avg('total_score') ?? 0;
                                                    @endphp
                                                    <td class="text-center">
                                                        {{ number_format($juryScore, 2) }}
                                                    </td>
                                                @endforeach
                                                @if($juries->count() > 1)
                                                    <td class="text-center bg-success text-white">
                                                        <strong>{{ number_format($totalScore, 2) }}</strong>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Comments Section -->
                            @if($scores->whereNotNull('comments')->count() > 0)
                            <div class="mt-4">
                                <h6>Judges Comments:</h6>
                                @foreach($scores->whereNotNull('comments') as $score)
                                    @if($score->jury && $score->comments)
                                    <div class="alert alert-info">
                                        <strong>{{ $score->jury->name }}:</strong>
                                        <p class="mb-0 mt-2">{{ $score->comments }}</p>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clipboard-x text-muted" style="font-size: 4rem;"></i>
                                <h5 class="text-muted mt-3">No scoring data available</h5>
                                <p class="text-muted">Scores will appear after judges complete their evaluation</p>
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
                        <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>SPC Scoring Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Scoring Weight:</strong>
                                <ul class="mt-2">
                                    <li>Penilaian Naskah (Semifinal): <strong>60%</strong></li>
                                    <li>Penilaian Presentasi (Final): <strong>40%</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>Final Score Formula:</strong>
                                <div class="mt-2 p-2 bg-light rounded">
                                    <code>Total = (Naskah × 60%) + (Presentasi × 40%)</code>
                                </div>
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

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-dark th {
    border-color: #454d55;
}

.hover-row:hover {
    background-color: #f8f9fa !important;
}
</style>
@endpush