@extends('layouts.peserta')

@section('title', 'My Submissions')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">My Submissions</h1>
            <p class="text-muted">Manage your competition submissions</p>
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

    <!-- Submissions Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-upload"></i> Submission History
            </h6>
        </div>
        <div class="card-body">
            @if($submissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="submissionsTable">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Competition</th>
                                <th>Registration #</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $submission)
                                <tr>
                                    <td>
                                        <strong>{{ $submission->title }}</strong>
                                    </td>
                                    <td>
                                        {{ $submission->registration->competition->name }}
                                        <br>
                                        <small class="text-muted">{{ ucfirst($submission->registration->competition->category) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $submission->registration->registration_number }}</span>
                                    </td>
                                    <td>
                                        @if($submission->status === 'submitted')
                                            <span class="badge bg-success">Submitted</span>
                                        @elseif($submission->status === 'draft')
                                            <span class="badge bg-warning">Draft</span>
                                        @elseif($submission->status === 'graded')
                                            <span class="badge bg-info">Graded</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($submission->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td>
                                        {{ $submission->updated_at->format('d M Y, H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('peserta.submissions.show', $submission) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($submission->status === 'draft')
                                                <a href="{{ route('peserta.submissions.edit', $submission) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $submissions->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-upload fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Submissions Yet</h5>
                    <p class="text-muted">You haven't submitted any work yet. Check your confirmed registrations to start submitting.</p>
                    <a href="{{ route('peserta.registrations.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> View My Registrations
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#submissionsTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "order": [[ 5, "desc" ]], // Order by last updated
        "columnDefs": [
            { "orderable": false, "targets": [6] } // Disable ordering for actions column
        ]
    });
});
</script>
@endpush
