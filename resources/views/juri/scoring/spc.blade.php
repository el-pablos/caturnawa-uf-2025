@extends('layouts.juri')

@section('title', 'SPC Scoring - ' . $submission->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('juri.scoring.index') }}">Scoring</a></li>
                <li class="breadcrumb-item active">SPC Scoring</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">SPC Scientific Paper Scoring</h1>
        <p class="text-muted mb-0">{{ $submission->title }}</p>
    </div>

    <!-- Submission Info -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Participant</h6>
                    <p class="mb-0"><strong>{{ $submission->registration->user->name }}</strong></p>
                    <small class="text-muted">{{ $submission->registration->user->institution }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Team</h6>
                    <p class="mb-0"><strong>{{ $submission->registration->team_name ?? 'Individual' }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Submitted</h6>
                    <p class="mb-0">{{ $submission->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Paper -->
    @if($submission->file_path)
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Scientific Paper Document</h6>
                    <small class="text-muted">{{ basename($submission->file_path) }}</small>
                </div>
                <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-primary">
                    <i class="bi bi-download me-2"></i>Download Paper
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Scoring Form -->
    <form action="{{ route('juri.scoring.submit', $submission->id) }}" method="POST">
        @csrf
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Scoring Criteria (0-100 Scale)</h5>
            </div>
            <div class="card-body">
                @php
                    $spcCriteria = \App\Models\Score::getSpcCriteria();
                    $existingScore = $existingScore ?? null;
                @endphp

                @foreach($spcCriteria as $key => $criteriaData)
                <div class="mb-4 pb-4 border-bottom">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="mb-2">{{ $criteriaData['name'] }}</h6>
                            <p class="text-muted small mb-2">{{ $criteriaData['description'] }}</p>
                            <span class="badge bg-info">Weight: {{ $criteriaData['weight'] }}%</span>
                            <span class="badge bg-secondary">Range: {{ $criteriaData['min_score'] }}-{{ $criteriaData['max_score'] }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Score (0-100)</label>
                            <input type="number" 
                                   name="criteria[{{ $key }}]" 
                                   class="form-control form-control-lg spc-score-input" 
                                   min="{{ $criteriaData['min_score'] }}" 
                                   max="{{ $criteriaData['max_score'] }}" 
                                   step="0.1"
                                   value="{{ old('criteria.' . $key, $existingScore->criteria_scores[$key] ?? '') }}" 
                                   required>
                            @error('criteria.' . $key)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
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
                            <div class="mt-2">
                                <span id="gradeDisplay" class="badge bg-secondary fs-6">-</span>
                            </div>
                        </div>
                    </div>
                </div>
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
                          placeholder="Provide detailed feedback on the scientific paper...">{{ old('comments', $existingScore->comments ?? '') }}</textarea>
                @error('comments')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Scoring Rubric Reference -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Scoring Rubric Reference</h5>
            </div>
            <div class="card-body">
                @php
                    $rubric = \App\Models\Score::getSpcScoringRubric();
                @endphp
                <div class="row g-3">
                    @foreach($rubric as $range => $data)
                    <div class="col-md-6">
                        <div class="card border-{{ $data['grade'] == 'A' ? 'success' : ($data['grade'] == 'F' ? 'danger' : 'secondary') }}">
                            <div class="card-body">
                                <h6 class="mb-2">
                                    <span class="badge bg-{{ $data['grade'] == 'A' ? 'success' : ($data['grade'] == 'F' ? 'danger' : 'secondary') }}">
                                        Grade {{ $data['grade'] }}
                                    </span>
                                    {{ $range }}
                                </h6>
                                <p class="small mb-2"><strong>{{ $data['description'] }}</strong></p>
                                <ul class="small mb-0">
                                    @foreach($data['criteria'] as $criterion)
                                        <li>{{ $criterion }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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
    const criteria = @json(\App\Models\Score::getSpcCriteria());
    let totalWeightedScore = 0;
    let totalWeight = 0;
    
    for (const [key, data] of Object.entries(criteria)) {
        const input = document.querySelector(`input[name="criteria[${key}]"]`);
        if (input && input.value) {
            const score = parseFloat(input.value) || 0;
            const weight = data.weight / 100; // Convert percentage to decimal
            totalWeightedScore += score * weight;
            totalWeight += weight;
        }
    }
    
    const finalScore = totalWeight > 0 ? totalWeightedScore : 0;
    document.getElementById('totalScore').textContent = finalScore.toFixed(1);
    
    // Update grade display
    let grade = 'F';
    if (finalScore >= 90) grade = 'A';
    else if (finalScore >= 80) grade = 'B+';
    else if (finalScore >= 70) grade = 'B';
    else if (finalScore >= 60) grade = 'C+';
    else if (finalScore >= 50) grade = 'C';
    
    const gradeDisplay = document.getElementById('gradeDisplay');
    gradeDisplay.textContent = `Grade ${grade}`;
    gradeDisplay.className = 'badge fs-6 bg-' + (grade === 'A' ? 'success' : (grade === 'F' ? 'danger' : 'secondary'));
}

// Attach event listeners
document.querySelectorAll('.spc-score-input').forEach(input => {
    input.addEventListener('input', calculateTotalScore);
});

// Calculate on page load
document.addEventListener('DOMContentLoaded', calculateTotalScore);
</script>
@endpush

