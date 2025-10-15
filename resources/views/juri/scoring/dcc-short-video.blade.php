@extends('layouts.juri')

@section('title', 'DCC Short Video Scoring')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('juri.scoring.index') }}">Scoring</a></li>
                <li class="breadcrumb-item active">DCC Short Video</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">DCC Short Video Scoring</h1>
        <p class="text-muted mb-0">{{ $submission->title }}</p>
    </div>

    <!-- Stage Selector -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Judging Stage:</label>
                </div>
                <div class="col-md-9">
                    <select id="stageSelect" class="form-select" onchange="window.location.href='?stage=' + this.value">
                        <option value="preliminary_round" {{ $stage == 'preliminary_round' ? 'selected' : '' }}>Preliminary Round</option>
                        <option value="semifinal_round" {{ $stage == 'semifinal_round' ? 'selected' : '' }}>Semifinal Round</option>
                        <option value="final_round" {{ $stage == 'final_round' ? 'selected' : '' }}>Final Round</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Info -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Team</h6>
                    <p class="mb-0"><strong>{{ $submission->registration->team_name }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Institution</h6>
                    <p class="mb-0">{{ $submission->registration->user->institution }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Stage</h6>
                    <span class="badge bg-{{ $stage == 'preliminary_round' ? 'primary' : ($stage == 'semifinal_round' ? 'warning' : 'success') }} fs-6">
                        {{ ucwords(str_replace('_', ' ', $stage)) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Duration</h6>
                    <p class="mb-0">{{ $submission->metadata['duration'] ?? '3:00' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Player -->
    @if($submission->file_path || ($submission->metadata['youtube_url'] ?? null))
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-play-circle me-2"></i>Short Video</h5>
        </div>
        <div class="card-body">
            @if($submission->metadata['youtube_url'] ?? null)
                <div class="ratio ratio-16x9">
                    <iframe src="{{ $submission->metadata['youtube_url'] }}" 
                            title="Short Video" 
                            allowfullscreen></iframe>
                </div>
            @elseif($submission->file_path)
                <div class="ratio ratio-16x9">
                    <video controls>
                        <source src="{{ Storage::url($submission->file_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
            
            @if($submission->metadata['description'] ?? null)
                <div class="mt-3">
                    <h6>Video Description:</h6>
                    <p class="text-muted">{{ $submission->metadata['description'] }}</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Scoring Form -->
    <form action="{{ route('juri.scoring.submit', $submission->id) }}" method="POST">
        @csrf
        <input type="hidden" name="stage" value="{{ $stage }}">
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Scoring Criteria - {{ ucwords(str_replace('_', ' ', $stage)) }}</h5>
            </div>
            <div class="card-body">
                @php
                    $criteria = \App\Models\Score::getDccShortVideoCriteria($stage);
                    $existingScore = $existingScore ?? null;
                @endphp

                @if(empty($criteria))
                    <div class="alert alert-warning">
                        No criteria defined for this stage.
                    </div>
                @else
                    @foreach($criteria as $criteriaName => $criteriaDetails)
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-2">{{ $criteriaName }}</h6>
                                <p class="text-muted small mb-2">{{ $criteriaDetails['description'] }}</p>
                                <span class="badge bg-info">Weight: {{ $criteriaDetails['weight'] }}%</span>
                                <span class="badge bg-secondary">Range: {{ $criteriaDetails['min_score'] }}-{{ $criteriaDetails['max_score'] }}</span>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Score (0-100)</label>
                                <input type="number" 
                                       name="criteria[{{ str_replace(' ', '_', strtolower($criteriaName)) }}]" 
                                       class="form-control form-control-lg dcc-score-input" 
                                       min="{{ $criteriaDetails['min_score'] }}" 
                                       max="{{ $criteriaDetails['max_score'] }}" 
                                       step="0.1"
                                       data-weight="{{ $criteriaDetails['weight'] }}"
                                       value="{{ old('criteria.' . str_replace(' ', '_', strtolower($criteriaName)), $existingScore->criteria_scores[str_replace(' ', '_', strtolower($criteriaName))] ?? '') }}" 
                                       required>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Total Score Display -->
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="mb-0">Total Weighted Score</h5>
                            <p class="text-muted small mb-0">Automatically calculated based on criteria weights</p>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <h3 class="mb-0" id="totalScore">0.0</h3>
                                <small class="text-muted">out of 100</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Feedback -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Feedback & Comments</h5>
            </div>
            <div class="card-body">
                <textarea name="comments" 
                          class="form-control" 
                          rows="5" 
                          placeholder="Provide detailed feedback on the short video...">{{ old('comments', $existingScore->comments ?? '') }}</textarea>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('juri.scoring.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                    <div>
                        <button type="submit" name="is_final" value="0" class="btn btn-outline-primary me-2">
                            <i class="bi bi-save me-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="is_final" value="1" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Submit Final Score
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate total weighted score
function calculateTotalScore() {
    let totalWeightedScore = 0;
    let totalWeight = 0;
    
    document.querySelectorAll('.dcc-score-input').forEach(input => {
        if (input.value) {
            const score = parseFloat(input.value) || 0;
            const weight = parseFloat(input.dataset.weight) / 100; // Convert percentage to decimal
            totalWeightedScore += score * weight;
            totalWeight += weight;
        }
    });
    
    const finalScore = totalWeight > 0 ? totalWeightedScore : 0;
    document.getElementById('totalScore').textContent = finalScore.toFixed(1);
}

// Attach event listeners
document.querySelectorAll('.dcc-score-input').forEach(input => {
    input.addEventListener('input', calculateTotalScore);
});

// Calculate on page load
document.addEventListener('DOMContentLoaded', calculateTotalScore);
</script>
@endpush

