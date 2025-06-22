@extends('layouts.app')

@section('title', 'Submission Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Submission Details</h1>
            <p class="text-muted">{{ $submission->title }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('peserta.submissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Submissions
            </a>
            @if($submission->isDraft())
                <a href="{{ route('peserta.submissions.edit', $submission) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Submission
                </a>
            @endif
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Submission Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Submission Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Title</h6>
                            <p class="fw-bold">{{ $submission->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-{{ $submission->status_class }}">{{ $submission->status_label }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted">Description</h6>
                        <p>{{ $submission->description }}</p>
                    </div>

                    @if($submission->submission_notes)
                        <div class="mb-3">
                            <h6 class="text-muted">Submission Notes</h6>
                            <p>{{ $submission->submission_notes }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="text-muted">Created At</h6>
                            <p>{{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if($submission->submitted_at)
                            <div class="col-md-4">
                                <h6 class="text-muted">Submitted At</h6>
                                <p>{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <h6 class="text-muted">Total File Size</h6>
                            <p>{{ $submission->file_size_formatted }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Files -->
            @if($submission->hasFiles())
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-paperclip"></i> Attached Files ({{ $submission->getFileCount() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Size</th>
                                        <th>Type</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($submission->getDownloadableFiles() as $file)
                                        <tr>
                                            <td>
                                                <i class="fas fa-file-alt me-2 text-muted"></i>
                                                {{ $file['original_name'] }}
                                            </td>
                                            <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                                            <td>
                                                <span class="badge bg-info">{{ strtoupper(pathinfo($file['original_name'], PATHINFO_EXTENSION)) }}</span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($file['uploaded_at'])->format('d M Y') }}</td>
                                            <td>
                                                @if($file['url'])
                                                    <a href="{{ $file['url'] }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tasks"></i> Submission Status
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="status-icon mb-2">
                            @if($submission->status === 'draft')
                                <i class="fas fa-edit fa-3x text-secondary"></i>
                            @elseif($submission->status === 'submitted')
                                <i class="fas fa-check-circle fa-3x text-primary"></i>
                            @elseif($submission->status === 'scored')
                                <i class="fas fa-star fa-3x text-success"></i>
                            @else
                                <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
                            @endif
                        </div>
                        <h5 class="text-{{ $submission->status_class }}">{{ $submission->status_label }}</h5>
                    </div>

                    @if($submission->isDraft())
                        <div class="alert alert-warning">
                            <small><i class="fas fa-info-circle me-1"></i>Your submission is still in draft mode. Remember to submit it before the deadline.</small>
                        </div>
                        
                        @if($submission->canBeSubmitted())
                            <div class="d-grid">
                                <button class="btn btn-success" onclick="submitSubmission()">
                                    <i class="fas fa-paper-plane"></i> Submit Final
                                </button>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <small>Cannot submit: {{ implode(', ', $submission->validateSubmission()) }}</small>
                            </div>
                        @endif
                    @endif

                    @php $deadlineInfo = $submission->getDeadlineInfo(); @endphp
                    @if($deadlineInfo['has_deadline'])
                        <hr>
                        <h6 class="text-muted">Deadline Information</h6>
                        <p class="mb-1"><strong>Deadline:</strong><br>{{ $deadlineInfo['deadline']->format('d M Y, H:i') }}</p>
                        @if($deadlineInfo['time_remaining'])
                            <p class="text-success"><i class="fas fa-clock me-1"></i>{{ $deadlineInfo['time_remaining'] }}</p>
                        @elseif($deadlineInfo['is_overdue'])
                            <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Overdue</p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Competition Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy"></i> Competition Details
                    </h6>
                </div>
                <div class="card-body">
                    <h6>{{ $submission->registration->competition->name }}</h6>
                    <p class="text-muted mb-2">{{ $submission->registration->competition->description }}</p>
                    <span class="badge bg-info">{{ ucfirst($submission->registration->competition->category) }}</span>
                    
                    <hr>
                    <p class="mb-1"><strong>Registration #:</strong><br>{{ $submission->registration->registration_number }}</p>
                    @if($submission->registration->team_name)
                        <p class="mb-1"><strong>Team:</strong><br>{{ $submission->registration->team_name }}</p>
                    @endif
                </div>
            </div>

            <!-- Scoring Info -->
            @if($submission->isFinal() && $submission->scores->count() > 0)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-star"></i> Scoring Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h3 class="text-primary">{{ number_format($submission->getAverageScore(), 2) }}</h3>
                            <small class="text-muted">Average Score</small>
                        </div>
                        
                        @if($submission->getRanking())
                            <div class="text-center mb-3">
                                <h4 class="text-success">#{{ $submission->getRanking() }}</h4>
                                <small class="text-muted">Current Ranking</small>
                            </div>
                        @endif

                        <p class="mb-1"><strong>Scores Received:</strong> {{ $submission->scores->count() }}</p>
                        @if($submission->isFullyScored())
                            <p class="text-success mb-0"><i class="fas fa-check-circle me-1"></i>Fully Scored</p>
                        @else
                            <p class="text-warning mb-0"><i class="fas fa-hourglass-half me-1"></i>Awaiting More Scores</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Submit Confirmation Modal -->
<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Final Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to submit this as your final submission?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Once submitted, you cannot edit or revert this submission.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('peserta.submissions.submit', $submission) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Yes, Submit Final</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function submitSubmission() {
    $('#submitModal').modal('show');
}
</script>
@endpush
