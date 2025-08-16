@extends('layouts.juri')

@section('title', 'Form Penilaian')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Form Penilaian Submission</h1>
            <p class="text-muted">Berikan penilaian untuk submission peserta</p>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="refreshData()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
            </button>
            <button class="btn btn-outline-secondary" onclick="exportData()">
                <i class="bi bi-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" id="statistics-cards">
        <!-- Will be populated by JavaScript -->
    </div>

    <!-- Reminder Alert -->
    <div id="reminder-alert" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <span id="reminder-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Filter Controls -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="competition-filter" class="form-label">Kompetisi</label>
                    <select id="competition-filter" class="form-select">
                        <option value="">Semua Kompetisi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status-filter" class="form-label">Status</label>
                    <select id="status-filter" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="submitted">Submitted</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="scored">Scored</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search-input" class="form-label">Pencarian</label>
                    <input type="text" id="search-input" class="form-control" placeholder="Cari peserta atau judul karya...">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabulator Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Submission untuk Dinilai</h6>
        </div>
        <div class="card-body">
            <div id="judging-table"></div>
        </div>
    </div>
</div>

<!-- Quick Score Modal -->
<div class="modal fade" id="quickScoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Penilaian Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickScoreForm">
                    <input type="hidden" id="modal-submission-id">
                    <div class="mb-3">
                        <label for="modal-score" class="form-label">Skor (0-100)</label>
                        <input type="number" class="form-control" id="modal-score" min="0" max="100" step="0.01" required>
                        <div class="form-text">Gunakan desimal untuk skor yang lebih presisi (contoh: 85.75)</div>
                    </div>
                    <div class="mb-3">
                        <label for="modal-feedback" class="form-label">Feedback</label>
                        <textarea class="form-control" id="modal-feedback" rows="4" placeholder="Berikan feedback untuk peserta..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveQuickScore()">Simpan Penilaian</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<style>
.tabulator {
    font-size: 14px;
}
.tabulator .tabulator-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
.tabulator-row.tabulator-selected {
    background-color: #e3f2fd !important;
}
.score-input {
    width: 80px;
    text-align: center;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script>
let judgingTable;
let judgingData = [];

document.addEventListener('DOMContentLoaded', function() {
    initializeJudgingForm();
});

async function initializeJudgingForm() {
    try {
        const response = await fetch('/api/judging/form', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to load judging form data');
        }

        const data = await response.json();
        
        if (data.success) {
            setupStatistics(data.data.statistics);
            setupCompetitionFilter(data.data.competitions);
            setupReminder(data.data.show_reminder, data.data.reminder_message);
            setupTable(data.data.tabulator_config, data.data.submissions);
            judgingData = data.data.submissions;
        }
    } catch (error) {
        console.error('Error initializing judging form:', error);
        showAlert('error', 'Gagal memuat data penilaian');
    }
}

function setupStatistics(stats) {
    const container = document.getElementById('statistics-cards');
    container.innerHTML = `
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Submissions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${stats.total_submissions}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Judgings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${stats.pending_judgings}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Judgings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${stats.completed_judgings}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function setupCompetitionFilter(competitions) {
    const select = document.getElementById('competition-filter');
    competitions.forEach(comp => {
        const option = document.createElement('option');
        option.value = comp.id;
        option.textContent = `${comp.name} (${comp.submissions_count} submissions)`;
        select.appendChild(option);
    });
}

function setupReminder(showReminder, message) {
    if (showReminder && message) {
        const alert = document.getElementById('reminder-alert');
        const messageSpan = document.getElementById('reminder-message');
        messageSpan.textContent = message;
        alert.classList.remove('d-none');
    }
}

function setupTable(config, data) {
    judgingTable = new Tabulator("#judging-table", {
        ...config,
        data: data,
        cellEdited: function(cell) {
            const row = cell.getRow();
            const field = cell.getField();
            const value = cell.getValue();
            
            if (field === 'score' || field === 'feedback') {
                saveScore(row.getData().id, row.getData().score, row.getData().feedback);
            }
        }
    });

    // Setup filters
    document.getElementById('competition-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('search-input').addEventListener('input', applyFilters);
}

function applyFilters() {
    const competitionFilter = document.getElementById('competition-filter').value;
    const statusFilter = document.getElementById('status-filter').value;
    const searchTerm = document.getElementById('search-input').value.toLowerCase();

    judgingTable.setFilter([
        {field: "competition_name", type: "like", value: competitionFilter ? competitionFilter : ""},
        {field: "status", type: "like", value: statusFilter},
        [
            {field: "participant_name", type: "like", value: searchTerm},
            {field: "title", type: "like", value: searchTerm}
        ]
    ]);
}

async function saveScore(submissionId, score, feedback) {
    if (!score || score < 0 || score > 100) return;

    try {
        const response = await fetch('/api/judging/score', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                submission_id: submissionId,
                score: parseFloat(score),
                feedback: feedback || ''
            })
        });

        const result = await response.json();
        
        if (result.success) {
            showAlert('success', 'Penilaian berhasil disimpan');
            refreshData();
        } else {
            showAlert('error', result.message || 'Gagal menyimpan penilaian');
        }
    } catch (error) {
        console.error('Error saving score:', error);
        showAlert('error', 'Terjadi kesalahan saat menyimpan penilaian');
    }
}

function quickScore(submissionId) {
    document.getElementById('modal-submission-id').value = submissionId;
    document.getElementById('modal-score').value = '';
    document.getElementById('modal-feedback').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('quickScoreModal'));
    modal.show();
}

async function saveQuickScore() {
    const submissionId = document.getElementById('modal-submission-id').value;
    const score = document.getElementById('modal-score').value;
    const feedback = document.getElementById('modal-feedback').value;

    if (!score || score < 0 || score > 100) {
        showAlert('error', 'Skor harus antara 0-100');
        return;
    }

    await saveScore(submissionId, score, feedback);
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('quickScoreModal'));
    modal.hide();
}

function viewSubmission(submissionId) {
    window.open(`/juri/submissions/${submissionId}`, '_blank');
}

function refreshData() {
    initializeJudgingForm();
}

function exportData() {
    judgingTable.download("csv", "judging_data.csv");
}

function showAlert(type, message) {
    // Simple alert implementation
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}

// Global functions for tabulator actions
window.viewSubmission = viewSubmission;
window.quickScore = quickScore;
</script>
@endpush
