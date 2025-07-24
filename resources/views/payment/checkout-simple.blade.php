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
    
    .payment-method-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid #e9ecef;
        background: white;
    }
    
    .payment-method-card:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
    
    .payment-method-card.selected {
        border-color: #0d6efd;
        background: #f8f9fa;
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
    
    .method-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f8f9fa;
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
        <!-- Payment Methods -->
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

            <!-- Payment Methods -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <h6 class="mb-0">
                        <i class="bi bi-wallet2 me-2 text-primary"></i>
                        Pilih Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <form id="paymentForm">
                        <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                        
                        <div class="row g-3 mb-4">
                            <!-- Virtual Account -->
                            <div class="col-md-6">
                                <div class="payment-method-card p-4 text-center" data-method="bank_transfer">
                                    <div class="method-icon bg-primary bg-opacity-10">
                                        <i class="bi bi-bank fs-4 text-primary"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Virtual Account</h6>
                                    <small class="text-muted">BCA, BNI, BRI, Mandiri, Permata</small>
                                    <div class="mt-2">
                                        <span class="badge bg-success">Paling Populer</span>
                                    </div>
                                </div>
                            </div>

                            <!-- E-Wallet -->
                            <div class="col-md-6">
                                <div class="payment-method-card p-4 text-center" data-method="ewallet">
                                    <div class="method-icon bg-warning bg-opacity-10">
                                        <i class="bi bi-phone fs-4 text-warning"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">E-Wallet</h6>
                                    <small class="text-muted">GoPay, OVO, Dana, ShopeePay</small>
                                    <div class="mt-2">
                                        <span class="badge bg-info">Instant</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Card -->
                            <div class="col-md-6">
                                <div class="payment-method-card p-4 text-center" data-method="credit_card">
                                    <div class="method-icon bg-success bg-opacity-10">
                                        <i class="bi bi-credit-card fs-4 text-success"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">Kartu Kredit/Debit</h6>
                                    <small class="text-muted">Visa, Mastercard, JCB</small>
                                    <div class="mt-2">
                                        <span class="badge bg-secondary">Secure</span>
                                    </div>
                                </div>
                            </div>

                            <!-- QRIS -->
                            <div class="col-md-6">
                                <div class="payment-method-card p-4 text-center" data-method="qris">
                                    <div class="method-icon bg-danger bg-opacity-10">
                                        <i class="bi bi-qr-code fs-4 text-danger"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1">QRIS</h6>
                                    <small class="text-muted">Scan QR dengan aplikasi bank</small>
                                    <div class="mt-2">
                                        <span class="badge bg-primary">Universal</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="payment_method" id="selectedPaymentMethod">

                        <!-- Payment Security Info -->
                        <div class="payment-security mb-3">
                            <i class="bi bi-shield-check me-2"></i>
                            Pembayaran aman dan terenkripsi dengan teknologi SSL
                        </div>

                        <!-- Continue Button -->
                        <button type="button" id="continuePayment" class="btn btn-pay-now w-100" disabled>
                            <i class="bi bi-lock-fill me-2"></i>
                            Bayar Sekarang - Rp {{ number_format($registration->amount, 0, ',', '.') }}
                        </button>
                    </form>
                </div>
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
    
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method-card');
    const selectedMethodInput = document.getElementById('selectedPaymentMethod');
    const continueButton = document.getElementById('continuePayment');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => m.classList.remove('selected'));
            
            // Add selected class to clicked method
            this.classList.add('selected');
            
            // Set the selected method value
            const methodValue = this.dataset.method;
            selectedMethodInput.value = methodValue;
            
            // Enable continue button
            continueButton.disabled = false;
            continueButton.innerHTML = `
                <i class="bi bi-lock-fill me-2"></i>
                Bayar dengan ${this.querySelector('h6').textContent} - Rp {{ number_format($registration->amount, 0, ',', '.') }}
            `;
        });
    });
    
    // Continue payment
    continueButton.addEventListener('click', function() {
        if (!selectedMethodInput.value) {
            alert('Silakan pilih metode pembayaran terlebih dahulu');
            return;
        }
        
        // Show loading
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
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
            if (data.success) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else if (data.snap_token) {
                    // Open Midtrans popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = `{{ url('payment/finish') }}/${data.payment_id}`;
                        },
                        onPending: function(result) {
                            window.location.href = `{{ url('payment/status') }}/${data.payment_id}`;
                        },
                        onError: function(result) {
                            window.location.href = `{{ url('payment/error') }}/${data.payment_id}`;
                        }
                    });
                }
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pembayaran');
                this.disabled = false;
                this.innerHTML = `
                    <i class="bi bi-lock-fill me-2"></i>
                    Bayar Sekarang - Rp {{ number_format($registration->amount, 0, ',', '.') }}
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            this.disabled = false;
            this.innerHTML = `
                <i class="bi bi-lock-fill me-2"></i>
                Bayar Sekarang - Rp {{ number_format($registration->amount, 0, ',', '.') }}
            `;
        });
    });
    
    // Cleanup timer on page unload
    window.addEventListener('beforeunload', function() {
        clearInterval(timerInterval);
    });
});
</script>
@endpush