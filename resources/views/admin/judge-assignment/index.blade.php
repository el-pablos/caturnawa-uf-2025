@extends('layouts.admin')

@section('title', 'Judge Assignment Management')

@section('page-title', 'Judge Assignment Management')

@section('header-actions')
    @if($selectedCompetition)
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#autoAssignModal">
        <i class="bi bi-magic me-1"></i>Auto-Assign Judges
    </button>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Competition Selector -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.judge-assignment.index') }}">
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
    <!-- Judge Workload Statistics -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Judge Workload Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Judge Name</th>
                                <th class="text-center">Total Assignments</th>
                                <th class="text-center">Completed</th>
                                <th class="text-center">Pending</th>
                                <th class="text-center">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($workload as $stat)
                            <tr>
                                <td>{{ $stat['judge_name'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $stat['total_assignments'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $stat['completed_assignments'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $stat['pending_assignments'] }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $stat['completion_rate'] >= 80 ? 'bg-success' : ($stat['completion_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $stat['completion_rate'] }}%"
                                             aria-valuenow="{{ $stat['completion_rate'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $stat['completion_rate'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No judges available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Submissions and Assignments -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Submissions & Judge Assignments
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Submission Title</th>
                                <th>Participant</th>
                                <th>Assigned Judges</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($submissions as $submission)
                            <tr>
                                <td>
                                    <strong>{{ $submission->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $submission->created_at->format('d M Y') }}</small>
                                </td>
                                <td>{{ $submission->registration->user->name }}</td>
                                <td>
                                    @forelse($submission->judgings as $judging)
                                        <span class="badge {{ $judging->judged_at ? 'bg-success' : 'bg-secondary' }} me-1 mb-1">
                                            {{ $judging->jury->name }}
                                            @if($judging->judged_at)
                                                <i class="bi bi-check-circle ms-1"></i>
                                            @endif
                                            @if(!$judging->judged_at)
                                                <form method="POST" action="{{ route('admin.judge-assignment.unassign') }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('Remove this judge assignment?')">
                                                    @csrf
                                                    <input type="hidden" name="submission_id" value="{{ $submission->id }}">
                                                    <input type="hidden" name="judge_id" value="{{ $judging->jury_id }}">
                                                    <button type="submit" class="btn btn-link btn-sm p-0 text-white" style="font-size: 0.8rem;">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </span>
                                    @empty
                                        <span class="text-muted">No judges assigned</span>
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#assignJudgeModal"
                                            data-submission-id="{{ $submission->id }}"
                                            data-submission-title="{{ $submission->title }}">
                                        <i class="bi bi-person-plus me-1"></i>Assign Judge
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No submissions found</td>
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

<!-- Auto-Assign Modal -->
<div class="modal fade" id="autoAssignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.judge-assignment.auto-assign') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Auto-Assign Judges</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($selectedCompetition)
                    <input type="hidden" name="competition_id" value="{{ $selectedCompetition->id }}">
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        This will automatically assign judges to all unassigned submissions with workload balancing and conflict of interest detection.
                    </div>
                    
                    <div class="mb-3">
                        <label for="judges_per_submission" class="form-label">Judges per Submission</label>
                        <input type="number" class="form-control" id="judges_per_submission" 
                               name="judges_per_submission" value="3" min="1" max="5" required>
                        <small class="text-muted">Recommended: 3 judges per submission</small>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-magic me-1"></i>Auto-Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Judge Modal -->
<div class="modal fade" id="assignJudgeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.judge-assignment.assign') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Assign Judge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="submission_id" id="assign_submission_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Submission</label>
                        <input type="text" class="form-control" id="assign_submission_title" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="judge_id" class="form-label">Select Judge</label>
                        <select name="judge_id" id="judge_id" class="form-select" required>
                            <option value="">-- Select Judge --</option>
                            @foreach($judges as $judge)
                                <option value="{{ $judge->id }}">{{ $judge->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i>Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle assign judge modal
document.getElementById('assignJudgeModal').addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;
    const submissionId = button.getAttribute('data-submission-id');
    const submissionTitle = button.getAttribute('data-submission-title');
    
    document.getElementById('assign_submission_id').value = submissionId;
    document.getElementById('assign_submission_title').value = submissionTitle;
});
</script>
@endpush
@endsection

