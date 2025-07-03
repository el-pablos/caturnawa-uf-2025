@extends('layouts.admin')

@section('title', 'QR Scanner')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">QR Scanner</h1>
            <p class="text-muted">Scan QR Code untuk check-in peserta kompetisi</p>
        </div>
    </div>

    <!-- Scanner Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-qr-code-scan me-2"></i>QR Code Scanner
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Camera Preview -->
                    <div class="text-center mb-4">
                        <div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>
                        <div id="qr-reader-results" class="mt-3"></div>
                    </div>

                    <!-- Manual Input -->
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" id="manual-qr-input" class="form-control" 
                                   placeholder="Atau masukkan kode QR secara manual...">
                        </div>
                        <div class="col-md-4">
                            <button type="button" id="manual-scan-btn" class="btn btn-primary w-100">
                                <i class="bi bi-search me-2"></i>Scan Manual
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Scan Result -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Hasil Scan
                    </h5>
                </div>
                <div class="card-body">
                    <div id="scan-result" class="text-center text-muted">
                        <i class="bi bi-qr-code display-4 mb-3"></i>
                        <p>Belum ada QR Code yang di-scan</p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Statistik Hari Ini
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0" id="today-checkins">0</h4>
                                <small class="text-muted">Check-in</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0" id="total-scans">0</h4>
                            <small class="text-muted">Total Scan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Check-ins -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Check-in Terbaru
                    </h5>
                    <button type="button" id="refresh-history" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="checkin-history">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Peserta</th>
                                    <th>Kompetisi</th>
                                    <th>No. Registrasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        <i class="bi bi-hourglass-split me-2"></i>Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Check-in Confirmation Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Check-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="checkin-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirm-checkin" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>Konfirmasi Check-in
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #qr-reader {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        background: #f8f9fa;
    }
    
    #qr-reader video {
        border-radius: 8px;
    }
    
    .scan-success {
        border-color: #28a745 !important;
        background-color: #d4edda !important;
    }
    
    .scan-error {
        border-color: #dc3545 !important;
        background-color: #f8d7da !important;
    }
</style>
@endpush

@push('scripts')
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
$(document).ready(function() {
    let html5QrcodeScanner;
    let currentRegistration = null;
    let scanCount = 0;
    
    // Initialize QR Scanner
    initQRScanner();
    
    // Load initial data
    loadHistory();
    loadStatistics();
    
    function initQRScanner() {
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", 
            { 
                fps: 10, 
                qrbox: {width: 250, height: 250},
                aspectRatio: 1.0
            },
            false
        );
        
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }
    
    function onScanSuccess(decodedText, decodedResult) {
        scanCount++;
        $('#total-scans').text(scanCount);
        
        // Process the scanned QR code
        processQRCode(decodedText);
    }
    
    function onScanFailure(error) {
        // Handle scan failure silently
    }
    
    function processQRCode(qrData) {
        $.ajax({
            url: '{{ route("admin.qr-scanner.scan") }}',
            method: 'POST',
            data: {
                qr_data: qrData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    currentRegistration = response.data.registration;
                    showScanResult(response.data.registration, response.data.can_check_in);
                    
                    if (response.data.can_check_in) {
                        showCheckinModal(response.data.registration);
                    }
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat memproses QR Code';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showError(message);
            }
        });
    }
    
    function showScanResult(registration, canCheckin) {
        let html = `
            <div class="text-start">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-success rounded-circle p-2 me-3">
                        <i class="bi bi-check-lg text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">QR Code Valid</h6>
                        <small class="text-muted">Peserta ditemukan</small>
                    </div>
                </div>
                
                <div class="mb-2">
                    <strong>Nama:</strong> ${registration.user.name}
                </div>
                <div class="mb-2">
                    <strong>Kompetisi:</strong> ${registration.competition.name}
                </div>
                <div class="mb-2">
                    <strong>No. Registrasi:</strong> ${registration.registration_number}
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> 
                    <span class="badge bg-success">Confirmed</span>
                </div>
                
                ${registration.checked_in_at ? 
                    `<div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Sudah check-in pada ${new Date(registration.checked_in_at).toLocaleString('id-ID')}
                    </div>` : 
                    `<div class="alert alert-success mt-3">
                        <i class="bi bi-check-circle me-2"></i>
                        Siap untuk check-in
                    </div>`
                }
            </div>
        `;
        
        $('#scan-result').html(html);
        $('#qr-reader').addClass('scan-success');
        
        setTimeout(() => {
            $('#qr-reader').removeClass('scan-success');
        }, 2000);
    }
    
    function showError(message) {
        let html = `
            <div class="text-center text-danger">
                <i class="bi bi-x-circle display-4 mb-3"></i>
                <p>${message}</p>
            </div>
        `;
        
        $('#scan-result').html(html);
        $('#qr-reader').addClass('scan-error');
        
        setTimeout(() => {
            $('#qr-reader').removeClass('scan-error');
        }, 2000);
    }
    
    function showCheckinModal(registration) {
        let html = `
            <div class="text-center mb-3">
                <div class="bg-primary rounded-circle p-3 d-inline-block mb-3">
                    <i class="bi bi-person-check text-white display-6"></i>
                </div>
                <h5>${registration.user.name}</h5>
                <p class="text-muted">${registration.competition.name}</p>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <strong>No. Registrasi:</strong><br>
                    <span class="text-muted">${registration.registration_number}</span>
                </div>
                <div class="col-6">
                    <strong>Email:</strong><br>
                    <span class="text-muted">${registration.user.email}</span>
                </div>
            </div>
        `;
        
        $('#checkin-details').html(html);
        $('#checkinModal').modal('show');
    }
    
    // Manual scan
    $('#manual-scan-btn').click(function() {
        let qrData = $('#manual-qr-input').val().trim();
        if (qrData) {
            processQRCode(qrData);
            $('#manual-qr-input').val('');
        }
    });
    
    // Enter key for manual input
    $('#manual-qr-input').keypress(function(e) {
        if (e.which === 13) {
            $('#manual-scan-btn').click();
        }
    });
    
    // Confirm check-in
    $('#confirm-checkin').click(function() {
        if (!currentRegistration) return;
        
        $.ajax({
            url: '{{ route("admin.qr-scanner.checkin") }}',
            method: 'POST',
            data: {
                registration_id: currentRegistration.id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#checkinModal').modal('hide');
                    
                    // Update statistics
                    let currentCheckins = parseInt($('#today-checkins').text());
                    $('#today-checkins').text(currentCheckins + 1);
                    
                    // Reload history
                    loadHistory();
                    
                    // Show success message
                    toastr.success('Check-in berhasil!');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan saat check-in';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    });
    
    // Refresh history
    $('#refresh-history').click(function() {
        loadHistory();
        loadStatistics();
    });
    
    function loadHistory() {
        $.ajax({
            url: '{{ route("admin.qr-scanner.history") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateHistoryTable(response.data.data);
                }
            }
        });
    }
    
    function updateHistoryTable(data) {
        let tbody = $('#checkin-history tbody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="bi bi-inbox me-2"></i>Belum ada check-in hari ini
                    </td>
                </tr>
            `);
            return;
        }
        
        data.forEach(function(item) {
            tbody.append(`
                <tr>
                    <td>${new Date(item.checked_in_at).toLocaleString('id-ID')}</td>
                    <td>${item.user.name}</td>
                    <td>${item.competition.name}</td>
                    <td>${item.registration_number}</td>
                    <td><span class="badge bg-success">Checked In</span></td>
                </tr>
            `);
        });
    }
    
    function loadStatistics() {
        // This would typically load from an API endpoint
        // For now, we'll update based on the history data
        let today = new Date().toISOString().split('T')[0];
        
        $.ajax({
            url: '{{ route("admin.qr-scanner.history") }}',
            method: 'GET',
            data: { date: today },
            success: function(response) {
                if (response.success) {
                    $('#today-checkins').text(response.data.data.length);
                }
            }
        });
    }
});
</script>
@endpush
