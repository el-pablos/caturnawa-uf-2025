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
                    
                    <!-- Essential System Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-gear me-2"></i>Pengaturan Sistem
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label fw-semibold">Nama Aplikasi</label>
                                <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                       id="app_name" name="app_name" value="{{ old('app_name', config('app.name')) }}" required>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="app_url" class="form-label fw-semibold">URL Aplikasi</label>
                                <input type="url" class="form-control @error('app_url') is-invalid @enderror"
                                       id="app_url" name="app_url" value="{{ old('app_url', config('app.url')) }}" required>
                                @error('app_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="timezone" class="form-label fw-semibold">Timezone</label>
                                <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                                    <option value="Asia/Jakarta" {{ old('timezone', config('app.timezone')) == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar" {{ old('timezone', config('app.timezone')) == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura" {{ old('timezone', config('app.timezone')) == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="locale" class="form-label fw-semibold">Bahasa</label>
                                <select class="form-select @error('locale') is-invalid @enderror" id="locale" name="locale" required>
                                    <option value="id" {{ old('locale', config('app.locale')) == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="en" {{ old('locale', config('app.locale')) == 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                @error('locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Competition Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-trophy me-2"></i>Pengaturan Kompetisi
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_team_members" class="form-label fw-semibold">Maksimal Anggota Tim</label>
                                <input type="number" class="form-control @error('max_team_members') is-invalid @enderror"
                                       id="max_team_members" name="max_team_members" value="{{ old('max_team_members', 5) }}" min="1" max="10" required>
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="app_description" class="form-label fw-semibold">Deskripsi Aplikasi</label>
                                <textarea class="form-control @error('app_description') is-invalid @enderror"
                                          id="app_description" name="app_description" rows="3" required>{{ old('app_description', $settings['app_description'] ?? 'Festival Kompetisi Universitas Nasional') }}</textarea>
                                @error('app_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- File Upload Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-cloud-upload me-2"></i>Pengaturan Upload File
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_file_size" class="form-label fw-semibold">Maksimal Ukuran File</label>
                                <select class="form-select @error('max_file_size') is-invalid @enderror" id="max_file_size" name="max_file_size" required>
                                    <option value="2048" {{ old('max_file_size', 10240) == 2048 ? 'selected' : '' }}>2 MB</option>
                                    <option value="5120" {{ old('max_file_size', 10240) == 5120 ? 'selected' : '' }}>5 MB</option>
                                    <option value="10240" {{ old('max_file_size', 10240) == 10240 ? 'selected' : '' }}>10 MB</option>
                                    <option value="20480" {{ old('max_file_size', 10240) == 20480 ? 'selected' : '' }}>20 MB</option>
                                    <option value="51200" {{ old('max_file_size', 10240) == 51200 ? 'selected' : '' }}>50 MB</option>
                                </select>
                                <div class="form-text">Ukuran maksimal file yang dapat diupload (dalam KB)</div>
                                @error('max_file_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="allowed_file_types" class="form-label fw-semibold">Tipe File yang Diizinkan</label>
                                <input type="text" class="form-control @error('allowed_file_types') is-invalid @enderror"
                                       id="allowed_file_types" name="allowed_file_types"
                                       value="{{ old('allowed_file_types', 'pdf,doc,docx,jpg,jpeg,png,zip,rar') }}"
                                       placeholder="pdf,doc,docx,jpg,png,zip" required>
                                <div class="form-text">Pisahkan dengan koma (,). Contoh: pdf,doc,docx,jpg,png</div>
                                @error('allowed_file_types')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="storage_path" class="form-label fw-semibold">Path Penyimpanan</label>
                                <input type="text" class="form-control @error('storage_path') is-invalid @enderror"
                                       id="storage_path" name="storage_path"
                                       value="{{ old('storage_path', 'uploads') }}" required>
                                <div class="form-text">Folder penyimpanan file (relatif dari storage/app/public)</div>
                                @error('storage_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" name="auto_optimize_images" id="autoOptimizeImages"
                                           {{ old('auto_optimize_images', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="autoOptimizeImages">
                                        Optimasi Gambar Otomatis
                                    </label>
                                    <div class="text-muted small">Kompres gambar secara otomatis saat upload</div>
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
            // Reset form to default values
            document.getElementById('app_name').value = 'UNAS Fest 2025';
            document.getElementById('app_description').value = 'Festival Kompetisi Universitas Nasional';
            document.getElementById('contact_email').value = 'info@unasfest.com';
            document.getElementById('contact_phone').value = '+62 21 1234 5678';
            document.getElementById('max_file_size').value = '10MB';
            document.getElementById('allowed_file_types').value = 'pdf,doc,docx,jpg,png,zip';
            document.getElementById('registration_open').checked = true;
            document.getElementById('maintenance_mode').checked = false;
            
            showSuccess('Pengaturan berhasil direset ke default');
        }
    );
}

function clearCache() {
    const btn = document.getElementById('clearCacheBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2 spin"></i>Clearing...';

    fetch('/admin/maintenance/clear-cache', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Cache berhasil dibersihkan');
        } else {
            showError('Gagal membersihkan cache: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        showError('Error: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function optimizeApp() {
    const btn = document.getElementById('optimizeBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-speedometer2 me-2 spin"></i>Optimizing...';

    fetch('/admin/maintenance/optimize', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Aplikasi berhasil dioptimasi');
        } else {
            showError('Gagal mengoptimasi aplikasi: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        showError('Error: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function clearLogs() {
    confirmAction(
        'Clear Old Logs',
        'Apakah Anda yakin ingin menghapus log lama (lebih dari 30 hari)?',
        function() {
            const btn = document.getElementById('clearLogsBtn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-trash me-2 spin"></i>Clearing...';

            fetch('/admin/maintenance/clear-logs', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Log lama berhasil dibersihkan');
                } else {
                    showError('Gagal membersihkan log: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                showError('Error: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    );
}

function runMaintenance() {
    confirmAction(
        'Run Maintenance',
        'Apakah Anda yakin ingin menjalankan maintenance lengkap? Ini akan membersihkan cache, optimasi, dan membersihkan file temporary.',
        function() {
            const btn = document.getElementById('maintenanceBtn');
            const originalText = btn.innerHTML;
            const resultDiv = document.getElementById('maintenanceResult');
            const statusSpan = document.getElementById('maintenanceStatus');

            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-tools me-2 spin"></i>Running...';
            resultDiv.style.display = 'block';
            statusSpan.textContent = 'Running maintenance tasks...';

            fetch('/admin/maintenance/run-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusSpan.textContent = 'Maintenance completed successfully!';
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        showSuccess('Maintenance berhasil dijalankan');
                    }, 2000);
                } else {
                    statusSpan.textContent = 'Maintenance failed: ' + (data.message || 'Unknown error');
                    setTimeout(() => {
                        resultDiv.style.display = 'none';
                        showError('Gagal menjalankan maintenance');
                    }, 3000);
                }
            })
            .catch(error => {
                statusSpan.textContent = 'Error: ' + error.message;
                setTimeout(() => {
                    resultDiv.style.display = 'none';
                    showError('Error: ' + error.message);
                }, 3000);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    );
}

function checkSystemHealth() {
    const btn = document.getElementById('healthCheckBtn');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-heart-pulse me-2 spin"></i>Checking...';

    fetch('/admin/maintenance/health-check', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let message = 'System Health Check Results:\n';
            message += '• Database: ' + (data.data.database ? '✓ OK' : '✗ Error') + '\n';
            message += '• Redis: ' + (data.data.redis ? '✓ OK' : '✗ Error') + '\n';
            message += '• Storage: ' + (data.data.storage ? '✓ OK' : '✗ Error') + '\n';
            message += '• Queue: ' + (data.data.queue ? '✓ OK' : '✗ Error');

            alert(message);
        } else {
            showError('Gagal melakukan health check: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        showError('Error: ' + error.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>
@endpush
