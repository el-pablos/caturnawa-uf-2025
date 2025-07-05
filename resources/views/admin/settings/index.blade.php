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
                    
                    <!-- System Control -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-shield-check me-2"></i>Kontrol Sistem
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenanceMode"
                                           {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="maintenanceMode">
                                        Mode Maintenance
                                    </label>
                                    <div class="text-muted small">Aktifkan untuk menutup akses website</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="registration_open" id="registrationOpen"
                                           {{ ($settings['registration_open'] ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="registrationOpen">
                                        Buka Pendaftaran
                                    </label>
                                    <div class="text-muted small">Izinkan pendaftaran peserta baru</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="maintenance_message" class="form-label fw-semibold">Pesan Mode Maintenance</label>
                                <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'Maaf yahh website dalam masa pemeliharaan, silahkan coba nanti') }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="registration_closed_message" class="form-label fw-semibold">Pesan Pendaftaran Ditutup</label>
                                <textarea class="form-control" id="registration_closed_message" name="registration_closed_message" rows="3">{{ old('registration_closed_message', $settings['registration_closed_message'] ?? 'Pendaftaran sedang ditutup, silahkan tungu periode selanjutnya yaaaa....') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Application Settings -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-app me-2"></i>Informasi Aplikasi
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="app_name" class="form-label fw-semibold">Nama Aplikasi</label>
                                <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                       id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'UNAS Fest 2025') }}" required>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label fw-semibold">Email Kontak</label>
                                <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                       id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'info@unasfest.com') }}" required>
                                @error('contact_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                       id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '+62 21 1234 5678') }}" required>
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
                                <select class="form-control @error('max_file_size') is-invalid @enderror"
                                        id="max_file_size" name="max_file_size" required>
                                    <option value="5" {{ ($settings['max_file_size'] ?? 10) == 5 ? 'selected' : '' }}>5 MB</option>
                                    <option value="10" {{ ($settings['max_file_size'] ?? 10) == 10 ? 'selected' : '' }}>10 MB</option>
                                    <option value="20" {{ ($settings['max_file_size'] ?? 10) == 20 ? 'selected' : '' }}>20 MB</option>
                                    <option value="50" {{ ($settings['max_file_size'] ?? 10) == 50 ? 'selected' : '' }}>50 MB</option>
                                </select>
                                @error('max_file_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="allowed_file_types" class="form-label fw-semibold">Tipe File yang Diizinkan</label>
                                <input type="text" class="form-control @error('allowed_file_types') is-invalid @enderror"
                                       id="allowed_file_types" name="allowed_file_types"
                                       value="{{ old('allowed_file_types', $settings['allowed_file_types'] ?? 'pdf,doc,docx,jpg,png,zip') }}"
                                       placeholder="pdf,doc,docx,jpg,png,zip" required>
                                <div class="form-text">Pisahkan dengan koma (,)</div>
                                @error('allowed_file_types')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                    <button type="button" class="btn btn-outline-primary" onclick="clearCache()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Cache
                    </button>
                    
                    <button type="button" class="btn btn-outline-success" onclick="optimizeApp()">
                        <i class="bi bi-speedometer2 me-2"></i>Optimize App
                    </button>
                    
                    <button type="button" class="btn btn-outline-info" onclick="viewLogs()">
                        <i class="bi bi-file-text me-2"></i>View Logs
                    </button>
                    
                    <button type="button" class="btn btn-outline-warning" onclick="backupDatabase()">
                        <i class="bi bi-download me-2"></i>Backup Database
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
    showInfo('Fitur ini akan segera tersedia');
}

function optimizeApp() {
    showInfo('Fitur ini akan segera tersedia');
}

function viewLogs() {
    showInfo('Fitur ini akan segera tersedia');
}

function backupDatabase() {
    showInfo('Fitur ini akan segera tersedia');
}
</script>
@endpush
