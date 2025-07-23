@extends('layouts.peserta')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@push('styles')
<style>
    .success-animation {
        animation: successPulse 2s ease-in-out infinite;
    }
    
    @keyframes successPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .success-card {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        margin-bottom: 30px;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        backdrop-filter: blur(10px);
    }
    
    .info-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .whatsapp-card {
        background: linear-gradient(135deg, #25D366, #128C7E);
        color: white;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .btn-whatsapp {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .btn-whatsapp:hover {
        background: white;
        color: #25D366;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .next-steps {
        background: #f8f9fa;
        border-left: 4px solid #007bff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .step-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 16px;
        padding: 12px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .step-number {
        width: 32px;
        height: 32px;
        background: #007bff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        margin-right: 16px;
        flex-shrink: 0;
    }
    
    .invoice-card {
        background: white;
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .btn-download-invoice {
        background: linear-gradient(135deg, #6f42c1, #5a2d91);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-download-invoice:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
        background: linear-gradient(135deg, #5a2d91, #4a1f7c);
        color: white;
    }
    
    .contact-person-card {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .social-proof {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Card -->
            <div class="success-card success-animation">
                <div class="success-icon">
                    <i class="bi bi-check-circle-fill fs-1"></i>
                </div>
                <h2 class="mb-3 fw-bold">Pembayaran Berhasil!</h2>
                <p class="mb-0 fs-5">
                    Terima kasih, <strong>{{ $registration->user->name }}</strong>.<br>
                    Pendaftaran Anda untuk <strong>{{ $registration->competition->name }}</strong> telah berhasil diproses.
                </p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Registration Details -->
                    <div class="info-card">
                        <h6 class="mb-3">
                            <i class="bi bi-info-circle me-2 text-primary"></i>
                            Detail Pendaftaran
                        </h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted d-block">Nomor Pendaftaran</small>
                                <strong class="text-primary">{{ $registration->registration_number }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Kompetisi</small>
                                <span>{{ $registration->competition->name }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Kategori</small>
                                <span>{{ $registration->competition->category }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Jumlah Dibayar</small>
                                <span class="fw-bold text-success">Rp {{ number_format($registration->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Tanggal Bayar</small>
                                <span>{{ $registration->confirmed_at ? $registration->confirmed_at->format('d M Y H:i') : now()->format('d M Y H:i') }}</span>
                            </div>
                            @if($registration->team_name)
                            <div class="col-12">
                                <small class="text-muted d-block">Nama Tim</small>
                                <span class="fw-semibold">{{ $registration->team_name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- WhatsApp Contact -->
                    @if($registration->competition->contact_person_whatsapp)
                    <div class="whatsapp-card">
                        <div class="mb-3">
                            <i class="bi bi-whatsapp fs-1"></i>
                        </div>
                        <h6 class="mb-2">Hubungi Contact Person</h6>
                        <p class="mb-3 opacity-90">
                            {{ $registration->competition->contact_person_name ?? 'Tim Panitia' }}<br>
                            <small>Siap membantu Anda 24/7</small>
                        </p>
                        <a href="https://wa.me/{{ $registration->competition->contact_person_whatsapp }}?text=Halo,%20saya%20sudah%20mendaftar%20{{ $registration->competition->name }}%20dengan%20nomor%20pendaftaran%20{{ $registration->registration_number }}.%20Mohon%20informasi%20selanjutnya." 
                           class="btn btn-whatsapp" target="_blank">
                            <i class="bi bi-whatsapp me-2"></i>
                            Chat WhatsApp
                        </a>
                    </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <!-- Download Invoice -->
                    <div class="invoice-card">
                        <div class="mb-3">
                            <i class="bi bi-receipt fs-1 text-muted"></i>
                        </div>
                        <h6 class="mb-2">Invoice Pembayaran</h6>
                        <p class="text-muted mb-3">
                            Unduh invoice resmi sebagai bukti pembayaran Anda
                        </p>
                        <a href="{{ route('payment.invoice', $registration) }}" 
                           class="btn btn-download-invoice" target="_blank">
                            <i class="bi bi-download me-2"></i>
                            Download Invoice PDF
                        </a>
                    </div>

                    <!-- Contact Person Info -->
                    @if($registration->competition->contact_person_name)
                    <div class="contact-person-card">
                        <h6 class="mb-2">
                            <i class="bi bi-person-check me-2"></i>
                            Contact Person
                        </h6>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-white bg-opacity-20 rounded-circle p-2">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $registration->competition->contact_person_name }}</div>
                                <small class="opacity-75">
                                    Koordinator {{ $registration->competition->name }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h6 class="mb-3 text-primary">
                    <i class="bi bi-list-check me-2"></i>
                    Langkah Selanjutnya
                </h6>
                
                <div class="step-item">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Bergabung dengan WhatsApp Group</strong><br>
                        <small class="text-muted">
                            Hubungi contact person untuk mendapatkan link grup WhatsApp peserta
                        </small>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Pantau Informasi Kompetisi</strong><br>
                        <small class="text-muted">
                            Cek dashboard secara berkala untuk update jadwal dan pengumuman penting
                        </small>
                    </div>
                </div>
                
                <div class="step-item">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Persiapkan Dokumen Tambahan</strong><br>
                        <small class="text-muted">
                            Lengkapi dokumen yang diperlukan sebelum deadline yang ditentukan
                        </small>
                    </div>
                </div>
                
                @if($registration->competition->submission_start)
                <div class="step-item">
                    <div class="step-number">4</div>
                    <div>
                        <strong>Upload Karya</strong><br>
                        <small class="text-muted">
                            Deadline upload: {{ $registration->competition->submission_end ? $registration->competition->submission_end->format('d M Y') : 'TBA' }}
                        </small>
                    </div>
                </div>
                @endif
            </div>

            <!-- Social Proof -->
            <div class="social-proof">
                <div class="mb-2">
                    <i class="bi bi-people-fill text-warning fs-4"></i>
                </div>
                <strong>{{ $registration->competition->paidRegistrations()->count() + 1 }} peserta</strong>
                telah mendaftar untuk kompetisi ini
            </div>

            <!-- Action Buttons -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <a href="{{ route('peserta.dashboard') }}" class="btn btn-primary w-100">
                        <i class="bi bi-house-door me-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-eye me-2"></i>
                        Lihat Detail Pendaftaran
                    </a>
                </div>
            </div>

            <!-- Competition Timeline -->
            @if($registration->competition->timeline)
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="bi bi-calendar-event me-2 text-primary"></i>
                    Timeline Kompetisi
                </h6>
                <div class="timeline">
                    @foreach($registration->competition->timeline as $event)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="bi {{ $event['icon'] ?? 'bi-circle' }} text-{{ $event['color'] ?? 'primary' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $event['title'] }}</div>
                            <small class="text-muted">{{ $event['date']->format('d M Y') }}</small>
                        </div>
                        <div>
                            @if($event['status'] === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif($event['status'] === 'active')
                                <span class="badge bg-primary">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Mendatang</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-scroll to success message
    setTimeout(() => {
        document.querySelector('.success-card').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }, 500);
    
    // Show browser notification
    if (Notification.permission === 'granted') {
        new Notification('Pembayaran Berhasil!', {
            body: 'Pendaftaran {{ $registration->competition->name }} Anda telah berhasil diproses.',
            icon: '/favicon.ico'
        });
    }
    
    // Add confetti effect
    if (typeof confetti !== 'undefined') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
});
</script>
@endpush