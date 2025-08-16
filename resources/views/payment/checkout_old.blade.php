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
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    transform: translateY(-2px);
    position: relative;
}

.payment-method-card.selected::after {
    content: '✓';
    position: absolute;
    top: 8px;
    right: 8px;
    background: #0d6efd;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
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
<script src="{{ \App\Helpers\MidtransHelper::getSnapJsUrl() }}" data-client-key="{{ \App\Helpers\MidtransHelper::getClientKey() }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if all required elements and scripts are loaded
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
            const newPaymentMethod = this.getAttribute('data-method');
            const methodName = this.querySelector('.fw-semibold').textContent;

            // Check if method changed
            if (selectedPaymentMethod && selectedPaymentMethod !== newPaymentMethod) {
                // Method changed, update via AJAX
                updatePaymentMethod(newPaymentMethod, methodName);
            } else {
                // First selection or same method
                selectedPaymentMethod = newPaymentMethod;
                selectedMethodName.textContent = methodName;
                selectedMethodInfo.style.display = 'block';
                updatePayButton();
            }
        });
    });

    // Function to update payment method via AJAX
    function updatePaymentMethod(newMethod, methodName) {
        // Show loading state
        const loadingHtml = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    <strong>Memperbarui metode pembayaran...</strong>
                </div>
            </div>
        `;
        selectedMethodInfo.innerHTML = loadingHtml;
        selectedMethodInfo.style.display = 'block';

        // Disable pay button during update
        payButton.disabled = true;

        const formData = new FormData();
        formData.append('payment_method', newMethod);

        fetch(`{{ route('payment.update-method', $registration) }}`, {
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
            if (data.success) {
                // Update successful
                selectedPaymentMethod = newMethod;
                selectedMethodName.textContent = methodName;

                // Show success message briefly
                const successHtml = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <strong>Metode Terpilih:</strong> <span>${methodName}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearPaymentMethod()">
                            <i class="bi bi-arrow-left me-1"></i>Ganti Metode
                        </button>
                    </div>
                `;
                selectedMethodInfo.innerHTML = successHtml;

                // Update pay button
                updatePayButton();

                // Show success notification
                showNotification('Metode pembayaran berhasil diperbarui!', 'success');
            } else {
                throw new Error(data.message || 'Gagal memperbarui metode pembayaran');
            }
        })
        .catch(error => {
            console.error('Payment method update error:', error);

            // Reset selection
            paymentMethods.forEach(m => m.classList.remove('selected'));
            selectedPaymentMethod = null;
            selectedMethodInfo.style.display = 'none';

            // Show error
            showNotification('Gagal memperbarui metode pembayaran: ' + error.message, 'error');
            updatePayButton();
        });
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Function to clear payment method selection
    window.clearPaymentMethod = function() {
        paymentMethods.forEach(m => m.classList.remove('selected'));
        selectedPaymentMethod = null;
        selectedMethodInfo.style.display = 'none';
        updatePayButton();
    };

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

        // Check if Midtrans Snap is loaded
        if (typeof snap === 'undefined') {
            alert('Sistem pembayaran belum siap. Silakan refresh halaman dan coba lagi.');
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
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin', // Important for session cookies
            body: formData
        })
        .then(response => {
            console.log('Payment response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Payment response data:', data);

            if (data.success) {
                if (data.snap_token) {
                    console.log('Opening Midtrans Snap with token:', data.snap_token.substring(0, 20) + '...');
                } else if (data.redirect_url) {
                    console.log('No snap_token, redirecting to:', data.redirect_url);
                    // Don't redirect automatically, show error instead
                    throw new Error('Tidak ada token pembayaran. Silakan coba lagi.');
                } else {
                    throw new Error('Response tidak valid dari server');
                }
            } else {
                throw new Error(data.message || 'Gagal memproses pembayaran');
            }

            if (data.success && data.snap_token) {

                // Check if snap is available
                if (typeof snap === 'undefined') {
                    throw new Error('Midtrans Snap tidak tersedia. Silakan refresh halaman.');
                }

                // Open Midtrans Snap
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
                        payButton.innerHTML = '<i class="bi bi-credit-card me-1"></i>Bayar Sekarang';
                    }
                });
            } else {
                throw new Error(data.message || 'Gagal mendapatkan token pembayaran');
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
