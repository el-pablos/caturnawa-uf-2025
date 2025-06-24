@extends('layouts.juri')

@section('title', 'Penilaian Karya - ' . $submission->title)

@section('page-title', 'Penilaian Karya')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.scoring.competition', $submission->competition) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Submission Details -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ $submission->title }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Peserta:</strong><br>
                        <span class="text-muted">{{ $submission->user->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Institusi:</strong><br>
                        <span class="text-muted">{{ $submission->user->institution ?? 'Tidak diketahui' }}</span>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kompetisi:</strong><br>
                        <span class="badge bg-primary">{{ $submission->competition->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal Submit:</strong><br>
                        <span class="text-muted">{{ $submission->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Deskripsi Karya:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($submission->description)) !!}
                    </div>
                </div>
                
                @if($submission->technologies)
                <div class="mb-3">
                    <strong>Teknologi yang Digunakan:</strong><br>
                    @foreach(explode(',', $submission->technologies) as $tech)
                        <span class="badge bg-secondary me-1">{{ trim($tech) }}</span>
                    @endforeach
                </div>
                @endif
                
                <div class="row">
                    @if($submission->file_path)
                    <div class="col-md-4 mb-3">
                        <strong>File Karya:</strong><br>
                        <a href="{{ route('download.submission', [$submission, basename($submission->file_path)]) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Download File
                        </a>
                    </div>
                    @endif
                    
                    @if($submission->video_url)
                    <div class="col-md-4 mb-3">
                        <strong>Video Demo:</strong><br>
                        <a href="{{ $submission->video_url }}" target="_blank" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-play-circle me-1"></i>Lihat Video
                        </a>
                    </div>
                    @endif
                    
                    @if($submission->github_url)
                    <div class="col-md-4 mb-3">
                        <strong>Repository:</strong><br>
                        <a href="{{ $submission->github_url }}" target="_blank" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-github me-1"></i>Lihat Code
                        </a>
                    </div>
                    @endif
                </div>
                
                @if($submission->preview_image)
                <div class="mt-3">
                    <strong>Preview Karya:</strong>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $submission->preview_image) }}" 
                             alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Scoring Form -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-star me-2"></i>Form Penilaian
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('juri.scoring.store', $submission) }}" method="POST" id="scoring-form">
                    @csrf
                    
                    @foreach($criteria as $criterion => $maxScore)
                    <div class="mb-4">
                        <label for="{{ $criterion }}" class="form-label">
                            <strong>{{ ucfirst(str_replace('_', ' ', $criterion)) }}</strong> 
                            <span class="text-muted">(0-{{ $maxScore }})</span>
                        </label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="range" class="form-range"
                                       id="{{ $criterion }}"
                                       name="criteria[{{ $criterion }}]"
                                       min="0" max="{{ $maxScore }}"
                                       value="{{ old('criteria.'.$criterion, ($score && $score->criteria_scores) ? ($score->criteria_scores[$criterion] ?? 0) : 0) }}"
                                       oninput="updateScore('{{ $criterion }}', this.value, {{ $maxScore }})">
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="number" class="form-control"
                                           id="{{ $criterion }}_input"
                                           min="0" max="{{ $maxScore }}"
                                           value="{{ old('criteria.'.$criterion, ($score && $score->criteria_scores) ? ($score->criteria_scores[$criterion] ?? 0) : 0) }}"
                                           onchange="updateRange('{{ $criterion }}', this.value)">
                                    <span class="input-group-text">/{{ $maxScore }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-text">
                            @switch($criterion)
                                @case('creativity')
                                    Penilaian terhadap keunikan, inovasi, dan kreativitas dalam karya
                                    @break
                                @case('technical')
                                    Penilaian terhadap kualitas teknis, implementasi, dan kompleksitas
                                    @break
                                @case('presentation')
                                    Penilaian terhadap cara penyajian, dokumentasi, dan komunikasi
                                    @break
                                @case('innovation')
                                    Penilaian terhadap tingkat inovasi dan dampak solusi
                                    @break
                                @default
                                    Berikan penilaian sesuai kriteria {{ str_replace('_', ' ', $criterion) }}
                            @endswitch
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="mb-4">
                        <label for="comments" class="form-label">
                            <strong>Komentar dan Feedback</strong>
                        </label>
                        <textarea class="form-control" id="comments" name="comments" rows="5" 
                                  placeholder="Berikan feedback konstruktif untuk peserta...">{{ old('comments', $score?->comments ?? '') }}</textarea>
                        <div class="form-text">Komentar ini akan membantu peserta untuk pengembangan selanjutnya</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_final" name="is_final" value="1"
                                   {{ old('is_final', $score?->is_final ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_final">
                                <strong>Finalisasi Penilaian</strong>
                            </label>
                        </div>
                        <div class="form-text text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Setelah difinalisasi, penilaian tidak dapat diubah lagi
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Total Nilai: <span id="total-score">0</span>/100</strong>
                            </div>
                            <div class="col-md-6">
                                <strong>Grade: <span id="grade">-</span></strong>
                            </div>
                        </div>
                        <div class="progress mt-2">
                            <div class="progress-bar" id="score-progress" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('juri.scoring.competition', $submission->competition) }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                            <i class="bi bi-save me-2"></i>Simpan Draft
                        </button>
                        <button type="submit" name="action" value="final" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Simpan & Finalisasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Scoring Guidelines -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-book me-2"></i>Panduan Penilaian
                </h6>
            </div>
            <div class="card-body">
                <div class="accordion" id="guidelinesAccordion">
                    @foreach($criteria as $criterion => $maxScore)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $criterion }}">
                                {{ ucfirst(str_replace('_', ' ', $criterion)) }} ({{ $maxScore }} poin)
                            </button>
                        </h2>
                        <div id="collapse{{ $criterion }}" class="accordion-collapse collapse" 
                             data-bs-parent="#guidelinesAccordion">
                            <div class="accordion-body">
                                @switch($criterion)
                                    @case('creativity')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Sangat kreatif dan inovatif</li>
                                            <li><strong>Good (16-20):</strong> Kreatif dengan beberapa inovasi</li>
                                            <li><strong>Fair (11-15):</strong> Cukup kreatif</li>
                                            <li><strong>Poor (6-10):</strong> Kurang kreatif</li>
                                            <li><strong>Very Poor (0-5):</strong> Tidak kreatif</li>
                                        </ul>
                                        @break
                                    @case('technical')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Implementasi teknis sangat baik</li>
                                            <li><strong>Good (16-20):</strong> Implementasi teknis baik</li>
                                            <li><strong>Fair (11-15):</strong> Implementasi teknis cukup</li>
                                            <li><strong>Poor (6-10):</strong> Implementasi teknis kurang</li>
                                            <li><strong>Very Poor (0-5):</strong> Implementasi teknis buruk</li>
                                        </ul>
                                        @break
                                    @case('presentation')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Presentasi sangat jelas dan menarik</li>
                                            <li><strong>Good (16-20):</strong> Presentasi jelas dan baik</li>
                                            <li><strong>Fair (11-15):</strong> Presentasi cukup jelas</li>
                                            <li><strong>Poor (6-10):</strong> Presentasi kurang jelas</li>
                                            <li><strong>Very Poor (0-5):</strong> Presentasi tidak jelas</li>
                                        </ul>
                                        @break
                                    @case('innovation')
                                        <ul class="mb-0">
                                            <li><strong>Excellent (21-25):</strong> Sangat inovatif dan berdampak</li>
                                            <li><strong>Good (16-20):</strong> Inovatif dengan dampak baik</li>
                                            <li><strong>Fair (11-15):</strong> Cukup inovatif</li>
                                            <li><strong>Poor (6-10):</strong> Kurang inovatif</li>
                                            <li><strong>Very Poor (0-5):</strong> Tidak inovatif</li>
                                        </ul>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Previous Scores (if any) -->
        @if($score && $score->created_at != $score->updated_at)
        <div class="card mb-3">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Riwayat Penilaian
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Dibuat:</strong> {{ $score->created_at->format('d M Y H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Terakhir Update:</strong> {{ $score->updated_at->format('d M Y H:i') }}
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-{{ $score->is_final ? 'success' : 'warning' }}">
                        {{ $score->is_final ? 'Final' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="setQuickScore(85)">
                        <i class="bi bi-star me-1"></i>Set Excellent (85)
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="setQuickScore(75)">
                        <i class="bi bi-star me-1"></i>Set Good (75)
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="setQuickScore(65)">
                        <i class="bi bi-star me-1"></i>Set Fair (65)
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="resetScores()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    calculateTotal();
    
    // Auto-save draft every 2 minutes
    setInterval(function() {
        if (document.getElementById('is_final').checked === false) {
            saveDraft();
        }
    }, 120000);
});

function updateScore(criterion, value, maxScore) {
    document.getElementById(criterion + '_input').value = value;
    document.getElementById(criterion).value = value;
    calculateTotal();
}

function updateRange(criterion, value) {
    document.getElementById(criterion).value = value;
    calculateTotal();
}

function calculateTotal() {
    const criteria = @json(array_keys($criteria));
    let total = 0;
    
    criteria.forEach(function(criterion) {
        const value = parseInt(document.getElementById(criterion + '_input').value) || 0;
        total += value;
    });
    
    document.getElementById('total-score').textContent = total;
    
    // Update progress bar
    const percentage = (total / 100) * 100;
    const progressBar = document.getElementById('score-progress');
    progressBar.style.width = percentage + '%';
    
    // Update grade
    let grade = 'F';
    let progressClass = 'bg-danger';
    
    if (total >= 85) {
        grade = 'A';
        progressClass = 'bg-success';
    } else if (total >= 75) {
        grade = 'B';
        progressClass = 'bg-info';
    } else if (total >= 65) {
        grade = 'C';
        progressClass = 'bg-warning';
    } else if (total >= 55) {
        grade = 'D';
        progressClass = 'bg-danger';
    }
    
    document.getElementById('grade').textContent = grade;
    progressBar.className = 'progress-bar ' + progressClass;
}

function setQuickScore(targetTotal) {
    const criteria = @json($criteria);
    const criteriaKeys = Object.keys(criteria);
    const scorePerCriterion = Math.floor(targetTotal / criteriaKeys.length);
    const remainder = targetTotal % criteriaKeys.length;
    
    criteriaKeys.forEach(function(criterion, index) {
        const score = scorePerCriterion + (index < remainder ? 1 : 0);
        const maxScore = criteria[criterion];
        const finalScore = Math.min(score, maxScore);
        
        document.getElementById(criterion).value = finalScore;
        document.getElementById(criterion + '_input').value = finalScore;
    });
    
    calculateTotal();
}

function resetScores() {
    if (confirm('Reset semua nilai ke 0?')) {
        const criteria = @json(array_keys($criteria));
        
        criteria.forEach(function(criterion) {
            document.getElementById(criterion).value = 0;
            document.getElementById(criterion + '_input').value = 0;
        });
        
        calculateTotal();
    }
}

function saveDraft() {
    const formData = new FormData(document.getElementById('scoring-form'));
    formData.append('action', 'draft');
    
    fetch(document.getElementById('scoring-form').action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show temporary success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>Draft tersimpan otomatis
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Auto-save failed:', error);
    });
}

// Form submission handling
document.getElementById('scoring-form').addEventListener('submit', function(e) {
    const action = e.submitter.value;
    
    if (action === 'final') {
        if (!confirm('Finalisasi penilaian? Setelah ini nilai tidak dapat diubah lagi!')) {
            e.preventDefault();
            return false;
        }
        document.getElementById('is_final').checked = true;
    }
});
</script>
@endpush
