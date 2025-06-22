@extends('layouts.app')

@section('title', 'My Registrations')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">My Registrations</h1>
            <p class="text-muted">Manage your competition registrations</p>
        </div>
        <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Register New Competition
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

    <!-- Registrations Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Registration History
            </h6>
        </div>
        <div class="card-body">
            @if($registrations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="registrationsTable">
                        <thead>
                            <tr>
                                <th>Registration #</th>
                                <th>Competition</th>
                                <th>Category</th>
                                <th>Team Name</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Registered At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $registration->registration_number }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $registration->competition->name }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($registration->competition->category) }}</span>
                                    </td>
                                    <td>
                                        {{ $registration->team_name ?? '-' }}
                                    </td>
                                    <td>
                                        @if($registration->status === 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($registration->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($registration->payment)
                                            @if($registration->payment->status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($registration->payment->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($registration->payment->status) }}</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">No Payment</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $registration->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('peserta.registrations.show', $registration) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($registration->status === 'pending')
                                                <a href="{{ route('payment.checkout', $registration) }}" 
                                                   class="btn btn-sm btn-outline-success" title="Pay Now">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif
                                            
                                            @if($registration->status === 'confirmed')
                                                <a href="{{ route('peserta.registrations.ticket', $registration) }}" 
                                                   class="btn btn-sm btn-outline-info" title="Download Ticket">
                                                    <i class="fas fa-ticket-alt"></i>
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
                    {{ $registrations->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Registrations Yet</h5>
                    <p class="text-muted">You haven't registered for any competitions yet.</p>
                    <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Register for Competition
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
    $('#registrationsTable').DataTable({
        "pageLength": 10,
        "responsive": true,
        "order": [[ 6, "desc" ]], // Order by registered date
        "columnDefs": [
            { "orderable": false, "targets": [7] } // Disable ordering for actions column
        ]
    });
});
</script>
@endpush
