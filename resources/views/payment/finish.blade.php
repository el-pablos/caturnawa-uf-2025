@extends('layouts.peserta')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-check-circle-fill me-2"></i>Pembayaran Berhasil!
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="text-success mt-4 mb-3">Selamat!</h2>
                    <p class="lead">Pembayaran Anda telah berhasil diproses.</p>
                    <p class="text-muted">Pendaftaran Anda untuk kompetisi <strong>{{ $registration->competition->name }}</strong> telah dikonfirmasi.</p>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mx-auto">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Detail Pembayaran</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td class="fw-semibold">Order ID:</td>
                                            <td>{{ $payment->order_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Jumlah:</td>
                                            <td class="fw-bold">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Metode:</td>
                                            <td>{{ $payment->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Waktu:</td>
                                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : now()->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-list-check me-2"></i>Langkah Selanjutnya
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">1</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Cek Email Konfirmasi</h6>
                                    <p class="text-muted mb-0">Email konfirmasi dan e-ticket akan dikirim ke {{ $registration->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">2</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Siapkan Karya</h6>
                                    <p class="text-muted mb-0">Mulai persiapkan karya Anda sesuai dengan ketentuan kompetisi</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">3</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Upload Submission</h6>
                                    <p class="text-muted mb-0">Upload karya Anda sebelum deadline submission</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="text-white fw-bold">4</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Pantau Perkembangan</h6>
                                    <p class="text-muted mb-0">Pantau status submission dan pengumuman melalui dashboard</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="alert alert-info mt-4">
                <div class="d-flex">
                    <i class="bi bi-info-circle me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading">Informasi Penting</h6>
                        <ul class="mb-0">
                            <li>Simpan bukti pembayaran ini untuk referensi</li>
                            <li>E-ticket akan dikirim melalui email dalam 5-10 menit</li>
                            <li>Jika ada pertanyaan, hubungi customer service kami</li>
                            <li>Deadline submission: {{ $registration->competition->submission_deadline ? $registration->competition->submission_deadline->format('d M Y H:i') : 'Akan diumumkan' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('peserta.registrations.show', $registration) }}" class="btn btn-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Pendaftaran
                        </a>
                        <a href="{{ route('peserta.submissions.index') }}" class="btn btn-success">
                            <i class="bi bi-upload me-1"></i>Upload Karya
                        </a>
                        <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}
</style>
@endpush

@push('scripts')
<script>
// Confetti animation (optional)
document.addEventListener('DOMContentLoaded', function() {
    // Simple celebration effect
    setTimeout(() => {
        console.log('🎉 Pembayaran berhasil! Selamat!');
    }, 500);
});
</script>
@endpush
