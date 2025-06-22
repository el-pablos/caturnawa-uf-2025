@extends('layouts.peserta')

@section('title', 'Registration Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Registration Details</h1>
            <p class="text-muted">{{ $registration->registration_number }}</p>
        </div>
        <a href="{{ route('peserta.registrations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Registrations
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

    <div class="row">
        <!-- Registration Information -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Registration Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Competition Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Competition:</strong></td>
                                    <td>{{ $registration->competition->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td><span class="badge bg-info">{{ ucfirst($registration->competition->category) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>{{ $registration->competition->is_team ? 'Team' : 'Individual' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Registration #:</strong></td>
                                    <td><span class="badge bg-secondary">{{ $registration->registration_number }}</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status & Dates</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($registration->status === 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif($registration->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Registered:</strong></td>
                                    <td>{{ $registration->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @if($registration->confirmed_at)
                                <tr>
                                    <td><strong>Confirmed:</strong></td>
                                    <td>{{ $registration->confirmed_at->format('d M Y, H:i') }}</td>
                                </tr>
                                @endif
                                @if($registration->team_name)
                                <tr>
                                    <td><strong>Team Name:</strong></td>
                                    <td>{{ $registration->team_name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($registration->teamMembers->count() > 0)
                        <hr>
                        <h6 class="text-muted">Team Members</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registration->teamMembers as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>{{ $member->phone }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs"></i> Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($registration->status === 'pending')
                        <div class="d-grid gap-2">
                            <a href="{{ route('payment.checkout', $registration) }}" class="btn btn-success">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </a>
                            <button class="btn btn-danger" onclick="cancelRegistration()">
                                <i class="fas fa-times"></i> Cancel Registration
                            </button>
                        </div>
                    @elseif($registration->status === 'confirmed')
                        <div class="d-grid gap-2">
                            <a href="{{ route('peserta.registrations.ticket', $registration) }}" class="btn btn-info">
                                <i class="fas fa-ticket-alt"></i> Download Ticket
                            </a>
                            @if($registration->competition->status === 'active')
                                <a href="{{ route('peserta.submissions.create', $registration) }}" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Submit Work
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($registration->payment)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-credit-card"></i> Payment Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td><strong>Payment Code:</strong></td>
                                <td>{{ $registration->payment->payment_code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($registration->payment->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($registration->payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($registration->payment->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @if($registration->payment->paid_at)
                            <tr>
                                <td><strong>Paid At:</strong></td>
                                <td>{{ $registration->payment->paid_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                        
                        @if($registration->payment->status === 'paid')
                            <div class="d-grid">
                                <a href="{{ route('download.invoice', $registration->payment) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancel Registration Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this registration?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Registration</button>
                <form method="POST" action="{{ route('peserta.registrations.cancel', $registration) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Cancel Registration</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelRegistration() {
    $('#cancelModal').modal('show');
}
</script>
@endpush
