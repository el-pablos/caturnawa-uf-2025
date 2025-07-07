@extends('layouts.juri')

@section('title', 'Penilaian Pertandingan - ' . $match->match_name)

@section('page-title', 'Penilaian Pertandingan')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.scoring.rounds') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
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

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</div>

<!-- Match Information -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Pertandingan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">{{ $match->match_name }}</h6>
                        <p class="mb-1"><strong>Kompetisi:</strong> {{ $competition->name }}</p>
                        <p class="mb-1"><strong>Babak:</strong> {{ $match->competitionRound->name }}</p>
                        @if($match->room_name)
                            <p class="mb-1"><strong>Ruangan:</strong> {{ $match->room_name }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($match->scheduled_at)
                            <p class="mb-1"><strong>Waktu:</strong> {{ $match->scheduled_at->format('d M Y, H:i') }} WIB</p>
                        @endif
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="badge bg-{{ $match->status == 'completed' ? 'success' : ($match->status == 'ongoing' ? 'warning' : 'secondary') }}">
                                {{ $match->status_name }}
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($match->motion)
                    <div class="mt-3">
                        <h6 class="text-primary">Mosi:</h6>
                        <p class="fs-6 fst-italic">"{{ $match->motion }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Scoring Form -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-juri-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>Form Penilaian
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('juri.scoring.match.store', $match) }}" method="POST">
                    @csrf
                    
                    @foreach($assignedMatchups as $matchup)
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $matchup->registration->team_name }}</h6>
                                    <small class="text-muted">{{ $matchup->registration->institution }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-primary">{{ $matchup->position }}</span>
                                    <small class="text-muted d-block">{{ $matchup->position_name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Team Members -->
                            @if($matchup->registration->team_members && count($matchup->registration->team_members) > 0)
                                <div class="mb-3">
                                    <strong>Anggota Tim:</strong>
                                    <div class="row">
                                        @foreach($matchup->registration->team_members as $member)
                                            <div class="col-md-6">
                                                <small class="text-muted">• {{ $member['name'] }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Scoring Criteria -->
                            <div class="row">
                                @foreach($scoringCriteria as $criteria)
                                <div class="col-md-6 mb-3">
                                    <label for="score_{{ $matchup->id }}_{{ $criteria->id }}" class="form-label">
                                        <strong>{{ $criteria->criteria_name }}</strong>
                                        <small class="text-muted">(Max: {{ $criteria->max_score }})</small>
                                    </label>
                                    @if($criteria->description)
                                        <small class="text-muted d-block">{{ $criteria->description }}</small>
                                    @endif
                                    <input type="number" 
                                           class="form-control" 
                                           id="score_{{ $matchup->id }}_{{ $criteria->id }}"
                                           name="scores[{{ $matchup->id }}][{{ $criteria->id }}]"
                                           min="0" 
                                           max="{{ $criteria->max_score }}" 
                                           step="0.1"
                                           value="{{ old('scores.' . $matchup->id . '.' . $criteria->id, $matchup->individual_scores[$criteria->criteria_name] ?? '') }}"
                                           required>
                                </div>
                                @endforeach
                            </div>

                            <!-- Current Score Display -->
                            @if($matchup->team_score)
                                <div class="alert alert-info">
                                    <strong>Skor Saat Ini:</strong> {{ $matchup->team_score }}
                                    @if($matchup->victory_points !== null)
                                        | <strong>Victory Points:</strong> {{ $matchup->victory_points }}
                                        | <strong>Ranking:</strong> {{ $matchup->ranking }}
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <div class="text-center">
                        <button type="submit" class="btn btn-juri-primary btn-lg">
                            <i class="bi bi-save me-2"></i>Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-juri-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.btn-juri-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    color: white;
}

.btn-juri-primary:hover {
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    color: white;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.border-primary {
    border-color: #007bff !important;
}

.fst-italic {
    font-style: italic;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate team score when individual scores change
    const scoreInputs = document.querySelectorAll('input[type="number"]');
    
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            // You can add real-time calculation here if needed
        });
    });
});
</script>
@endpush
