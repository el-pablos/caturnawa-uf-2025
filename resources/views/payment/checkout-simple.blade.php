@extends('layouts.peserta')

@section('title', 'Pembayaran')
@section('page-title', 'Checkout')

@push('styles')
<style>
    .payment-timer {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
    }
    
    .timer-display {
        font-size: 2.5rem;
        font-weight: bold;
        font-family: 'Courier New', monospace;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .order-summary {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 24px;
        position: sticky;
        top: 20px;
    }
    
    .payment-security {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        font-size: 0.9rem;
    }
    
    .btn-pay-now {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 8px;
        padding: 16px 32px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .btn-pay-now:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        background: linear-gradient(135deg, #218838, #1e7e34);
    }
    
    .price-breakdown {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    .competition-info {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .payment-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    @media (max-width: 768px) {
        .timer-display {
            font-size: 2rem;
        }
        
        .order-summary {
            position: static;
            margin-top: 24px;
        }
    }
</style>
@endpush

@push('scripts')
<!-- Midtrans Snap JS -->
<script type="text/javascript" 
        src="{{ $globalConfig['midtrans_production'] ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $globalConfig['midtrans_client_key'] }}"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Payment Timer -->
    <div class="payment-timer" id="paymentTimer">
        <div class="mb-2">
            <i class="bi bi-clock-history fs-4"></i>
        </div>
        <div>Selesaikan pembayaran dalam</div>
        <div class="timer-display" id="timerDisplay">15:00</div>
        <small>Waktu akan otomatis diperpanjang jika diperlukan</small>
    </div>

    <div class="row">
        <!-- Payment Section -->
        <div class="col-lg-8">
            <!-- Competition Info -->
            <div class="competition-info">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1">{{ $registration->competition->name }}</h5>
                        <p class="text-muted mb-2">{{ $registration->competition->category }}</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Nomor Pendaftaran</small>
                                <span class="fw-semibold">{{ $registration->registration_number }}</span>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block">Nama Peserta</small>
                                <span class="fw-semibold">{{ $registration->user->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($registration->competition->image)
                            <img src="{{ $registration->competition->image_url }}" 
                                 alt="{{ $registration->competition->name }}" 
                                 class="img-fluid rounded" style="max-height: 80px;">
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="payment-card">
                <div class="text-center mb-4">
                    <h6 class="mb-3">
                        <i class="bi bi-wallet2 me-2 text-primary"></i>
                        Pembayaran Aman dengan Midtrans
                    </h6>
                    <p class="text-muted mb-4">
                        Sistem akan mendeteksi metode pembayaran yang tersedia secara otomatis.<br>
                        Pilihan tersedia: Virtual Account, E-Wallet (GoPay, OVO, Dana), QRIS, Kartu Kredit/Debit
                    </p>
                </div>

                <form id="paymentForm">
                    <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                    
                    <!-- Payment Security Info -->
                    <div class="payment-security mb-4">
                        <i class="bi bi-shield-check me-2"></i>
                        Pembayaran aman dan terenkripsi dengan teknologi SSL
                    </div>

                    <!-- Pay Now Button -->
                    <button type="button" id="payNowButton" class="btn btn-pay-now w-100">
                        <i class="bi bi-wallet me-2"></i>
                        Bayar Sekarang - Rp {{ number_format($registration->amount, 0, ',', '.') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="order-summary">
                <h6 class="mb-3">
                    <i class="bi bi-receipt me-2"></i>
                    Ringkasan Pesanan
                </h6>

                <!-- Price Breakdown -->
                <div class="price-breakdown">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Pendaftaran</span>
                        <span>Rp {{ number_format($registration->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Biaya Admin</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Diskon</span>
                        <span class="text-success">
                            @if($registration->pricing_phase === 'early_bird')
                                -Rp {{ number_format($registration->original_price - $registration->amount, 0, ',', '.') }}
                            @else
                                Rp 0
                            @endif
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ number_format($registration->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="mb-2">
                        <i class="bi bi-headset me-2"></i>
                        Butuh Bantuan?
                    </h6>
                    <p class="small text-muted mb-2">
                        Tim customer service kami siap membantu Anda 24/7
                    </p>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/{{ $registration->competition->contact_person_whatsapp ?? '6281234567890' }}" 
                           class="btn btn-sm btn-outline-success" target="_blank">
                            <i class="bi bi-whatsapp me-1"></i>
                            WhatsApp Support
                        </a>
                    </div>
                </div>

                <!-- Payment Guarantee -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-fill-check text-success me-1"></i>
                        100% Aman & Terpercaya
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Payment Method Modal -->
<div class="modal fade" id="changePaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Ganti Metode Pembayaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengganti metode pembayaran? Transaksi sebelumnya akan dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmChangePayment">Ya, Ganti</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timer functionality
    let timeLeft = 15 * 60; // 15 minutes in seconds
    const timerDisplay = document.getElementById('timerDisplay');
    const paymentTimer = document.getElementById('paymentTimer');
    
    function updateTimer() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 300) { // 5 minutes left
            paymentTimer.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
        }
        
        if (timeLeft <= 0) {
            // Extend timer by 5 minutes
            timeLeft = 5 * 60;
            paymentTimer.style.background = 'linear-gradient(135deg, #ffc107, #e0a800)';
            
            // Show notification
            if (Notification.permission === 'granted') {
                new Notification('Waktu Pembayaran Diperpanjang', {
                    body: 'Waktu pembayaran Anda telah diperpanjang 5 menit lagi.',
                    icon: '/favicon.ico'
                });
            }
        }
        
        timeLeft--;
    }
    
    // Request notification permission
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
        Notification.requestPermission();
    }
    
    // Start timer
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
    
    // Pay Now button functionality
    const payNowButton = document.getElementById('payNowButton');
    
    payNowButton.addEventListener('click', function() {
        // Show loading
        const originalText = this.innerHTML;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses Pembayaran...';
        
        // Process payment
        const formData = new FormData(document.getElementById('paymentForm'));
        
        fetch(`{{ route('payment.process', $registration) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment response:', data);
            
            if (data.success && data.snap_token) {
                // Open Midtrans Snap popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = `{{ url('payment/finish') }}/${data.payment_id}`;
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = `{{ url('payment/status') }}/${data.payment_id}`;
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        window.location.href = `{{ url('payment/error') }}/${data.payment_id}`;
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        // Re-enable button when popup is closed
                        payNowButton.disabled = false;
                        payNowButton.innerHTML = originalText;
                    }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                this.disabled = false;
                this.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            this.disabled = false;
            this.innerHTML = originalText;
        });
    });
    
    // Cleanup timer on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });
});
</script>
@endpush