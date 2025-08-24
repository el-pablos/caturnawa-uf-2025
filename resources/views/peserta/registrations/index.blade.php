@extends('layouts.peserta')

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
            <i class="bi bi-plus-circle"></i> Register New Competition
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Registrations Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-clipboard-check"></i> Registration History
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
                                        @if($registration->status === 'confirmed' || $registration->status === 'paid')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($registration->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($registration->status === 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @elseif($registration->status === 'expired')
                                            <span class="badge bg-secondary">Expired</span>
                                        @else
                                            <span class="badge bg-info">{{ ucfirst($registration->status) }}</span>
                                        @endif
                                    </td>
                                    <td id="payment-status-{{ $registration->id }}">
                                        @if($registration->payment)
                                            @if($registration->payment->is_confirmed)
                                                <span class="badge bg-success">Dikonfirmasi</span>
                                            @elseif($registration->payment->isSuccess())
                                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                            @elseif($registration->payment->isPending())
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($registration->payment->transaction_status) }}</span>
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
                                               class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                                <span class="d-none d-md-inline ms-1">Detail</span>
                                            </a>

                                            @if($registration->status === 'pending')
                                                <a href="{{ route('payment.checkout', $registration) }}"
                                                   class="btn btn-sm btn-outline-success" title="Bayar Sekarang">
                                                    <i class="bi bi-credit-card"></i>
                                                    <span class="d-none d-md-inline ms-1">Bayar</span>
                                                </a>
                                            @endif

                                            @if($registration->status === 'confirmed' || $registration->status === 'paid')
                                                <a href="{{ route('peserta.submissions.create', ['registration' => $registration->id]) }}"
                                                   class="btn btn-sm btn-outline-success" title="Upload Karya">
                                                    <i class="bi bi-upload"></i>
                                                    <span class="d-none d-md-inline ms-1">Upload Karya</span>
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
                    <i class="bi bi-clipboard-x fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Registrations Yet</h5>
                    <p class="text-muted">You haven't registered for any competitions yet.</p>
                    <a href="{{ route('peserta.competitions.index') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Register for Competition
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
    
    // Auto-refresh payment status every 30 seconds
    setInterval(function() {
        checkPaymentStatusUpdates();
    }, 30000);
});

// Function to check for payment status updates
function checkPaymentStatusUpdates() {
    const registrationIds = [];
    $('[id^="payment-status-"]').each(function() {
        const id = $(this).attr('id').replace('payment-status-', '');
        registrationIds.push(id);
    });
    
    if (registrationIds.length > 0) {
        $.ajax({
            url: '{{ route("peserta.registrations.check-payment-status") }}',
            method: 'POST',
            data: {
                registration_ids: registrationIds,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    response.data.forEach(function(registration) {
                        updatePaymentStatusUI(registration.id, registration.payment_status, registration.payment_confirmed);
                    });
                }
            },
            error: function() {
                // Silently fail - don't show error for background updates
            }
        });
    }
}

// Function to update payment status UI
function updatePaymentStatusUI(registrationId, paymentStatus, isConfirmed) {
    const statusCell = $('#payment-status-' + registrationId);
    let badgeHtml = '';
    
    if (isConfirmed) {
        badgeHtml = '<span class="badge bg-success">Dikonfirmasi</span>';
    } else if (paymentStatus === 'settlement' || paymentStatus === 'capture') {
        badgeHtml = '<span class="badge bg-warning">Menunggu Konfirmasi</span>';
    } else if (paymentStatus === 'pending') {
        badgeHtml = '<span class="badge bg-warning">Pending</span>';
    } else if (paymentStatus) {
        const statusText = paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1);
        badgeHtml = '<span class="badge bg-danger">' + statusText + '</span>';
    } else {
        badgeHtml = '<span class="badge bg-secondary">No Payment</span>';
    }
    
    if (statusCell.html() !== badgeHtml) {
        statusCell.html(badgeHtml);
        // Add visual feedback
        statusCell.addClass('bg-info').delay(2000).queue(function() {
            $(this).removeClass('bg-info').dequeue();
        });
    }
}
</script>
@endpush
