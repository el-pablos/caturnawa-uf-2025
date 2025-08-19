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
            <i class="bi bi-arrow-left"></i> Back to Registrations
        </a>
    </div>


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
                                        @elseif($registration->status === 'paid')
                                            <span class="badge bg-info">Paid</span>
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
                                @if($registration->logo_instansi)
                                <tr>
                                    <td><strong>Logo Instansi:</strong></td>
                                    <td>
                                        <img src="{{ asset('storage/' . $registration->logo_instansi) }}"
                                             class="img-thumbnail" alt="Logo Instansi" style="max-width: 80px;">
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($registration->team_members && count($registration->team_members) > 0)
                        <hr>
                        <h6 class="text-muted">Anggota Tim</h6>
                        <div class="row">
                            @foreach($registration->team_members as $index => $member)
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                @if(isset($member['foto']) && $member['foto'])
                                                    <img src="{{ asset('storage/' . $member['foto']) }}"
                                                         class="rounded-circle" alt="Foto {{ $member['name'] }}"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">Peserta {{ $index + 1 }}</h6>
                                                <p class="mb-0 small">
                                                    <strong>{{ $member['name'] }}</strong><br>
                                                    @if(isset($member['email']))
                                                    <span class="text-muted">{{ $member['email'] }}</span><br>
                                                    @endif
                                                    @if(isset($member['phone']))
                                                    <span class="text-muted">{{ $member['phone'] }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
                        <i class="bi bi-gear"></i> Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($registration->status === 'pending')
                        <div class="d-grid gap-2">
                            <a href="{{ route('payment.checkout', $registration) }}" class="btn btn-success">
                                <i class="bi bi-credit-card"></i> Pay Now
                            </a>
                            <a href="{{ route('peserta.registrations.documents', $registration) }}" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload Documents
                            </a>
                            <button class="btn btn-danger" onclick="cancelRegistration()">
                                <i class="bi bi-x-circle"></i> Cancel Registration
                            </button>
                        </div>
                    @elseif($registration->status === 'paid')
                        <!-- Payment completed but awaiting admin confirmation -->
                        <div class="alert alert-info mb-3">
                            <h6><i class="bi bi-info-circle"></i> Payment Completed - Awaiting Confirmation</h6>
                            <p class="mb-2">Your payment has been processed successfully. Please confirm your registration by contacting our admin through WhatsApp.</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            @php
                                $whatsappMessage = urlencode("*KONFIRMASI PEMBAYARAN UNAS FEST 2025*\n\n" .
                                    "Nama: " . $registration->user->name . "\n" .
                                    "Email: " . $registration->user->email . "\n" .
                                    "Nomor Registrasi: " . $registration->registration_number . "\n" .
                                    "Kompetisi: " . $registration->competition->name . "\n" .
                                    "Kategori: " . ucfirst($registration->competition->category) . "\n" .
                                    ($registration->team_name ? "Nama Tim: " . $registration->team_name . "\n" : "") .
                                    "Institusi: " . ($registration->institution ?? $registration->user->institution ?? 'Tidak disebutkan') . "\n" .
                                    "Jumlah Pembayaran: Rp " . number_format($registration->amount, 0, ',', '.') . "\n" .
                                    "Tanggal Pembayaran: " . ($registration->payment ? $registration->payment->paid_at?->format('d M Y, H:i') : 'N/A') . "\n\n" .
                                    "Mohon konfirmasi pembayaran saya untuk dapat mengakses grup WhatsApp kompetisi dan upload karya.\n\n" .
                                    "Terima kasih!");
                                $whatsappLink = "https://wa.me/6285817378442?text=" . $whatsappMessage;
                            @endphp
                            
                            <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-success">
                                <i class="bi bi-whatsapp"></i> Confirm Payment via WhatsApp
                            </a>
                            
                            <a href="{{ route('download.unified-invoice', $registration) }}" class="btn btn-info" target="_blank">
                                <i class="bi bi-receipt"></i> Download Invoice
                            </a>
                            
                            <a href="{{ route('peserta.registrations.documents', $registration) }}" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark-text"></i> Manage Documents
                            </a>
                        </div>
                        
                    @elseif($registration->status === 'confirmed')
                        <!-- Admin confirmed registration -->
                        <div class="alert alert-success mb-3">
                            <h6><i class="bi bi-check-circle"></i> Registration Confirmed</h6>
                            <p class="mb-0">Your registration has been confirmed by admin. You can now access all competition features.</p>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('download.unified-invoice', $registration) }}" class="btn btn-info" target="_blank">
                                <i class="bi bi-receipt"></i> Download Invoice
                            </a>
                            
                            <a href="{{ route('peserta.registrations.documents', $registration) }}" class="btn btn-outline-primary">
                                <i class="bi bi-file-earmark-text"></i> Manage Documents
                            </a>
                            
                            @if($registration->competition->whatsapp_group_link)
                                <a href="{{ $registration->competition->whatsapp_group_link }}"
                                   target="_blank"
                                   class="btn btn-success">
                                    <i class="bi bi-whatsapp"></i> Join WhatsApp Group
                                </a>
                            @endif
                            
                            @if($registration->competition->status === 'active')
                                <a href="{{ route('peserta.submissions.create', $registration) }}" class="btn btn-primary">
                                    <i class="bi bi-cloud-upload"></i> Submit Work
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
                                <td>{{ $registration->payment->payment_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Order ID:</strong></td>
                                <td>{{ $registration->payment->order_id ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td>Rp {{ number_format($registration->payment->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Transaction Status:</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $registration->payment->transaction_status ?? 'N/A' }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($registration->payment->is_confirmed)
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($registration->payment->isSuccess())
                                        <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                    @elseif($registration->payment->isPending())
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
                        
                        <div class="d-grid gap-2">
                            @if($registration->payment->status === 'paid')
                                <a href="{{ route('download.unified-invoice', $registration) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-download"></i> Download Invoice
                                </a>
                            @endif

                            <!-- Refresh Payment Status Button -->
                            <form action="{{ route('peserta.registrations.refresh-payment', $registration) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-info w-100">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh Payment Status
                                </button>
                            </form>
                        </div>
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
