<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Tiket - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="{{ asset('css/unas-theme.css') }}" rel="stylesheet">
</head>
<body style="background: #f8fafc;">

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
    <div class="row justify-content-center w-100">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>Verifikasi Tiket
                    </h3>
                </div>
                <div class="card-body p-5">
                    @if($valid)
                        <!-- Valid Ticket -->
                        <div class="text-center mb-4">
                            <div class="success-icon mb-3">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-success mb-3">Tiket Valid!</h4>
                            <p class="text-muted">{{ $message }}</p>
                        </div>
                        
                        <div class="ticket-info bg-light rounded p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Nama Peserta:</strong><br>
                                    <span class="text-muted">{{ $registration->user->name }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email:</strong><br>
                                    <span class="text-muted">{{ $registration->user->email }}</span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Kompetisi:</strong><br>
                                    <span class="text-muted">{{ $registration->competition->name }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Kategori:</strong><br>
                                    <span class="badge bg-primary">{{ ucfirst($registration->competition->category) }}</span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Kode Tiket:</strong><br>
                                    <code class="text-primary">{{ $registration->ticket_code }}</code>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Status:</strong><br>
                                    <span class="badge bg-success">{{ ucfirst($registration->status) }}</span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Daftar:</strong><br>
                                    <span class="text-muted">{{ $registration->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Institusi:</strong><br>
                                    <span class="text-muted">{{ $registration->user->institution ?? 'Tidak diketahui' }}</span>
                                </div>
                            </div>
                            
                            @if($registration->team_name)
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <strong>Nama Tim:</strong><br>
                                    <span class="text-muted">{{ $registration->team_name }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-center mt-4">
                            <div class="alert alert-success">
                                <i class="bi bi-info-circle me-2"></i>
                                Tiket ini telah diverifikasi pada {{ now()->format('d M Y H:i:s') }}
                            </div>
                        </div>
                        
                    @else
                        <!-- Invalid Ticket -->
                        <div class="text-center mb-4">
                            <div class="error-icon mb-3">
                                <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-danger mb-3">Tiket Tidak Valid!</h4>
                            <p class="text-muted">{{ $message }}</p>
                        </div>
                        
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-exclamation-triangle me-2"></i>Kemungkinan Penyebab:</h6>
                            <ul class="mb-0">
                                <li>Kode tiket salah atau tidak ditemukan</li>
                                <li>Tiket sudah kadaluarsa</li>
                                <li>Registrasi belum dikonfirmasi</li>
                                <li>Pembayaran belum selesai</li>
                            </ul>
                        </div>
                    @endif
                    
                    <div class="text-center mt-4">
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('ticket.scan') }}" class="btn btn-primary">
                                <i class="bi bi-qr-code-scan me-2"></i>Scan Tiket Lain
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Beranda
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Sistem Verifikasi UNAS Fest 2025
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh untuk invalid tickets setelah 10 detik
    @if(!$valid)
        setTimeout(function() {
            const refreshBtn = document.createElement('button');
            refreshBtn.className = 'btn btn-outline-primary btn-sm mt-2';
            refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi';
            refreshBtn.onclick = function() {
                window.location.reload();
            };
            
            const alertDiv = document.querySelector('.alert-danger');
            if (alertDiv) {
                alertDiv.appendChild(refreshBtn);
            }
        }, 10000);
    @endif
    
    // Print ticket untuk valid tickets
    @if($valid)
        const printBtn = document.createElement('button');
        printBtn.className = 'btn btn-outline-success';
        printBtn.innerHTML = '<i class="bi bi-printer me-2"></i>Cetak Tiket';
        printBtn.onclick = function() {
            window.print();
        };
        
        const buttonGroup = document.querySelector('.d-flex.gap-3');
        if (buttonGroup) {
            buttonGroup.appendChild(printBtn);
        }
    @endif
});
</script>

<style>
@media print {
    .btn, .card-footer {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    body {
        background: white !important;
    }
}

.success-icon, .error-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.ticket-info {
    border-left: 4px solid var(--bs-primary);
}

code {
    font-size: 1.1em;
    padding: 0.25rem 0.5rem;
    background: rgba(13, 110, 253, 0.1);
    border-radius: 0.25rem;
}
</style>

</body>
</html>
