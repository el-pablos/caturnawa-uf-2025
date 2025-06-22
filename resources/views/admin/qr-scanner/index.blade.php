@extends('layouts.admin')

@section('title', 'QR Scanner E-Ticket')
@section('page-title', 'QR Scanner E-Ticket')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.qr-scanner.history') }}" class="btn btn-outline-info">
            <i class="bi bi-clock-history me-2"></i>Riwayat Check-in
        </a>
        <button type="button" class="btn btn-primary" onclick="startScanner()">
            <i class="bi bi-camera me-2"></i>Mulai Scanner
        </button>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Scanner Card -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-qr-code-scan me-2"></i>Scanner QR Code E-Ticket
                </h5>
            </div>
            <div class="card-body">
                <!-- Scanner Area -->
                <div id="scanner-container" class="text-center mb-4">
                    <div id="scanner-placeholder" class="scanner-placeholder">
                        <i class="bi bi-qr-code-scan display-1 text-muted"></i>
                        <h4 class="mt-3">QR Code Scanner</h4>
                        <p class="text-muted">Klik "Mulai Scanner" untuk memulai scanning QR code e-ticket</p>
                        <button type="button" class="btn btn-primary btn-lg" onclick="startScanner()">
                            <i class="bi bi-camera me-2"></i>Mulai Scanner
                        </button>
                    </div>
                    <div id="scanner-video" style="display: none;">
                        <video id="qr-video" width="100%" style="max-width: 500px; border-radius: 10px;"></video>
                        <div class="mt-3">
                            <button type="button" class="btn btn-danger" onclick="stopScanner()">
                                <i class="bi bi-stop-circle me-2"></i>Stop Scanner
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="manual-input">
                    <h6>Atau Input Manual QR Data:</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="manual-qr-input" 
                               placeholder="Paste QR code data di sini...">
                        <button class="btn btn-outline-primary" type="button" onclick="verifyManualInput()">
                            <i class="bi bi-search me-2"></i>Verify
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Card -->
        <div id="result-card" class="card mt-4" style="display: none;">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Hasil Verifikasi
                </h6>
            </div>
            <div class="card-body" id="result-content">
                <!-- Result will be populated here -->
            </div>
        </div>
    </div>
</div>

<!-- Check-in Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle me-2"></i>Konfirmasi Check-in
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="checkin-modal-body">
                <!-- Participant details will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirm-checkin-btn">
                    <i class="bi bi-check-lg me-2"></i>Konfirmasi Check-in
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.scanner-placeholder {
    padding: 4rem 2rem;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    background: #f8f9fa;
}

.manual-input {
    border-top: 1px solid #dee2e6;
    padding-top: 1.5rem;
    margin-top: 1.5rem;
}

#qr-video {
    border: 3px solid #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.participant-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.participant-info h6 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.participant-info .value {
    font-weight: 600;
    color: #212529;
}

.status-success {
    color: #198754;
    background: #d1e7dd;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.status-error {
    color: #dc3545;
    background: #f8d7da;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.status-warning {
    color: #664d03;
    background: #fff3cd;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
<script>
let qrScanner = null;
let currentRegistration = null;

function startScanner() {
    const video = document.getElementById('qr-video');
    const placeholder = document.getElementById('scanner-placeholder');
    const videoContainer = document.getElementById('scanner-video');
    
    placeholder.style.display = 'none';
    videoContainer.style.display = 'block';
    
    qrScanner = new QrScanner(
        video,
        result => {
            console.log('QR Code detected:', result.data);
            verifyQRCode(result.data);
            stopScanner();
        },
        {
            returnDetailedScanResult: true,
            highlightScanRegion: true,
            highlightCodeOutline: true,
        }
    );
    
    qrScanner.start().catch(err => {
        console.error('Error starting scanner:', err);
        showAlert('error', 'Gagal memulai scanner: ' + err.message);
        stopScanner();
    });
}

function stopScanner() {
    if (qrScanner) {
        qrScanner.stop();
        qrScanner.destroy();
        qrScanner = null;
    }
    
    const placeholder = document.getElementById('scanner-placeholder');
    const videoContainer = document.getElementById('scanner-video');
    
    placeholder.style.display = 'block';
    videoContainer.style.display = 'none';
}

function verifyManualInput() {
    const input = document.getElementById('manual-qr-input');
    const qrData = input.value.trim();
    
    if (!qrData) {
        showAlert('warning', 'Masukkan data QR code terlebih dahulu');
        return;
    }
    
    verifyQRCode(qrData);
}

function verifyQRCode(qrData) {
    showLoading();
    
    fetch('/admin/qr-scanner/verify', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ qr_data: qrData })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        displayResult(data);
        
        if (data.success && data.registration) {
            currentRegistration = data.registration;
            if (!data.registration.checked_in_at) {
                showCheckinModal(data.registration);
            }
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memverifikasi QR code');
    });
}

function displayResult(data) {
    const resultCard = document.getElementById('result-card');
    const resultContent = document.getElementById('result-content');
    
    let html = '';
    
    if (data.success) {
        const reg = data.registration;
        html = `
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                <strong>QR Code Valid!</strong> ${data.message}
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="participant-info">
                        <h6>Informasi Peserta</h6>
                        <div class="mb-2">
                            <small class="text-muted">Nama:</small><br>
                            <span class="value">${reg.participant_name}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Email:</small><br>
                            <span class="value">${reg.participant_email}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Institusi:</small><br>
                            <span class="value">${reg.institution || '-'}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="participant-info">
                        <h6>Informasi Kompetisi</h6>
                        <div class="mb-2">
                            <small class="text-muted">Kompetisi:</small><br>
                            <span class="value">${reg.competition_name}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Kategori:</small><br>
                            <span class="value">${reg.competition_category}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">No. Registrasi:</small><br>
                            <span class="value">${reg.registration_number}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="participant-info">
                <h6>Status Check-in</h6>
                ${reg.checked_in_at ? 
                    `<span class="status-warning">Sudah Check-in pada ${reg.checked_in_at}</span>` :
                    `<span class="status-success">Belum Check-in - Siap untuk check-in</span>`
                }
            </div>
        `;
    } else {
        html = `
            <div class="alert alert-danger">
                <i class="bi bi-x-circle me-2"></i>
                <strong>QR Code Tidak Valid!</strong> ${data.message}
            </div>
        `;
    }
    
    resultContent.innerHTML = html;
    resultCard.style.display = 'block';
}

function showCheckinModal(registration) {
    const modalBody = document.getElementById('checkin-modal-body');
    
    modalBody.innerHTML = `
        <div class="text-center mb-3">
            <i class="bi bi-person-check-fill text-success" style="font-size: 3rem;"></i>
            <h5 class="mt-2">Konfirmasi Check-in Peserta</h5>
        </div>
        
        <div class="participant-info">
            <div class="row">
                <div class="col-md-6">
                    <strong>Nama:</strong> ${registration.participant_name}<br>
                    <strong>Email:</strong> ${registration.participant_email}<br>
                    <strong>Telepon:</strong> ${registration.phone || '-'}
                </div>
                <div class="col-md-6">
                    <strong>Kompetisi:</strong> ${registration.competition_name}<br>
                    <strong>No. Registrasi:</strong> ${registration.registration_number}<br>
                    <strong>Institusi:</strong> ${registration.institution || '-'}
                </div>
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Pastikan identitas peserta sesuai sebelum melakukan check-in.
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('checkinModal'));
    modal.show();
}

document.getElementById('confirm-checkin-btn').addEventListener('click', function() {
    if (!currentRegistration) return;
    
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
    this.disabled = true;
    
    fetch('/admin/qr-scanner/checkin', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ registration_id: currentRegistration.id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            bootstrap.Modal.getInstance(document.getElementById('checkinModal')).hide();
            
            // Update result display
            currentRegistration.checked_in_at = data.checked_in_at;
            displayResult({ success: true, registration: currentRegistration, message: 'Check-in berhasil' });
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat check-in');
    })
    .finally(() => {
        this.innerHTML = '<i class="bi bi-check-lg me-2"></i>Konfirmasi Check-in';
        this.disabled = false;
    });
});

function showLoading() {
    const resultCard = document.getElementById('result-card');
    const resultContent = document.getElementById('result-content');
    
    resultContent.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memverifikasi QR code...</p>
        </div>
    `;
    resultCard.style.display = 'block';
}

function hideLoading() {
    // Loading will be replaced by result
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
    const iconClass = type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'x-circle');
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="bi bi-${iconClass} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) alert.remove();
    }, 5000);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (qrScanner) {
        qrScanner.stop();
        qrScanner.destroy();
    }
});
</script>
@endpush
