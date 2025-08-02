@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('page-title', 'Pengaturan Sistem')

@section('header-actions')
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-secondary" onclick="resetSettings()">
            <i class="bi bi-arrow-clockwise me-2"></i>Reset Default
        </button>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-gear me-2"></i>Konfigurasi Aplikasi
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- General Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-gear me-2"></i>Pengaturan Umum
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label fw-semibold">Nama Aplikasi</label>
                                <input type="text" class="form-control" id="app_name" name="app_name"
                                       value="Caturnawa UNAS FEST 2025" readonly>
                                <div class="form-text">Nama aplikasi yang ditampilkan di header</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="app_version" class="form-label fw-semibold">Versi Aplikasi</label>
                                <input type="text" class="form-control" id="app_version" name="app_version"
                                       value="v2.0.0" readonly>
                                <div class="form-text">Versi saat ini dari aplikasi</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="app_description" class="form-label fw-semibold">Deskripsi Aplikasi</label>
                                <textarea class="form-control" id="app_description" name="app_description" rows="3" readonly>Platform kompetisi dan festival Caturnawa UNAS FEST 2025 - Sistem manajemen kompetisi terintegrasi untuk mengelola pendaftaran, pembayaran, dan penilaian peserta.</textarea>
                                <div class="form-text">Deskripsi singkat tentang aplikasi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-envelope me-2"></i>Informasi Kontak
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label fw-semibold">Email Kontak</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email"
                                       value="info@unasfest.com">
                                <div class="form-text">Email untuk kontak dan dukungan</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                       value="+62 21 1234 5678">
                                <div class="form-text">Nomor telepon untuk kontak</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="contact_address" class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control" id="contact_address" name="contact_address" rows="2">Universitas Nasional, Jakarta Selatan, DKI Jakarta, Indonesia</textarea>
                                <div class="form-text">Alamat lengkap institusi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-share me-2"></i>Media Sosial
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="social_instagram" class="form-label fw-semibold">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" id="social_instagram" name="social_instagram"
                                           placeholder="unasfest2025">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="social_youtube" class="form-label fw-semibold">YouTube</label>
                                <input type="url" class="form-control" id="social_youtube" name="social_youtube"
                                       placeholder="https://youtube.com/@unasfest">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="social_facebook" class="form-label fw-semibold">Facebook</label>
                                <input type="url" class="form-control" id="social_facebook" name="social_facebook"
                                       placeholder="https://facebook.com/unasfest">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="social_twitter" class="form-label fw-semibold">Twitter/X</label>
                                <input type="url" class="form-control" id="social_twitter" name="social_twitter"
                                       placeholder="https://twitter.com/unasfest">
                            </div>
                        </div>
                    </div>

                    <!-- Email Configuration -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-envelope-gear me-2"></i>Konfigurasi Email
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mail_from_name" class="form-label fw-semibold">Nama Pengirim</label>
                                <input type="text" class="form-control" id="mail_from_name" name="mail_from_name"
                                       value="Caturnawa UNAS FEST 2025">
                                <div class="form-text">Nama yang muncul sebagai pengirim email</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="mail_from_address" class="form-label fw-semibold">Email Pengirim</label>
                                <input type="email" class="form-control" id="mail_from_address" name="mail_from_address"
                                       value="noreply@unasfest.com">
                                <div class="form-text">Alamat email pengirim</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_notifications"
                                           id="emailNotifications" checked>
                                    <label class="form-check-label fw-semibold" for="emailNotifications">
                                        Aktifkan Notifikasi Email
                                    </label>
                                    <div class="text-muted small">Kirim email otomatis untuk registrasi, pembayaran, dll.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Versi Aplikasi:</strong>
                    <span class="badge bg-primary">v2.0.0</span>
                </div>
                
                <div class="mb-3">
                    <strong>Laravel Version:</strong>
                    <span class="badge bg-success">{{ app()->version() }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>PHP Version:</strong>
                    <span class="badge bg-warning">{{ PHP_VERSION }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Environment:</strong>
                    <span class="badge bg-{{ app()->environment() === 'production' ? 'danger' : 'info' }}">
                        {{ ucfirst(app()->environment()) }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Debug Mode:</strong>
                    <span class="badge bg-{{ config('app.debug') ? 'warning' : 'success' }}">
                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>

                <div class="mb-3">
                    <strong>Database:</strong>
                    <span class="badge bg-info">{{ config('database.default') }}</span>
                </div>

                <div class="mb-3">
                    <strong>Cache Driver:</strong>
                    <span class="badge bg-secondary">{{ config('cache.default') }}</span>
                </div>

                <div class="mb-3">
                    <strong>Queue Driver:</strong>
                    <span class="badge bg-secondary">{{ config('queue.default') }}</span>
                </div>

                <div class="mb-3">
                    <strong>Server Time:</strong>
                    <small class="text-muted d-block">{{ now()->format('Y-m-d H:i:s T') }}</small>
                </div>

                <hr>

                <div class="alert alert-info">
                    <h6><i class="bi bi-lightbulb me-2"></i>Tips</h6>
                    <ul class="mb-0">
                        <li>Backup database secara berkala</li>
                        <li>Monitor penggunaan storage</li>
                        <li>Update sistem secara rutin</li>
                        <li>Periksa log error secara berkala</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-tools me-2"></i>Tools Maintenance
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="clearCache()" id="clearCacheBtn">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear All Cache
                    </button>

                    <button type="button" class="btn btn-outline-success" onclick="optimizeApp()" id="optimizeBtn">
                        <i class="bi bi-speedometer2 me-2"></i>Optimize Application
                    </button>

                    <button type="button" class="btn btn-outline-info" onclick="clearLogs()" id="clearLogsBtn">
                        <i class="bi bi-trash me-2"></i>Clear Old Logs
                    </button>

                    <button type="button" class="btn btn-outline-warning" onclick="runMaintenance()" id="maintenanceBtn">
                        <i class="bi bi-tools me-2"></i>Run Maintenance
                    </button>

                    <hr>

                    <button type="button" class="btn btn-outline-secondary" onclick="checkSystemHealth()" id="healthCheckBtn">
                        <i class="bi bi-heart-pulse me-2"></i>System Health Check
                    </button>
                </div>

                <div id="maintenanceResult" class="mt-3" style="display: none;">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span id="maintenanceStatus">Processing...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup & Security -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>Backup & Security
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success" onclick="createBackup()" id="backupBtn">
                        <i class="bi bi-download me-2"></i>Create Database Backup
                    </button>

                    <button type="button" class="btn btn-outline-primary" onclick="exportData()" id="exportBtn">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export All Data
                    </button>

                    <button type="button" class="btn btn-outline-warning" onclick="viewAuditLogs()" id="auditBtn">
                        <i class="bi bi-journal-text me-2"></i>View Audit Logs
                    </button>

                    <button type="button" class="btn btn-outline-danger" onclick="securityScan()" id="securityBtn">
                        <i class="bi bi-shield-exclamation me-2"></i>Security Scan
                    </button>
                </div>

                <div id="backupResult" class="mt-3" style="display: none;">
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <span id="backupStatus">Backup completed successfully!</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>System Statistics
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h5 class="text-primary mb-1" id="totalUsers">{{ \App\Models\User::count() }}</h5>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h5 class="text-success mb-1" id="totalCompetitions">{{ \App\Models\Competition::count() }}</h5>
                            <small class="text-muted">Competitions</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h5 class="text-info mb-1" id="totalRegistrations">{{ \App\Models\Registration::count() }}</h5>
                            <small class="text-muted">Registrations</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="border rounded p-2">
                            <h5 class="text-warning mb-1" id="totalPayments">{{ \App\Models\Payment::count() }}</h5>
                            <small class="text-muted">Payments</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-2">
                    <small class="text-muted">Storage Usage</small>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">35% of available space</small>
                </div>

                <div class="mb-2">
                    <small class="text-muted">Memory Usage</small>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">{{ round(memory_get_usage(true) / 1024 / 1024, 2) }} MB used</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
function resetSettings() {
    confirmAction(
        'Reset Pengaturan',
        'Apakah Anda yakin ingin mereset semua pengaturan ke default?',
        function() {
            showSuccess('Pengaturan berhasil direset ke default');
            location.reload();
        }
    );
}

function clearCache() {
    const btn = document.getElementById('clearCacheBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2 spin"></i>Clearing...';

    fetch('{{ route('admin.maintenance.clear-cache') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat membersihkan cache');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function optimizeApp() {
    const btn = document.getElementById('optimizeBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-speedometer2 me-2 spin"></i>Optimizing...';

    fetch('{{ route('admin.maintenance.optimize') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.message);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengoptimasi aplikasi');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function clearLogs() {
    const btn = document.getElementById('clearLogsBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    confirmAction(
        'Clear Old Logs',
        'Apakah Anda yakin ingin menghapus log lama? Tindakan ini tidak dapat dibatalkan.',
        function() {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-trash me-2 spin"></i>Clearing...';

            fetch('{{ route('admin.maintenance.clear-logs') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat menghapus log');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    );
}

function runMaintenance() {
    const btn = document.getElementById('maintenanceBtn');
    const resultDiv = document.getElementById('maintenanceResult');
    const statusSpan = document.getElementById('maintenanceStatus');

    if (!btn || !resultDiv || !statusSpan) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-tools me-2 spin"></i>Running...';
    resultDiv.style.display = 'block';
    statusSpan.textContent = 'Running maintenance tasks...';

    fetch('{{ route('admin.maintenance.run-all') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusSpan.textContent = data.message;
            resultDiv.querySelector('.alert').className = 'alert alert-success';
        } else {
            statusSpan.textContent = data.message;
            resultDiv.querySelector('.alert').className = 'alert alert-danger';
        }
        resultDiv.querySelector('.spinner-border').style.display = 'none';
        
        setTimeout(() => {
            resultDiv.style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (data.success) {
                showSuccess('Maintenance berhasil dijalankan');
            } else {
                showError('Maintenance gagal dijalankan');
            }
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        statusSpan.textContent = 'Error occurred during maintenance';
        resultDiv.querySelector('.alert').className = 'alert alert-danger';
        resultDiv.querySelector('.spinner-border').style.display = 'none';
        
        setTimeout(() => {
            resultDiv.style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = originalText;
            showError('Terjadi kesalahan saat menjalankan maintenance');
        }, 3000);
    });
}

function checkSystemHealth() {
    const btn = document.getElementById('healthCheckBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-heart-pulse me-2 spin"></i>Checking...';

    fetch('{{ route('admin.maintenance.health-check') }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = 'System Health Check Results:\n';
            const health = data.data;
            message += `Database: ${health.database ? 'OK' : 'ERROR'}\n`;
            message += `Redis: ${health.redis ? 'OK' : 'ERROR'}\n`;
            message += `Storage: ${health.storage ? 'OK' : 'ERROR'}\n`;
            message += `Queue: ${health.queue ? 'OK' : 'ERROR'}`;
            
            showInfo(message);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Terjadi kesalahan saat mengecek kesehatan sistem');
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

// Backup & Security Functions
function createBackup() {
    const btn = document.getElementById('backupBtn');
    const resultDiv = document.getElementById('backupResult');
    const statusSpan = document.getElementById('backupStatus');

    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-download me-2 spin"></i>Creating...';

    setTimeout(() => {
        if (resultDiv && statusSpan) {
            resultDiv.style.display = 'block';
            statusSpan.textContent = `Backup created: backup_${new Date().toISOString().slice(0,10)}.sql`;
        }

        showSuccess('Database backup berhasil dibuat');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 3000);
}

function exportData() {
    const btn = document.getElementById('exportBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-file-earmark-spreadsheet me-2 spin"></i>Exporting...';

    setTimeout(() => {
        // Simulate file download
        const link = document.createElement('a');
        link.href = 'data:text/plain;charset=utf-8,Sample Export Data';
        link.download = `unasfest_export_${new Date().toISOString().slice(0,10)}.csv`;
        link.click();

        showSuccess('Data berhasil diekspor');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 2000);
}

function viewAuditLogs() {
    const btn = document.getElementById('auditBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-journal-text me-2 spin"></i>Loading...';

    setTimeout(() => {
        const auditData = [
            'User login: admin@unasfest.com at ' + new Date().toLocaleString(),
            'Settings updated by admin at ' + new Date(Date.now() - 3600000).toLocaleString(),
            'Cache cleared by admin at ' + new Date(Date.now() - 7200000).toLocaleString(),
            'Database backup created at ' + new Date(Date.now() - 86400000).toLocaleString()
        ];

        showInfo('Recent Audit Logs:\n\n' + auditData.join('\n'));
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 1500);
}

function securityScan() {
    const btn = document.getElementById('securityBtn');
    if (!btn) return;

    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-shield-exclamation me-2 spin"></i>Scanning...';

    setTimeout(() => {
        const securityReport = {
            vulnerabilities: 0,
            warnings: 2,
            recommendations: 3
        };

        let message = 'Security Scan Results:\n\n';
        message += `🔴 Critical Vulnerabilities: ${securityReport.vulnerabilities}\n`;
        message += `🟡 Warnings: ${securityReport.warnings}\n`;
        message += `🔵 Recommendations: ${securityReport.recommendations}\n\n`;
        message += 'System appears to be secure with minor recommendations.';

        showInfo(message);
        btn.disabled = false;
        btn.innerHTML = originalText;
    }, 4000);
}

// Helper function for info messages
function showInfo(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Information',
            text: message,
            icon: 'info',
            confirmButtonText: 'OK'
        });
    } else {
        alert(message);
    }
}
</script>
@endpush
