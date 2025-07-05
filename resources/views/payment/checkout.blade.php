@extends('layouts.peserta')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran Pendaftaran')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Payment Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Pembayaran Pendaftaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6 class="fw-bold">{{ $registration->competition->name }}</h6>
                            <p class="text-muted mb-2">{{ $registration->competition->category }}</p>
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <small class="text-muted">Nomor Pendaftaran</small>
                                    <div class="fw-semibold">{{ $registration->registration_number }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">Nama Peserta</small>
                                    <div class="fw-semibold">{{ $registration->display_name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="h4 text-primary mb-0">
                                Rp {{ number_format($registration->amount, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">Biaya Pendaftaran</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Detail Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Biaya Pendaftaran</td>
                                    <td class="text-end">Rp {{ number_format($registration->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Admin</td>
                                    <td class="text-end">Rp 0</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-bold">Total Pembayaran</td>
                                    <td class="text-end fw-bold h5 text-primary">
                                        Rp {{ number_format($registration->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-wallet2 me-2"></i>Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="payment-method-card p-3 border rounded text-center" data-method="credit_card">
                                <i class="bi bi-credit-card fs-2 text-primary mb-2"></i>
                                <div class="fw-semibold">Kartu Kredit/Debit</div>
                                <small class="text-muted">Visa, Mastercard, JCB</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-method-card p-3 border rounded text-center" data-method="bank_transfer">
                                <i class="bi bi-bank fs-2 text-success mb-2"></i>
                                <div class="fw-semibold">Transfer Bank</div>
                                <small class="text-muted">BCA, BNI, BRI, Mandiri</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-method-card p-3 border rounded text-center" data-method="ewallet">
                                <i class="bi bi-phone fs-2 text-warning mb-2"></i>
                                <div class="fw-semibold">E-Wallet</div>
                                <small class="text-muted">GoPay, ShopeePay</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="payment-method-card p-3 border rounded text-center" data-method="qris">
                                <i class="bi bi-qr-code fs-2 text-info mb-2"></i>
                                <div class="fw-semibold">QRIS</div>
                                <small class="text-muted">Scan QR Code</small>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Payment Method Info -->
                    <div id="selectedMethodInfo" class="mt-3 p-3 bg-light rounded" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Metode Terpilih:</strong> <span id="selectedMethodName">-</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearPaymentMethod()">
                                <i class="bi bi-arrow-left me-1"></i>Ganti Metode
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    Saya setuju dengan <a href="#" class="text-primary">syarat dan ketentuan</a> yang berlaku
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('peserta.competitions.show', $registration->competition) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="button" class="btn btn-primary" id="payButton" disabled>
                                <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="alert alert-info mt-4">
                <div class="d-flex">
                    <i class="bi bi-info-circle me-3 fs-5"></i>
                    <div>
                        <h6 class="alert-heading">Informasi Pembayaran</h6>
                        <ul class="mb-0">
                            <li>Pembayaran akan diproses secara real-time</li>
                            <li>Setelah pembayaran berhasil, Anda akan menerima konfirmasi via email</li>
                            <li>Jika mengalami kendala, hubungi customer service kami</li>
                            <li>Pembayaran yang sudah berhasil tidak dapat dibatalkan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.payment-method-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.payment-method-card:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

.payment-method-card.selected {
    border-color: #0d6efd !important;
    background-color: #e7f1ff;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
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
@if(config('midtrans.is_production'))
<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const agreeTerms = document.getElementById('agreeTerms');
    const payButton = document.getElementById('payButton');
    let selectedPaymentMethod = null;

    // Enable/disable pay button based on terms agreement and payment method
    function updatePayButton() {
        payButton.disabled = !agreeTerms.checked || !selectedPaymentMethod;
    }

    agreeTerms.addEventListener('change', updatePayButton);

    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method-card');
    const selectedMethodInfo = document.getElementById('selectedMethodInfo');
    const selectedMethodName = document.getElementById('selectedMethodName');

    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove previous selection
            paymentMethods.forEach(m => m.classList.remove('selected'));

            // Add selection to clicked method
            this.classList.add('selected');

            // Get method info
            selectedPaymentMethod = this.getAttribute('data-method');
            const methodName = this.querySelector('.fw-semibold').textContent;

            // Show selected method info
            selectedMethodName.textContent = methodName;
            selectedMethodInfo.style.display = 'block';

            // Update pay button
            updatePayButton();
        });
    });

    // Handle payment
    payButton.addEventListener('click', function() {
        if (!agreeTerms.checked) {
            alert('Harap setujui syarat dan ketentuan terlebih dahulu');
            return;
        }

        if (!selectedPaymentMethod) {
            alert('Harap pilih metode pembayaran terlebih dahulu');
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

        // Process payment with selected method
        const formData = new FormData();
        formData.append('payment_method', selectedPaymentMethod);

        fetch(`{{ route('payment.process', $registration) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.snap_token) {
                // Open Midtrans Snap
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = `{{ route('payment.finish', $registration) }}`;
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = `{{ route('payment.status', $registration) }}`;
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        window.location.href = `{{ route('payment.error', $registration) }}`;
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
                    }
                });
            } else {
                throw new Error(data.message || 'Gagal mendapatkan token pembayaran');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);
            alert('Terjadi kesalahan sistem');
            payButton.disabled = false;
            payButton.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
        });
    });

    // Initialize pay button state
    updatePayButton();
});

// Function to clear payment method selection
function clearPaymentMethod() {
    const paymentMethods = document.querySelectorAll('.payment-method-card');
    const selectedMethodInfo = document.getElementById('selectedMethodInfo');
    const payButton = document.getElementById('payButton');

    // Remove all selections
    paymentMethods.forEach(m => m.classList.remove('selected'));

    // Hide selected method info
    selectedMethodInfo.style.display = 'none';

    // Reset selected method
    selectedPaymentMethod = null;

    // Update pay button
    payButton.disabled = true;
}
</script>
@endpush
