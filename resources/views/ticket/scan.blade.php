<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scan Tiket - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/unas-theme.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="background: #f8fafc;">

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-qr-code-scan me-2"></i>Scan Tiket QR Code
                    </h3>
                    <p class="mb-0 mt-2">Arahkan kamera ke QR Code tiket untuk verifikasi</p>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Scanner Section -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="scanner-container">
                                <div id="qr-reader" class="mb-3"></div>
                                <div id="qr-reader-results" class="mt-3"></div>
                            </div>
                            
                            <!-- Manual Input Alternative -->
                            <div class="manual-input mt-4">
                                <h6><i class="bi bi-keyboard me-2"></i>Input Manual Kode Tiket</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="manual-code" 
                                           placeholder="Masukkan kode tiket..." maxlength="20">
                                    <button class="btn btn-primary" type="button" id="verify-manual">
                                        <i class="bi bi-search me-1"></i>Verifikasi
                                    </button>
                                </div>
                                <small class="text-muted">Gunakan ini jika QR scanner tidak berfungsi</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <!-- Scanner Status -->
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-info-circle me-2"></i>Status Scanner
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="scanner-status" class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 mb-0">Memuat scanner...</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Instructions -->
                            <div class="card mt-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-lightbulb me-2"></i>Cara Menggunakan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ol class="mb-0">
                                        <li>Pastikan kamera diizinkan</li>
                                        <li>Arahkan kamera ke QR Code</li>
                                        <li>Tunggu hingga terdeteksi</li>
                                        <li>Hasil akan muncul otomatis</li>
                                    </ol>
                                    
                                    <hr>
                                    
                                    <h6>Alternatif:</h6>
                                    <ul class="mb-0">
                                        <li>Gunakan input manual jika QR tidak terbaca</li>
                                        <li>Pastikan kode tiket benar</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Recent Scans -->
                            <div class="card mt-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">
                                        <i class="bi bi-clock-history me-2"></i>Scan Terakhir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="recent-scans">
                                        <p class="text-muted mb-0">Belum ada scan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer text-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hasil Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-content">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="scan-again">Scan Lagi</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let html5QrcodeScanner;
    let recentScans = [];
    
    // Initialize QR Scanner
    function initializeScanner() {
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };
        
        html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", config, false);
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        
        updateScannerStatus('active', 'Scanner aktif - Arahkan ke QR Code');
    }
    
    // Handle successful scan
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);
        
        // Stop scanning temporarily
        html5QrcodeScanner.pause(true);
        
        // Verify the ticket
        verifyTicket(decodedText);
        
        // Add to recent scans
        addToRecentScans(decodedText);
    }
    
    // Handle scan failure
    function onScanFailure(error) {
        // Handle scan failure, usually better to ignore and keep scanning
        console.warn(`Code scan error = ${error}`);
    }
    
    // Update scanner status
    function updateScannerStatus(status, message) {
        const statusDiv = document.getElementById('scanner-status');
        let icon, color;
        
        switch(status) {
            case 'loading':
                icon = 'spinner-border';
                color = 'text-primary';
                break;
            case 'active':
                icon = 'bi-camera-video';
                color = 'text-success';
                break;
            case 'error':
                icon = 'bi-exclamation-triangle';
                color = 'text-danger';
                break;
            case 'paused':
                icon = 'bi-pause-circle';
                color = 'text-warning';
                break;
        }
        
        statusDiv.innerHTML = `
            <i class="${icon} ${color}" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-0">${message}</p>
        `;
    }
    
    // Verify ticket via AJAX
    function verifyTicket(code) {
        updateScannerStatus('loading', 'Memverifikasi tiket...');
        
        fetch('{{ route("ticket.validate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            showResult(data);
            updateScannerStatus('active', 'Scanner aktif - Arahkan ke QR Code');
            
            // Resume scanning after 3 seconds
            setTimeout(() => {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memverifikasi tiket');
            updateScannerStatus('error', 'Error - Coba lagi');
            
            // Resume scanning
            setTimeout(() => {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            }, 2000);
        });
    }
    
    // Show verification result
    function showResult(data) {
        const modal = new bootstrap.Modal(document.getElementById('resultModal'));
        const modalContent = document.getElementById('modal-content');
        
        if (data.valid) {
            modalContent.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h4 class="text-success mt-3">Tiket Valid!</h4>
                    <div class="alert alert-success mt-3">
                        <strong>Peserta:</strong> ${data.registration.user.name}<br>
                        <strong>Kompetisi:</strong> ${data.registration.competition.name}<br>
                        <strong>Kode:</strong> <code>${data.registration.ticket_code}</code>
                    </div>
                </div>
            `;
        } else {
            modalContent.innerHTML = `
                <div class="text-center">
                    <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                    <h4 class="text-danger mt-3">Tiket Tidak Valid!</h4>
                    <div class="alert alert-danger mt-3">
                        ${data.message}
                    </div>
                </div>
            `;
        }
        
        modal.show();
    }
    
    // Show error message
    function showError(message) {
        const modal = new bootstrap.Modal(document.getElementById('resultModal'));
        const modalContent = document.getElementById('modal-content');
        
        modalContent.innerHTML = `
            <div class="text-center">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                <h4 class="text-warning mt-3">Error</h4>
                <div class="alert alert-warning mt-3">
                    ${message}
                </div>
            </div>
        `;
        
        modal.show();
    }
    
    // Add to recent scans
    function addToRecentScans(code) {
        const timestamp = new Date().toLocaleTimeString();
        recentScans.unshift({ code, timestamp });
        
        // Keep only last 5 scans
        if (recentScans.length > 5) {
            recentScans = recentScans.slice(0, 5);
        }
        
        updateRecentScans();
    }
    
    // Update recent scans display
    function updateRecentScans() {
        const recentScansDiv = document.getElementById('recent-scans');
        
        if (recentScans.length === 0) {
            recentScansDiv.innerHTML = '<p class="text-muted mb-0">Belum ada scan</p>';
            return;
        }
        
        let html = '';
        recentScans.forEach(scan => {
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <code class="small">${scan.code}</code>
                    <small class="text-muted">${scan.timestamp}</small>
                </div>
            `;
        });
        
        recentScansDiv.innerHTML = html;
    }
    
    // Manual verification
    document.getElementById('verify-manual').addEventListener('click', function() {
        const code = document.getElementById('manual-code').value.trim();
        if (code) {
            verifyTicket(code);
            addToRecentScans(code);
            document.getElementById('manual-code').value = '';
        }
    });
    
    // Enter key for manual input
    document.getElementById('manual-code').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('verify-manual').click();
        }
    });
    
    // Scan again button
    document.getElementById('scan-again').addEventListener('click', function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('resultModal'));
        modal.hide();
        
        if (html5QrcodeScanner) {
            html5QrcodeScanner.resume();
        }
    });
    
    // Initialize scanner when page loads
    try {
        initializeScanner();
    } catch (error) {
        console.error('Failed to initialize scanner:', error);
        updateScannerStatus('error', 'Gagal memuat scanner - Gunakan input manual');
    }
});
</script>

<style>
#qr-reader {
    border: 2px dashed #dee2e6;
    border-radius: 0.5rem;
    overflow: hidden;
}

#qr-reader__dashboard_section {
    display: none !important;
}

.manual-input {
    border-top: 1px solid #dee2e6;
    padding-top: 1rem;
}

.scanner-container {
    position: relative;
}

code {
    font-size: 0.875em;
    padding: 0.25rem 0.5rem;
    background: rgba(13, 110, 253, 0.1);
    border-radius: 0.25rem;
}

@media (max-width: 768px) {
    #qr-reader {
        width: 100% !important;
    }
}
</style>

</body>
</html>
