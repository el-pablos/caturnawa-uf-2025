@extends('layouts.peserta')

@section('title', 'Detail Kompetisi')

@section('page-title', $competition->name)

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('peserta.competitions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        @if(!$existingRegistration && $competition->isRegistrationOpen())
            @if($canRegister)
                <button type="button" class="btn btn-peserta-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-plus-circle me-2"></i>Daftar Sekarang
                </button>
            @else
                <button type="button" class="btn btn-secondary" disabled title="Anda sudah terdaftar di kompetisi lain">
                    <i class="bi bi-lock-fill me-2"></i>Tidak Dapat Mendaftar
                </button>
            @endif
        @endif
    </div>
@endsection

@section('content')
<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->has('registration_conflict'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-lock-fill me-2"></i><strong>Registrasi Ditolak - Auto Lock Aktif</strong>
        <hr>
        @foreach($errors->get('registration_conflict') as $conflictErrors)
            @if(is_array($conflictErrors))
                @foreach($conflictErrors as $error)
                    <div class="mb-1">• {{ $error }}</div>
                @endforeach
            @else
                <div class="mb-1">• {{ $conflictErrors }}</div>
            @endif
        @endforeach
        <hr>
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Sistem auto lock mencegah peserta mendaftar di multiple kompetisi untuk menjaga fairness.
        </small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Auto Lock Warning -->
@if(!$canRegister && !$existingRegistration)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Tidak Dapat Mendaftar</strong>
        <hr>
        <p class="mb-2">Anda sudah terdaftar di kompetisi lain. Sistem auto lock mencegah pendaftaran multiple kompetisi.</p>
        <strong>Kompetisi yang sudah diikuti:</strong>
        <ul class="mb-2">
            @foreach($userRegistrations as $registration)
                <li>{{ $registration->competition->name }} (Status: {{ ucfirst($registration->status) }})</li>
            @endforeach
        </ul>
        <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Untuk menjaga fairness, setiap peserta hanya boleh mengikuti satu kompetisi.
        </small>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Competition Details -->
    <div class="col-lg-8">
        <div class="peserta-card mb-4">
            <div class="card-body">
                <!-- Competition Image -->
                @if($competition->image)
                    <img src="{{ $competition->image_url }}" class="img-fluid rounded mb-4" alt="{{ $competition->name }}">
                @endif

                <!-- Competition Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Kategori</h6>
                        <span class="badge bg-primary fs-6">{{ $competition->category }}</span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Tipe Kompetisi</h6>
                        <span class="badge bg-info fs-6">
                            {{ $competition->is_team_competition ? 'Tim' : 'Individual' }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <h5 class="mb-3">Deskripsi</h5>
                <div class="mb-4">
                    {!! nl2br(e($competition->description)) !!}
                </div>

                <!-- Rules -->
                @if($competition->rules)
                    <h5 class="mb-3">Peraturan</h5>
                    <div class="mb-4">
                        @if(is_array($competition->rules))
                            <ul class="list-unstyled">
                                @foreach($competition->rules as $rule)
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>{{ $rule }}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! nl2br(e($competition->rules)) !!}
                        @endif
                    </div>
                @endif

                <!-- Prizes -->
                @if($competition->prizes)
                    <h5 class="mb-3">Hadiah</h5>
                    <div class="mb-4">
                        @if(is_array($competition->prizes))
                            <ul class="list-unstyled">
                                @foreach($competition->prizes as $prize)
                                    <li class="mb-2"><i class="bi bi-trophy text-warning me-2"></i>{{ $prize }}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! nl2br(e($competition->prizes)) !!}
                        @endif
                    </div>
                @endif

                <!-- Requirements -->
                @if($competition->requirements)
                    <h5 class="mb-3">Persyaratan</h5>
                    <div class="mb-4">
                        @if(is_array($competition->requirements))
                            <ul class="list-unstyled">
                                @foreach($competition->requirements as $requirement)
                                    <li class="mb-2"><i class="bi bi-arrow-right text-primary me-2"></i>{{ $requirement }}</li>
                                @endforeach
                            </ul>
                        @else
                            {!! nl2br(e($competition->requirements)) !!}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Competition Stats & Registration -->
    <div class="col-lg-4">
        <!-- Countdown Timer -->
        @if($competition->registration_deadline && $competition->days_left > 0)
        <div class="peserta-card mb-4">
            <div class="card-body text-center">
                <h6 class="text-warning mb-3">
                    <i class="bi bi-clock me-2"></i>Batas Waktu Pendaftaran
                </h6>
                <div class="countdown-timer mb-3">
                    <div class="display-4 fw-bold text-primary">{{ $competition->days_left }}</div>
                    <div class="text-muted">Hari Tersisa</div>
                </div>
                <small class="text-muted">
                    Deadline: {{ $competition->registration_deadline->format('d M Y, H:i') }}
                </small>
            </div>
        </div>
        @endif

        <!-- Registration Status -->
        <div class="peserta-card mb-4">
            <div class="card-body text-center">
                @if($existingRegistration)
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-success">Sudah Terdaftar</h5>
                    <p class="text-muted">Anda sudah terdaftar dalam kompetisi ini</p>
                    <a href="{{ route('peserta.registrations.show', $existingRegistration) }}" class="btn btn-outline-success">
                        <i class="bi bi-eye me-2"></i>Lihat Detail
                    </a>
                @elseif($competition->isRegistrationOpen())
                    <div class="mb-3">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-primary">Pendaftaran Terbuka</h5>
                    <p class="text-muted">Daftar sekarang untuk mengikuti kompetisi ini</p>
                    <button type="button" class="btn btn-peserta-primary w-100" data-bs-toggle="modal" data-bs-target="#registerModal">
                        <i class="bi bi-plus-circle me-2"></i>Daftar Sekarang
                    </button>
                @else
                    <div class="mb-3">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-danger">Pendaftaran Ditutup</h5>
                    <p class="text-muted">Pendaftaran untuk kompetisi ini sudah ditutup</p>
                @endif
            </div>
        </div>

        <!-- Competition Stats -->
        <div class="peserta-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistik Kompetisi
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 text-primary mb-1">{{ $stats['participants_count'] }}</div>
                            <small class="text-muted">Peserta</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            @if($stats['slots_remaining'] !== null)
                                <div class="h4 text-warning mb-1">{{ $stats['slots_remaining'] }}</div>
                                <small class="text-muted">Slot Tersisa</small>
                            @else
                                <div class="h4 text-success mb-1">∞</div>
                                <small class="text-muted">Unlimited</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 text-info mb-1">{{ $stats['days_left'] }}</div>
                            <small class="text-muted">Hari Tersisa</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 text-success mb-1">Rp {{ number_format($competition->getCurrentPriceAttribute()) }}</div>
                            <small class="text-muted">Biaya</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Dates -->
        <div class="peserta-card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>Tanggal Penting
                </h6>
            </div>
            <div class="card-body">
                @if($competition->timeline && count($competition->timeline) > 0)
                    <div class="timeline">
                        @foreach($competition->timeline as $item)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $item['color'] }} {{ $item['status'] === 'completed' ? '' : 'opacity-50' }}">
                                    <i class="bi {{ $item['icon'] }} text-white"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1 {{ $item['status'] === 'completed' ? 'text-success' : '' }}">
                                                {{ $item['title'] }}
                                                @if($item['status'] === 'completed')
                                                    <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $item['date']->format('d M Y, H:i') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $item['color'] }} {{ $item['status'] === 'completed' ? '' : 'opacity-75' }}">
                                            {{ $item['status'] === 'completed' ? 'Selesai' : 'Akan Datang' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">Timeline belum tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
@if(!$existingRegistration && $competition->isRegistrationOpen())
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Daftar Kompetisi: {{ $competition->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('peserta.competitions.register', $competition) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="institution" class="form-label">Asal Instansi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="institution" name="institution"
                                   value="{{ old('institution') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="logo_instansi" class="form-label">Logo Instansi</label>
                            <input type="file" class="form-control" id="logo_instansi" name="logo_instansi"
                                   accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="education_level" class="form-label">Tingkat Pendidikan <span class="text-danger">*</span></label>
                            <select class="form-select" id="education_level" name="education_level" required>
                                <option value="">Pilih Tingkat Pendidikan</option>
                                <option value="SMA" {{ old('education_level') === 'SMA' ? 'selected' : '' }}>SMA</option>
                                <option value="SMK" {{ old('education_level') === 'SMK' ? 'selected' : '' }}>SMK</option>
                                <option value="D3" {{ old('education_level') === 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                <option value="S1" {{ old('education_level') === 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                <option value="S2" {{ old('education_level') === 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                <option value="S3" {{ old('education_level') === 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="participant_category" class="form-label">Kategori Peserta <span class="text-danger">*</span></label>
                            <select class="form-select" id="participant_category" name="participant_category" required>
                                <option value="">Pilih Kategori Peserta</option>
                                @if(isset($participantCategories))
                                    @foreach($participantCategories as $key => $name)
                                        <option value="{{ $key }}" {{ old('participant_category') === $key ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                @else
                                    <option value="unas_student" {{ old('participant_category') === 'unas_student' ? 'selected' : '' }}>Mahasiswa UNAS</option>
                                    <option value="external_student" {{ old('participant_category') === 'external_student' ? 'selected' : '' }}>Mahasiswa Eksternal</option>
                                    <option value="high_school_student" {{ old('participant_category') === 'high_school_student' ? 'selected' : '' }}>Siswa SMA/SMK</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Pendaftaran</label>
                            <div class="card border-info">
                                <div class="card-body p-2">
                                    <div id="pricing-info">
                                        <div class="text-muted small">Pilih kategori peserta untuk melihat harga</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="emergency_contact" class="form-label">Kontak Darurat</label>
                            <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" 
                                   value="{{ old('emergency_contact') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="emergency_phone" class="form-label">Telepon Darurat</label>
                            <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" 
                                   value="{{ old('emergency_phone') }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="special_needs" class="form-label">Kebutuhan Khusus</label>
                        <textarea class="form-control" id="special_needs" name="special_needs" rows="3" 
                                  placeholder="Jelaskan jika ada kebutuhan khusus...">{{ old('special_needs') }}</textarea>
                    </div>

                    @if($competition->is_team_competition)
                        <hr>
                        <h6>Informasi Tim</h6>
                        <div class="mb-3">
                            <label for="team_name" class="form-label">Nama Tim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="team_name" name="team_name" 
                                   value="{{ old('team_name') }}" required>
                        </div>
                        
                        <div id="team-members">
                            <label class="form-label">Anggota Tim <span class="text-danger">*</span></label>
                            <div class="team-member mb-4 border rounded p-3">
                                <h6 class="text-primary mb-3">Peserta 1</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" name="team_members[0][name]"
                                               placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="email" class="form-control" name="team_members[0][email]"
                                               placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" class="form-control" name="team_members[0][phone]"
                                               placeholder="No Handphone" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="file" class="form-control" name="team_members[0][foto]"
                                               accept="image/*" required>
                                        <small class="text-muted">Foto Peserta (JPG, PNG. Max 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTeamMember()">
                            <i class="bi bi-plus me-2"></i>Tambah Anggota (1/5)
                        </button>
                    @endif
                    
                    <hr>
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Informasi Pembayaran</h6>
                        <p class="mb-0">Biaya pendaftaran: <strong>Rp {{ number_format($competition->getCurrentPriceAttribute()) }}</strong></p>
                        <small>Setelah mendaftar, Anda akan diarahkan ke halaman pembayaran.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-peserta-primary">
                        <i class="bi bi-check-circle me-2"></i>Daftar & Lanjut Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

/* Enhanced Card Styles */
.peserta-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
}

.peserta-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}

.peserta-card .card-body {
    padding: 2rem;
}

.peserta-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.25rem 2rem;
    font-weight: 600;
}

/* Content Spacing */
.peserta-card h5 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.peserta-card h6 {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 1rem;
}

.peserta-card p,
.peserta-card li {
    line-height: 1.7;
    color: #4a5568;
    margin-bottom: 1rem;
}

.peserta-card ul {
    padding-left: 0;
}

.peserta-card li {
    padding: 0.75rem 1rem;
    background: #f7fafc;
    border-radius: 8px;
    margin-bottom: 0.75rem;
    border-left: 4px solid #667eea;
    transition: all 0.2s ease;
}

.peserta-card li:hover {
    background: #edf2f7;
    transform: translateX(4px);
}

/* Badge Improvements */
.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
    border-radius: 20px;
}

/* Button Improvements */
.btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-peserta-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
}

.btn-peserta-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    color: white;
}

/* Stats Cards */
.text-center .h4 {
    font-weight: 700;
    font-size: 2rem;
}

/* Modal Improvements */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 12px 12px 0 0;
}

.modal-body {
    padding: 2rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

/* Alert Improvements */
.alert {
    border-radius: 8px;
    border: none;
    padding: 1.25rem;
}

.alert-info {
    background: linear-gradient(135deg, #e6fffa 0%, #f0fff4 100%);
    color: #2d3748;
}

/* Image Improvements */
.img-fluid {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Responsive Improvements */
@media (max-width: 768px) {
    .peserta-card .card-body {
        padding: 1.5rem;
    }

    .peserta-card .card-header {
        padding: 1rem 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let teamMemberIndex = 1;

function addTeamMember() {
    const container = document.getElementById('team-members');
    const currentMembers = container.querySelectorAll('.team-member').length;

    // Batasi maksimal 5 anggota
    if (currentMembers >= 5) {
        alert('Maksimal 5 anggota tim');
        return;
    }

    const memberHtml = `
        <div class="team-member mb-4 border rounded p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary mb-0">Peserta ${teamMemberIndex + 1}</h6>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeTeamMember(this)">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <input type="text" class="form-control" name="team_members[${teamMemberIndex}][name]"
                           placeholder="Nama Lengkap" required>
                </div>
                <div class="col-md-6 mb-2">
                    <input type="email" class="form-control" name="team_members[${teamMemberIndex}][email]"
                           placeholder="Email" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <input type="text" class="form-control" name="team_members[${teamMemberIndex}][phone]"
                           placeholder="No Handphone" required>
                </div>
                <div class="col-md-6 mb-2">
                    <input type="file" class="form-control" name="team_members[${teamMemberIndex}][foto]"
                           accept="image/*" required>
                    <small class="text-muted">Foto Peserta (JPG, PNG. Max 2MB)</small>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', memberHtml);
    teamMemberIndex++;

    // Update button text
    updateAddButtonText();
}

function removeTeamMember(button) {
    const container = document.getElementById('team-members');
    const currentMembers = container.querySelectorAll('.team-member').length;

    // Minimal 1 anggota
    if (currentMembers <= 1) {
        alert('Minimal harus ada 1 anggota tim');
        return;
    }

    button.closest('.team-member').remove();

    // Update nomor peserta
    updateMemberNumbers();
    updateAddButtonText();
}

function updateMemberNumbers() {
    const members = document.querySelectorAll('.team-member');
    members.forEach((member, index) => {
        const title = member.querySelector('h6');
        if (title) {
            title.textContent = `Peserta ${index + 1}`;
        }
    });
}

function updateAddButtonText() {
    const container = document.getElementById('team-members');
    const currentMembers = container.querySelectorAll('.team-member').length;
    const addButton = document.querySelector('button[onclick="addTeamMember()"]');

    if (addButton) {
        if (currentMembers >= 5) {
            addButton.style.display = 'none';
        } else {
            addButton.style.display = 'inline-block';
            addButton.innerHTML = `<i class="bi bi-plus me-2"></i>Tambah Anggota (${currentMembers}/5)`;
        }
    }
}

// Initialize button text on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAddButtonText();

    // Handle participant category change for dynamic pricing
    const participantCategorySelect = document.getElementById('participant_category');
    const pricingInfo = document.getElementById('pricing-info');

    if (participantCategorySelect && pricingInfo) {
        // Pricing data from server
        const pricingSummary = @json($pricingSummary ?? []);

        participantCategorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;

            if (selectedCategory && pricingSummary[selectedCategory]) {
                const priceData = pricingSummary[selectedCategory];
                const currentPrice = priceData.current_price;
                const savings = priceData.savings;

                let html = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">Rp ${new Intl.NumberFormat('id-ID').format(currentPrice.amount)}</span>
                        <small class="text-muted">${currentPrice.phase_name}</small>
                    </div>
                `;

                if (savings && savings.savings > 0) {
                    html += `
                        <div class="mt-1">
                            <small class="text-success">
                                <i class="bi bi-arrow-down"></i>
                                Hemat Rp ${new Intl.NumberFormat('id-ID').format(savings.savings)}
                                (${savings.savings_percentage}%)
                            </small>
                        </div>
                    `;

                    if (savings.days_left > 0) {
                        html += `
                            <div class="mt-1">
                                <small class="text-warning">
                                    <i class="bi bi-clock"></i>
                                    ${savings.days_left} hari lagi harga naik
                                </small>
                            </div>
                        `;
                    }
                }

                pricingInfo.innerHTML = html;
            } else {
                pricingInfo.innerHTML = '<div class="text-muted small">Pilih kategori peserta untuk melihat harga</div>';
            }
        });
    }
});
</script>
@endpush
