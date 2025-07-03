@extends('layouts.juri')

@section('title', 'Penilaian - ' . $competition->name)

@section('page-title', 'Penilaian Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('juri.scoring.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Competition Info -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>Kategori:</strong><br>
                        <span class="badge bg-primary">{{ ucfirst($competition->category) }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Total Submission:</strong><br>
                        <span class="text-muted">{{ $submissions->count() }} karya</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Sudah Dinilai:</strong><br>
                        <span class="text-success">{{ $submissions->where('is_scored', true)->count() }} karya</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Belum Dinilai:</strong><br>
                        <span class="text-warning">{{ $submissions->where('is_scored', false)->count() }} karya</span>
                    </div>
                </div>
                
                @if($submissions->count() > 0)
                    <div class="mt-3">
                        <div class="progress">
                            @php
                                $scoredCount = $submissions->where('is_scored', true)->count();
                                $totalCount = $submissions->count();
                                $percentage = $totalCount > 0 ? ($scoredCount / $totalCount) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $percentage }}%">
                                {{ number_format($percentage, 1) }}% Selesai
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Submissions List -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0">
                    <i class="bi bi-list-task me-2"></i>Daftar Karya untuk Dinilai
                </h6>
            </div>
            <div class="card-body">
                @if($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Peserta</th>
                                    <th>Judul Karya</th>
                                    <th>Tanggal Submit</th>
                                    <th>Status Penilaian</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $index => $submission)
                                <tr class="{{ $submission->is_scored ? 'table-light' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($submission->user->avatar)
                                                <img src="{{ asset('storage/' . $submission->user->avatar) }}" 
                                                     alt="{{ $submission->user->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 40px; height: 40px;">
                                                    <span class="text-white fw-bold">{{ substr($submission->user->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $submission->user->name }}</div>
                                                <small class="text-muted">{{ $submission->user->institution ?? 'Institusi tidak diketahui' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $submission->title }}</div>
                                        @if($submission->description)
                                            <small class="text-muted">{{ Str::limit($submission->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if($submission->is_scored)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Sudah Dinilai
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="bi bi-clock me-1"></i>Belum Dinilai
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($submission->jury_score)
                                            <span class="fw-bold text-success">
                                                {{ $submission->jury_score->total_score }}/100
                                            </span>
                                            @if($submission->jury_score->is_final)
                                                <br><small class="text-success">Final</small>
                                            @else
                                                <br><small class="text-warning">Draft</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('juri.scoring.submission', $submission) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if($submission->is_scored)
                                                <a href="{{ route('juri.scoring.edit', $submission) }}" 
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="Edit Penilaian">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('juri.scoring.create', $submission) }}" 
                                                   class="btn btn-sm btn-success"
                                                   title="Beri Nilai">
                                                    <i class="bi bi-plus-circle"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <h5 class="text-muted mt-3">Belum Ada Submission</h5>
                        <p class="text-muted">Belum ada peserta yang mengumpulkan karya untuk kompetisi ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
@if($submissions->count() > 0)
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @php
                        $nextUnscored = $submissions->where('is_scored', false)->first();
                    @endphp
                    @if($nextUnscored)
                        <a href="{{ route('juri.scoring.create', $nextUnscored) }}" class="btn btn-success">
                            <i class="bi bi-play-circle me-2"></i>Mulai Penilaian Berikutnya
                        </a>
                    @endif
                    
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                        <i class="bi bi-list-check me-2"></i>Aksi Massal
                    </button>
                    
                    <a href="{{ route('juri.scoring.export', $competition) }}" class="btn btn-outline-success">
                        <i class="bi bi-download me-2"></i>Export Nilai
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistik Penilaian
                </h6>
            </div>
            <div class="card-body">
                @php
                    $scoredSubmissions = $submissions->where('is_scored', true);
                    $averageScore = $scoredSubmissions->count() > 0 ? $scoredSubmissions->avg('jury_score.total_score') : 0;
                    $highestScore = $scoredSubmissions->count() > 0 ? $scoredSubmissions->max('jury_score.total_score') : 0;
                    $lowestScore = $scoredSubmissions->count() > 0 ? $scoredSubmissions->min('jury_score.total_score') : 0;
                @endphp
                
                <div class="row text-center">
                    <div class="col-4">
                        <h5 class="text-primary mb-0">{{ number_format($averageScore, 1) }}</h5>
                        <small class="text-muted">Rata-rata</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-success mb-0">{{ $highestScore }}</h5>
                        <small class="text-muted">Tertinggi</small>
                    </div>
                    <div class="col-4">
                        <h5 class="text-warning mb-0">{{ $lowestScore }}</h5>
                        <small class="text-muted">Terendah</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Pilih aksi yang ingin dilakukan untuk semua submission:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="markAllAsReviewed()">
                        <i class="bi bi-eye-check me-2"></i>Tandai Semua Sudah Direview
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="resetAllScores()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset Semua Nilai
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="finalizeAllScores()">
                        <i class="bi bi-check-all me-2"></i>Finalisasi Semua Nilai
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh every 5 minutes to get latest submissions
    setInterval(function() {
        location.reload();
    }, 300000); // 5 minutes
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + N for next unscored submission
        if (e.ctrlKey && e.key === 'n') {
            e.preventDefault();
            const nextBtn = document.querySelector('a[href*="scoring/create"]');
            if (nextBtn) {
                nextBtn.click();
            }
        }
        
        // Ctrl + E for export
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            const exportBtn = document.querySelector('a[href*="export"]');
            if (exportBtn) {
                exportBtn.click();
            }
        }
    });
});

function markAllAsReviewed() {
    if (confirm('Tandai semua submission sebagai sudah direview?')) {
        // Implementation for bulk review marking
        console.log('Marking all as reviewed...');
        // You would typically send an AJAX request here
    }
}

function resetAllScores() {
    if (confirm('Reset semua nilai? Tindakan ini tidak dapat dibatalkan!')) {
        // Implementation for bulk score reset
        console.log('Resetting all scores...');
        // You would typically send an AJAX request here
    }
}

function finalizeAllScores() {
    if (confirm('Finalisasi semua nilai? Setelah ini nilai tidak dapat diubah!')) {
        // Implementation for bulk score finalization
        console.log('Finalizing all scores...');
        // You would typically send an AJAX request here
    }
}
</script>
@endpush
