@extends('layouts.peserta')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-credit-card-2-front fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Checkout Pembayaran</h4>
                            <p class="text-muted mb-0">{{ $registration->competition->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Detail Pendaftaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Peserta:</strong> {{ $registration->user->name }}</p>
                            <p><strong>Email:</strong> {{ $registration->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kompetisi:</strong> {{ $registration->competition->name }}</p>
                            <p><strong>Kategori:</strong> {{ ucfirst($registration->competition->category) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran
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

            <!-- Payment Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-credit-card me-2"></i>Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <div class="d-flex">
                            <i class="bi bi-info-circle me-3 fs-5"></i>
                            <div>
                                <h6 class="alert-heading mb-2">Metode Pembayaran Tersedia</h6>
                                <div class="mb-2">
                                    <span class="badge bg-primary me-1">Kartu Kredit/Debit</span>
                                    <span class="badge bg-success me-1">Transfer Bank</span>
                                    <span class="badge bg-warning me-1">E-Wallet</span>
                                    <span class="badge bg-info me-1">QRIS</span>
                                    <span class="badge bg-secondary me-1">Convenience Store</span>
                                </div>
                                <small class="text-muted">
                                    Klik tombol "Pay Now" untuk memilih metode pembayaran yang tersedia melalui Midtrans
                                </small>
                            </div>
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
                            <button type="button" class="btn btn-primary btn-lg" id="payButton" disabled>
                                <i class="bi bi-credit-card me-2"></i>Pay Now
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
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.alert-info {
    background-color: #e7f3ff;
    border-color: #b8daff;
    color: #004085;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
@endpush

@push('scripts')
<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Payment checkout page loaded');
    console.log('Midtrans Snap available:', typeof snap !== 'undefined');
    console.log('CSRF token:', document.querySelector('meta[name="csrf-token"]')?.content?.substring(0, 10) + '...');

    const agreeTerms = document.getElementById('agreeTerms');
    const payButton = document.getElementById('payButton');

    // Check if required elements exist
    if (!agreeTerms) {
        console.error('agreeTerms element not found');
        return;
    }
    if (!payButton) {
        console.error('payButton element not found');
        return;
    }

    // Enable/disable pay button based on terms agreement only
    function updatePayButton() {
        payButton.disabled = !agreeTerms.checked;
    }

    agreeTerms.addEventListener('change', updatePayButton);

    // Handle payment
    payButton.addEventListener('click', function() {
        if (!agreeTerms.checked) {
            alert('Harap setujui syarat dan ketentuan terlebih dahulu');
            return;
        }

        // Check if Midtrans Snap is loaded
        if (typeof snap === 'undefined') {
            alert('Sistem pembayaran belum siap. Silakan refresh halaman dan coba lagi.');
            console.error('Midtrans Snap not loaded');
            return;
        }

        console.log('Starting payment process...');
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Process payment with auto-detect (no payment method selection needed)
        const formData = new FormData();

        fetch(`{{ route('payment.process', $registration) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Payment response data:', data);

            if (data.success && data.snap_token) {
                console.log('Payment response successful, opening Midtrans Snap...');
                console.log('Snap token received:', data.snap_token.substring(0, 20) + '...');

                // Check if snap is available
                if (typeof snap === 'undefined') {
                    throw new Error('Midtrans Snap tidak tersedia. Silakan refresh halaman.');
                }

                // Open Midtrans Snap
                console.log('Calling snap.pay()...');
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        alert('Pembayaran berhasil! Anda akan diarahkan ke halaman konfirmasi.');
                        // Use payment ID from response, not registration ID
                        if (data.payment_id) {
                            window.location.href = `/payment/finish/${data.payment_id}`;
                        } else {
                            window.location.href = `{{ route('payment.finish', $registration) }}`;
                        }
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        alert('Pembayaran sedang diproses. Anda akan diarahkan ke halaman status.');
                        // Use payment ID from response, not registration ID
                        if (data.payment_id) {
                            window.location.href = `/payment/status/${data.payment_id}`;
                        } else {
                            window.location.href = `{{ route('payment.status', $registration) }}`;
                        }
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        alert('Terjadi kesalahan dalam pembayaran: ' + (result.status_message || 'Unknown error'));
                        // Use payment ID from response, not registration ID
                        if (data.payment_id) {
                            window.location.href = `/payment/error/${data.payment_id}`;
                        } else {
                            window.location.href = `{{ route('payment.error', $registration) }}`;
                        }
                    },
                    onClose: function() {
                        console.log('Payment popup closed by user');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Pay Now';
                    }
                });
            } else if (data.success && !data.snap_token) {
                throw new Error('Token pembayaran tidak ditemukan dalam response. Silakan coba lagi.');
            } else {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }
        })
        .catch(error => {
            console.error('Payment error:', error);

            // Show detailed error message
            let errorMessage = 'Terjadi kesalahan sistem: ' + error.message;
            if (error.message.includes('403')) {
                errorMessage = 'Akses ditolak. Silakan login ulang.';
            } else if (error.message.includes('404')) {
                errorMessage = 'Halaman tidak ditemukan. Silakan refresh halaman.';
            } else if (error.message.includes('500')) {
                errorMessage = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
            }

            alert(errorMessage);
            payButton.disabled = false;
            payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Pay Now';
        });
    });

    // Initialize pay button state
    updatePayButton();
});
</script>
@endpush
