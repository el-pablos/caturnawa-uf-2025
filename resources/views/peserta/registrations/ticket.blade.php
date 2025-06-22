@extends('layouts.peserta')

@section('title', 'E-Ticket Kompetisi')
@section('page-title', 'E-Ticket Kompetisi')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-2"></i>Cetak Ticket
        </button>
        @if($registration->payment && $registration->payment->isSuccess())
        <a href="{{ route('payment.receipt', $registration->payment) }}" class="btn btn-outline-primary">
            <i class="bi bi-receipt me-2"></i>Download Struk
        </a>
        @endif
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- E-Ticket Card -->
        <div class="card border-0 shadow-lg" id="ticket-card">
            <div class="card-header bg-gradient-primary text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="bi bi-ticket-perforated me-2"></i>E-TICKET KOMPETISI
                </h3>
                <p class="mb-0 mt-2 opacity-75">{{ config('app.name') }}</p>
            </div>
            
            <div class="card-body p-5">
                <!-- Competition Info -->
                <div class="text-center mb-4">
                    <h2 class="text-primary mb-2">{{ $registration->competition->name }}</h2>
                    <span class="badge bg-primary fs-6 px-3 py-2">{{ $registration->competition->category }}</span>
                </div>
                
                <!-- Ticket Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="ticket-info">
                            <h6 class="text-muted mb-3">Informasi Peserta</h6>
                            <div class="info-item mb-2">
                                <strong>Nama:</strong> {{ $registration->user->name }}
                            </div>
                            <div class="info-item mb-2">
                                <strong>Email:</strong> {{ $registration->user->email }}
                            </div>
                            <div class="info-item mb-2">
                                <strong>Telepon:</strong> {{ $registration->phone ?: $registration->user->phone }}
                            </div>
                            @if($registration->institution)
                            <div class="info-item mb-2">
                                <strong>Institusi:</strong> {{ $registration->institution }}
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="ticket-info">
                            <h6 class="text-muted mb-3">Informasi Pendaftaran</h6>
                            <div class="info-item mb-2">
                                <strong>No. Registrasi:</strong> {{ $registration->registration_number }}
                            </div>
                            <div class="info-item mb-2">
                                <strong>Tanggal Daftar:</strong> {{ $registration->registered_at->format('d M Y H:i') }}
                            </div>
                            @if($registration->confirmed_at)
                            <div class="info-item mb-2">
                                <strong>Dikonfirmasi:</strong> {{ $registration->confirmed_at->format('d M Y H:i') }}
                            </div>
                            @endif
                            <div class="info-item mb-2">
                                <strong>Status:</strong> 
                                <span class="badge bg-success">{{ ucfirst($registration->status) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Team Members (if applicable) -->
                @if($registration->team_members && count($registration->team_members) > 0)
                <div class="mb-4">
                    <h6 class="text-muted mb-3">Anggota Tim</h6>
                    <div class="row">
                        @foreach($registration->team_members as $index => $member)
                        <div class="col-md-6 mb-2">
                            <div class="team-member-card p-3 bg-light rounded">
                                <strong>{{ $member['name'] }}</strong>
                                @if(isset($member['email']))
                                <br><small class="text-muted">{{ $member['email'] }}</small>
                                @endif
                                @if(isset($member['role']))
                                <br><small class="text-primary">{{ $member['role'] }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- QR Code Section -->
                <div class="text-center mb-4">
                    <div class="qr-code-section p-4 bg-light rounded">
                        <h6 class="text-muted mb-3">QR Code Check-in</h6>
                        @if($registration->qr_code)
                            <div class="qr-code-container mb-3">
                                <img src="data:image/png;base64,{{ base64_encode($registration->qr_code) }}" 
                                     alt="QR Code" class="qr-code-image">
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                QR Code akan tersedia setelah pembayaran dikonfirmasi
                            </div>
                        @endif
                        <p class="text-muted small mb-0">
                            Tunjukkan QR Code ini kepada panitia saat check-in kompetisi
                        </p>
                    </div>
                </div>
                
                <!-- Competition Schedule -->
                <div class="competition-schedule">
                    <h6 class="text-muted mb-3">Jadwal Kompetisi</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="schedule-item">
                                <strong>Mulai:</strong> 
                                {{ $registration->competition->competition_start ? $registration->competition->competition_start->format('d M Y H:i') : 'TBA' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="schedule-item">
                                <strong>Selesai:</strong> 
                                {{ $registration->competition->competition_end ? $registration->competition->competition_end->format('d M Y H:i') : 'TBA' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ticket Footer -->
            <div class="card-footer bg-light text-center py-3">
                <small class="text-muted">
                    Ticket ini adalah bukti sah pendaftaran kompetisi. Simpan dengan baik dan tunjukkan saat check-in.
                </small>
            </div>
        </div>
        
        <!-- Important Notes -->
        <div class="alert alert-info mt-4">
            <h6 class="alert-heading">
                <i class="bi bi-info-circle me-2"></i>Informasi Penting
            </h6>
            <ul class="mb-0">
                <li>Pastikan untuk membawa identitas diri yang valid saat check-in</li>
                <li>Datang minimal 30 menit sebelum kompetisi dimulai</li>
                <li>QR Code ini bersifat unik dan tidak dapat dipindahtangankan</li>
                <li>Hubungi panitia jika ada pertanyaan atau kendala</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.ticket-info .info-item {
    padding: 0.5rem 0;
    border-bottom: 1px dotted #dee2e6;
}

.ticket-info .info-item:last-child {
    border-bottom: none;
}

.team-member-card {
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.team-member-card:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.1);
}

.qr-code-image {
    max-width: 200px;
    height: auto;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    background: white;
}

.schedule-item {
    padding: 0.75rem;
    background: white;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    margin-bottom: 0.5rem;
}

/* Print Styles */
@media print {
    .btn, .alert, .card-header .opacity-75 {
        display: none !important;
    }
    
    .card {
        border: 2px solid #000 !important;
        box-shadow: none !important;
    }
    
    .bg-gradient-primary {
        background: #333 !important;
        color: white !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .qr-code-image {
        max-width: 150px;
    }
}

@media (max-width: 768px) {
    .card-body {
        padding: 2rem !important;
    }
    
    .qr-code-image {
        max-width: 150px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interactivity if needed
    console.log('E-Ticket loaded for registration:', '{{ $registration->registration_number }}');
});
</script>
@endpush
