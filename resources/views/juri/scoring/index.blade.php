@extends('layouts.juri')

@section('title', 'Penilaian Karya')

@section('page-title', 'Penilaian Karya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-award me-2"></i>Daftar Karya untuk Dinilai
                </h5>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="competition_filter" class="form-label">Filter Kompetisi</label>
                        <select class="form-select" id="competition_filter">
                            <option value="">Semua Kompetisi</option>
                            @foreach($competitions as $competition)
                                <option value="{{ $competition->id }}" {{ request('competition') == $competition->id ? 'selected' : '' }}>
                                    {{ $competition->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status_filter" class="form-label">Filter Status</label>
                        <select class="form-select" id="status_filter">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dinilai</option>
                            <option value="scored" {{ request('status') == 'scored' ? 'selected' : '' }}>Sudah Dinilai</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Cari Peserta</label>
                        <input type="text" class="form-control" id="search" placeholder="Nama peserta..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $totalSubmissions }}</h4>
                                <small>Total Karya</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $pendingSubmissions }}</h4>
                                <small>Belum Dinilai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ $scoredSubmissions }}</h4>
                                <small>Sudah Dinilai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0">{{ number_format($averageScore, 1) }}</h4>
                                <small>Rata-rata Nilai</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submissions Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Peserta</th>
                                <th>Kompetisi</th>
                                <th>Judul Karya</th>
                                <th>Tanggal Submit</th>
                                <th>Status Penilaian</th>
                                <th>Nilai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                            <tr>
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
                                    <span class="badge bg-primary">{{ $submission->competition->name }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $submission->title }}</div>
                                    @if($submission->description)
                                        <small class="text-muted">{{ Str::limit($submission->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $submission->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @php
                                        $score = $submission->scores()->where('juri_id', auth()->id())->first();
                                    @endphp
                                    @if($score)
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
                                    @if($score)
                                        <span class="fw-bold text-success">{{ $score->total_score }}/100</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('juri.scoring.show', $submission) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($score)
                                            <a href="{{ route('juri.scoring.edit', $submission) }}" 
                                               class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('juri.scoring.create', $submission) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="bi bi-plus-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-4 d-block mb-2"></i>
                                    Belum ada karya yang perlu dinilai
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($submissions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $submissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Score Modal -->
<div class="modal fade" id="quickScoreModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickScoreForm">
                    <input type="hidden" id="submission_id" name="submission_id">
                    
                    <div class="mb-3">
                        <label for="creativity_score" class="form-label">Kreativitas (0-25)</label>
                        <input type="number" class="form-control" id="creativity_score" name="creativity_score" min="0" max="25" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="technical_score" class="form-label">Teknis (0-25)</label>
                        <input type="number" class="form-control" id="technical_score" name="technical_score" min="0" max="25" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="presentation_score" class="form-label">Presentasi (0-25)</label>
                        <input type="number" class="form-control" id="presentation_score" name="presentation_score" min="0" max="25" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="innovation_score" class="form-label">Inovasi (0-25)</label>
                        <input type="number" class="form-control" id="innovation_score" name="innovation_score" min="0" max="25" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comments" class="form-label">Komentar</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Total Nilai: <span id="total_score">0</span>/100</strong>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="saveQuickScore">Simpan Nilai</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const competitionFilter = document.getElementById('competition_filter');
    const statusFilter = document.getElementById('status_filter');
    const searchInput = document.getElementById('search');
    
    function applyFilters() {
        const params = new URLSearchParams();
        
        if (competitionFilter.value) params.set('competition', competitionFilter.value);
        if (statusFilter.value) params.set('status', statusFilter.value);
        if (searchInput.value) params.set('search', searchInput.value);
        
        window.location.search = params.toString();
    }
    
    competitionFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });
    
    // Quick score modal
    const quickScoreModal = new bootstrap.Modal(document.getElementById('quickScoreModal'));
    const scoreInputs = ['creativity_score', 'technical_score', 'presentation_score', 'innovation_score'];
    const totalScoreSpan = document.getElementById('total_score');
    
    function calculateTotal() {
        let total = 0;
        scoreInputs.forEach(inputId => {
            const value = parseInt(document.getElementById(inputId).value) || 0;
            total += value;
        });
        totalScoreSpan.textContent = total;
    }
    
    scoreInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('input', calculateTotal);
    });
    
    // Save quick score
    document.getElementById('saveQuickScore').addEventListener('click', function() {
        const form = document.getElementById('quickScoreForm');
        const formData = new FormData(form);
        
        // Here you would typically send an AJAX request to save the score
        console.log('Saving score...', Object.fromEntries(formData));
        
        // For now, just close the modal and reload
        quickScoreModal.hide();
        location.reload();
    });
});
</script>
@endpush
