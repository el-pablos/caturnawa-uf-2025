@extends('layouts.admin')

@section('title', 'Certificate Management')

@section('page-title', 'Certificate Management')

@section('header-actions')
    @if($selectedCompetition)
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal">
        <i class="bi bi-file-earmark-pdf me-1"></i>Generate Bulk Certificates
    </button>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Competition Selector -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.certificates.index') }}">
                    <div class="row align-items-end">
                        <div class="col-md-10">
                            <label for="competition_id" class="form-label">Select Competition</label>
                            <select name="competition_id" id="competition_id" class="form-select" required>
                                <option value="">-- Select Competition --</option>
                                @foreach($competitions as $competition)
                                    <option value="{{ $competition->id }}" 
                                        {{ $selectedCompetition && $selectedCompetition->id == $competition->id ? 'selected' : '' }}>
                                        {{ $competition->name }} ({{ $competition->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i>Load
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($selectedCompetition)
    <!-- Registrations Table -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-award me-2"></i>Participants & Certificates
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th>Participant Name</th>
                                @if($selectedCompetition->is_team_competition)
                                <th>Team Name</th>
                                @endif
                                <th>Registration Number</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                            <tr>
                                <td>
                                    <input type="checkbox" name="registration_ids[]" 
                                           value="{{ $registration->id }}" 
                                           class="form-check-input registration-checkbox">
                                </td>
                                <td>{{ $registration->user->name }}</td>
                                @if($selectedCompetition->is_team_competition)
                                <td>{{ $registration->team_name ?? '-' }}</td>
                                @endif
                                <td>{{ $registration->registration_number }}</td>
                                <td>
                                    <span class="badge bg-success">{{ ucfirst($registration->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- Winner Certificate -->
                                        <button type="button" class="btn btn-outline-warning" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#winnerCertModal"
                                                data-registration-id="{{ $registration->id }}"
                                                data-participant-name="{{ $registration->user->name }}"
                                                title="Generate Winner Certificate">
                                            <i class="bi bi-trophy"></i>
                                        </button>
                                        
                                        <!-- Participation Certificate -->
                                        <a href="{{ route('admin.certificates.generate-participation', $registration) }}" 
                                           class="btn btn-outline-primary"
                                           title="Generate Participation Certificate">
                                            <i class="bi bi-award"></i>
                                        </a>
                                        
                                        <!-- Preview -->
                                        <button type="button" class="btn btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#previewModal"
                                                data-registration-id="{{ $registration->id }}"
                                                data-participant-name="{{ $registration->user->name }}"
                                                title="Preview Certificate">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $selectedCompetition->is_team_competition ? 6 : 5 }}" 
                                    class="text-center text-muted">
                                    No confirmed registrations found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Winner Certificate Modal -->
<div class="modal fade" id="winnerCertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="winnerCertForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Generate Winner Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Participant</label>
                        <input type="text" class="form-control" id="winner_participant_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rank" class="form-label">Rank / Position</label>
                        <select name="rank" id="rank" class="form-select" required>
                            <option value="">-- Select Rank --</option>
                            <option value="1">1st Place (Juara 1)</option>
                            <option value="2">2nd Place (Juara 2)</option>
                            <option value="3">3rd Place (Juara 3)</option>
                            <option value="4">Finalist</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-download me-1"></i>Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="GET" id="previewForm" target="_blank">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Participant</label>
                        <input type="text" class="form-control" id="preview_participant_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="preview_type" class="form-label">Certificate Type</label>
                        <select name="type" id="preview_type" class="form-select" required>
                            <option value="participation">Participation Certificate</option>
                            <option value="winner">Winner Certificate</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="preview_rank_container" style="display: none;">
                        <label for="preview_rank" class="form-label">Rank (for Winner Certificate)</label>
                        <select name="rank" id="preview_rank" class="form-select">
                            <option value="1">1st Place</option>
                            <option value="2">2nd Place</option>
                            <option value="3">3rd Place</option>
                            <option value="4">Finalist</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-eye me-1"></i>Preview
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Generate Modal -->
<div class="modal fade" id="bulkGenerateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.certificates.generate-bulk') }}">
                @csrf
                @if($selectedCompetition)
                <input type="hidden" name="competition_id" value="{{ $selectedCompetition->id }}">
                @endif
                
                <div class="modal-header">
                    <h5 class="modal-title">Generate Bulk Certificates</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Select participants from the table or generate for all confirmed registrations.
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulk_type" class="form-label">Certificate Type</label>
                        <select name="type" id="bulk_type" class="form-select" required>
                            <option value="participation">Participation Certificates</option>
                            <option value="winner">Winner Certificates</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Selected Participants</label>
                        <div id="selected-count" class="text-muted">
                            No participants selected (will generate for all)
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-zip me-1"></i>Generate ZIP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Select all checkboxes
document.getElementById('select-all')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.registration-checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateSelectedCount();
});

// Update selected count
document.querySelectorAll('.registration-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.registration-checkbox:checked').length;
    const countDiv = document.getElementById('selected-count');
    if (selected > 0) {
        countDiv.textContent = `${selected} participant(s) selected`;
        countDiv.classList.remove('text-muted');
        countDiv.classList.add('text-primary', 'fw-bold');
    } else {
        countDiv.textContent = 'No participants selected (will generate for all)';
        countDiv.classList.remove('text-primary', 'fw-bold');
        countDiv.classList.add('text-muted');
    }
}

// Winner certificate modal
document.getElementById('winnerCertModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const registrationId = button.getAttribute('data-registration-id');
    const participantName = button.getAttribute('data-participant-name');
    
    document.getElementById('winner_participant_name').value = participantName;
    document.getElementById('winnerCertForm').action = `/admin/certificates/${registrationId}/generate-winner`;
});

// Preview modal
document.getElementById('previewModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const registrationId = button.getAttribute('data-registration-id');
    const participantName = button.getAttribute('data-participant-name');
    
    document.getElementById('preview_participant_name').value = participantName;
    document.getElementById('previewForm').action = `/admin/certificates/${registrationId}/preview`;
});

// Show/hide rank field based on certificate type
document.getElementById('preview_type')?.addEventListener('change', function() {
    const rankContainer = document.getElementById('preview_rank_container');
    if (this.value === 'winner') {
        rankContainer.style.display = 'block';
    } else {
        rankContainer.style.display = 'none';
    }
});
</script>
@endpush
@endsection

