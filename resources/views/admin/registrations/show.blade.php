@extends('layouts.admin')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        @if($registration->payment && $registration->payment->isSuccess())
        <a href="{{ route('payment.receipt', $registration->payment) }}" class="btn btn-primary">
            <i class="bi bi-receipt me-2"></i>Download Struk
        </a>
        @endif
        <a href="{{ route('peserta.registrations.ticket', $registration) }}" class="btn btn-success">
            <i class="bi bi-ticket-perforated me-2"></i>Lihat E-Ticket
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Registration Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-check me-2"></i>Informasi Pendaftaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">No. Registrasi:</td>
                                <td><code>{{ $registration->registration_number }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($registration->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Daftar:</td>
                                <td>{{ $registration->registered_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @if($registration->confirmed_at)
                            <tr>
                                <td class="fw-semibold">Tanggal Konfirmasi:</td>
                                <td>{{ $registration->confirmed_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Biaya Pendaftaran:</td>
                                <td class="fw-bold text-success">Rp {{ number_format($registration->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Institusi:</td>
                                <td>{{ $registration->institution ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Telepon:</td>
                                <td>{{ $registration->phone ?: $registration->user->phone }}</td>
                            </tr>
                            @if($registration->special_needs)
                            <tr>
                                <td class="fw-semibold">Kebutuhan Khusus:</td>
                                <td>{{ $registration->special_needs }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participant Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>Detail Peserta
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        @if($registration->user->profile_photo)
                            <img src="{{ asset('storage/' . $registration->user->profile_photo) }}" 
                                 class="img-fluid rounded-circle" alt="Profile Photo" style="max-width: 120px;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nama Lengkap:</td>
                                <td>{{ $registration->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td>{{ $registration->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Lahir:</td>
                                <td>{{ $registration->user->date_of_birth ? $registration->user->date_of_birth->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Jenis Kelamin:</td>
                                <td>{{ $registration->user->gender ? ucfirst($registration->user->gender) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alamat:</td>
                                <td>{{ $registration->user->address ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competition Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>Detail Kompetisi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nama Kompetisi:</td>
                                <td>{{ $registration->competition->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kategori:</td>
                                <td>{{ $registration->competition->category }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tipe:</td>
                                <td>{{ $registration->competition->is_team_competition ? 'Tim' : 'Individual' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Mulai Kompetisi:</td>
                                <td>{{ $registration->competition->competition_start ? $registration->competition->competition_start->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Selesai Kompetisi:</td>
                                <td>{{ $registration->competition->competition_end ? $registration->competition->competition_end->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Deadline Submission:</td>
                                <td>{{ $registration->competition->submission_deadline ? $registration->competition->submission_deadline->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members (if applicable) -->
        @if($registration->team_members && count($registration->team_members) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Anggota Tim
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($registration->team_members as $index => $member)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Anggota {{ $index + 1 }}</h6>
                                <p class="card-text">
                                    <strong>Nama:</strong> {{ $member['name'] }}<br>
                                    @if(isset($member['email']))
                                    <strong>Email:</strong> {{ $member['email'] }}<br>
                                    @endif
                                    @if(isset($member['student_id']))
                                    <strong>NIM/ID:</strong> {{ $member['student_id'] }}<br>
                                    @endif
                                    @if(isset($member['role']))
                                    <strong>Peran:</strong> {{ $member['role'] }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Registration Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Aksi Pendaftaran
                </h6>
            </div>
            <div class="card-body">
                @if($registration->status === 'pending')
                    <div class="d-grid gap-2">
                        <button class="btn btn-success" onclick="confirmRegistration('{{ $registration->id }}')">
                            <i class="bi bi-check-circle me-2"></i>Konfirmasi Pendaftaran
                        </button>
                        <button class="btn btn-danger" onclick="cancelRegistration('{{ $registration->id }}')">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Pendaftaran
                        </button>
                    </div>
                @elseif($registration->status === 'confirmed')
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Pendaftaran telah dikonfirmasi
                    </div>
                @else
                    <div class="alert alert-secondary">
                        <i class="bi bi-info-circle me-2"></i>
                        Status: {{ ucfirst($registration->status) }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Payment Information -->
        @if($registration->payment)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-semibold">Order ID:</td>
                        <td><code>{{ $registration->payment->order_id }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Status:</td>
                        <td>
                            <span class="badge bg-{{ $registration->payment->status_class }}">
                                {{ $registration->payment->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Jumlah:</td>
                        <td class="fw-bold">Rp {{ number_format($registration->payment->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($registration->payment->paid_at)
                    <tr>
                        <td class="fw-semibold">Dibayar:</td>
                        <td>{{ $registration->payment->paid_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
                <div class="d-grid">
                    <a href="{{ route('admin.payments.show', $registration->payment) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-2"></i>Lihat Detail Pembayaran
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Emergency Contact -->
        @if($registration->emergency_contact)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-telephone me-2"></i>Kontak Darurat
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-semibold">Nama:</td>
                        <td>{{ $registration->emergency_contact }}</td>
                    </tr>
                    @if($registration->emergency_phone)
                    <tr>
                        <td class="fw-semibold">Telepon:</td>
                        <td>{{ $registration->emergency_phone }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmRegistration(registrationId) {
    if (confirm('Apakah Anda yakin ingin mengkonfirmasi pendaftaran ini?')) {
        fetch(`/admin/registrations/${registrationId}/confirm`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengkonfirmasi pendaftaran');
        });
    }
}

function cancelRegistration(registrationId) {
    const reason = prompt('Masukkan alasan pembatalan:');
    if (reason) {
        fetch(`/admin/registrations/${registrationId}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat membatalkan pendaftaran');
        });
    }
}
</script>
@endpush
