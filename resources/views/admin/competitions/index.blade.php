@extends('layouts.app')

@section('title', 'Manage Competitions')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Competitions Management</h1>
            <p class="text-muted">Manage all competitions and their settings</p>
        </div>
        <a href="{{ route('admin.competitions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Competition
        </a>
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

    <!-- Competitions Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> All Competitions
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="competitionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Registration</th>
                            <th>Participants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($competitions ?? [] as $competition)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold">{{ $competition->name }}</div>
                                            <div class="text-muted small">{{ Str::limit($competition->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($competition->category === 'biodiversity') bg-success
                                        @elseif($competition->category === 'health') bg-danger
                                        @else bg-primary @endif">
                                        {{ ucfirst($competition->category) }}
                                    </span>
                                </td>
                                <td>
                                    @if($competition->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($competition->status === 'inactive')
                                        <span class="badge bg-secondary">Inactive</span>
                                    @elseif($competition->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-info">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <div>Rp {{ number_format($competition->price, 0, ',', '.') }}</div>
                                    @if($competition->early_bird_price)
                                        <small class="text-muted">Early: Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        <div>Start: {{ $competition->registration_start ? $competition->registration_start->format('d M Y') : 'N/A' }}</div>
                                        <div>End: {{ $competition->registration_end ? $competition->registration_end->format('d M Y') : 'N/A' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $competition->registrations_count ?? 0 }}</span>
                                        @if($competition->max_participants)
                                            <span class="text-muted">/ {{ $competition->max_participants }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.competitions.show', $competition) }}" 
                                           class="btn btn-sm btn-outline-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.competitions.edit', $competition) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteCompetition({{ $competition->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Competitions Found</h5>
                                    <p class="text-muted">Start by creating your first competition.</p>
                                    <a href="{{ route('admin.competitions.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Create Competition
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Competition</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this competition?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone and will affect all related registrations.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#competitionsTable').DataTable({
        "responsive": true,
        "pageLength": 10,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [6] }
        ]
    });
});

function deleteCompetition(id) {
    $('#deleteForm').attr('action', '{{ route("admin.competitions.destroy", ":id") }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}
</script>
@endpush
