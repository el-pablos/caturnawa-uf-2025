@extends('layouts.admin')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')

@section('header-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        @if($registration->payment && $registration->payment->isSuccess())
        <a href="{{ route('payment.receipt', $registration->payment) }}" class="btn btn-primary">
            <i class="bi bi-receipt me-2"></i>Download Struk
        </a>
        @endif
        <a href="{{ route('peserta.registrations.ticket', $registration) }}" class="btn btn-success">
            <i class="bi bi-ticket-perforated me-2"></i>Lihat E-Ticket
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Registration Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-check me-2"></i>Informasi Pendaftaran
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">No. Registrasi:</td>
                                <td><code>{{ $registration->registration_number }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'paid' ? 'info' : ($registration->status === 'pending' ? 'warning' : 'danger')) }}">
                                        {{ $registration->status === 'paid' ? 'Dibayar' : ucfirst($registration->status) }}
                                    </span>
                                    @if($registration->is_locked)
                                        <br>
                                        <span class="badge bg-warning mt-1">
                                            <i class="bi bi-lock-fill"></i> Locked
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if($registration->is_locked)
                            <tr>
                                <td class="fw-semibold">Lock Information:</td>
                                <td>
                                    <div class="small">
                                        <strong>Reason:</strong> {{ $registration->lock_reason ?: 'No reason provided' }}<br>
                                        <strong>Locked at:</strong> {{ $registration->locked_at ? $registration->locked_at->format('d M Y H:i:s') : 'Unknown' }}<br>
                                        @if($registration->lockedBy)
                                            <strong>Locked by:</strong> {{ $registration->lockedBy->name }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold">Tanggal Daftar:</td>
                                <td>{{ $registration->registered_at ? $registration->registered_at->format('d M Y H:i:s') : $registration->created_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @if($registration->confirmed_at)
                            <tr>
                                <td class="fw-semibold">Tanggal Konfirmasi:</td>
                                <td>{{ $registration->confirmed_at->format('d M Y H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Biaya Pendaftaran:</td>
                                <td class="fw-bold text-success">Rp {{ number_format($registration->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Asal Instansi:</td>
                                <td>{{ $registration->institution ?: '-' }}</td>
                            </tr>
                            @if($registration->logo_instansi)
                            <tr>
                                <td class="fw-semibold">Logo Instansi:</td>
                                <td>
                                    <img src="{{ asset('storage/' . $registration->logo_instansi) }}"
                                         class="img-thumbnail" alt="Logo Instansi" style="max-width: 100px;">
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="fw-semibold">Telepon:</td>
                                <td>{{ $registration->phone ?: $registration->user->phone }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participant Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>Detail Peserta
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        @if($registration->user->profile_photo)
                            <img src="{{ asset('storage/' . $registration->user->profile_photo) }}" 
                                 class="img-fluid rounded-circle" alt="Profile Photo" style="max-width: 120px;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nama Lengkap:</td>
                                <td>{{ $registration->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td>{{ $registration->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tanggal Lahir:</td>
                                <td>{{ $registration->user->date_of_birth ? $registration->user->date_of_birth->format('d M Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Jenis Kelamin:</td>
                                <td>{{ $registration->user->gender ? ucfirst($registration->user->gender) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alamat:</td>
                                <td>{{ $registration->user->address ?: $registration->address ?: '-' }}</td>
                            </tr>
                            @if($registration->student_id_number)
                            <tr>
                                <td class="fw-semibold">NIM/ID Mahasiswa:</td>
                                <td>{{ $registration->student_id_number }}</td>
                            </tr>
                            @endif
                            @if($registration->major_study_program)
                            <tr>
                                <td class="fw-semibold">Program Studi:</td>
                                <td>{{ $registration->major_study_program }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Participant Information -->
        @if($registration->academic_year || $registration->semester || $registration->birth_place || $registration->nationality || $registration->religion)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informasi Akademik & Personal
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($registration->academic_year)
                            <tr>
                                <td class="fw-semibold">Tahun Akademik:</td>
                                <td>{{ $registration->academic_year }}</td>
                            </tr>
                            @endif
                            @if($registration->semester)
                            <tr>
                                <td class="fw-semibold">Semester:</td>
                                <td>{{ $registration->semester }}</td>
                            </tr>
                            @endif
                            @if($registration->birth_place)
                            <tr>
                                <td class="fw-semibold">Tempat Lahir:</td>
                                <td>{{ $registration->birth_place }}</td>
                            </tr>
                            @endif
                            @if($registration->birth_date)
                            <tr>
                                <td class="fw-semibold">Tanggal Lahir:</td>
                                <td>{{ \Carbon\Carbon::parse($registration->birth_date)->format('d M Y') }}</td>
                            </tr>
                            @endif
                            @if($registration->nationality)
                            <tr>
                                <td class="fw-semibold">Kewarganegaraan:</td>
                                <td>{{ $registration->nationality }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($registration->religion)
                            <tr>
                                <td class="fw-semibold">Agama:</td>
                                <td>{{ $registration->religion }}</td>
                            </tr>
                            @endif
                            @if($registration->city)
                            <tr>
                                <td class="fw-semibold">Kota:</td>
                                <td>{{ $registration->city }}</td>
                            </tr>
                            @endif
                            @if($registration->province)
                            <tr>
                                <td class="fw-semibold">Provinsi:</td>
                                <td>{{ $registration->province }}</td>
                            </tr>
                            @endif
                            @if($registration->postal_code)
                            <tr>
                                <td class="fw-semibold">Kode Pos:</td>
                                <td>{{ $registration->postal_code }}</td>
                            </tr>
                            @endif
                            @if($registration->blood_type)
                            <tr>
                                <td class="fw-semibold">Golongan Darah:</td>
                                <td>{{ $registration->blood_type }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Documents & Files -->
        @if($registration->id_card_photo || $registration->photo_3x4 || $registration->student_id_card_photo || $registration->university_letter || $registration->health_certificate)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark me-2"></i>Dokumen yang Diupload
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($registration->id_card_photo)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-card-image me-2"></i>Foto KTP</h6>
                                <img src="{{ asset('storage/' . $registration->id_card_photo) }}" 
                                     class="img-fluid rounded" alt="Foto KTP" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($registration->photo_3x4)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-person-square me-2"></i>Foto 3x4</h6>
                                <img src="{{ asset('storage/' . $registration->photo_3x4) }}" 
                                     class="img-fluid rounded" alt="Foto 3x4" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($registration->student_id_card_photo)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-card-text me-2"></i>Foto Kartu Mahasiswa</h6>
                                <img src="{{ asset('storage/' . $registration->student_id_card_photo) }}" 
                                     class="img-fluid rounded" alt="Kartu Mahasiswa" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($registration->university_letter)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-file-text me-2"></i>Surat Keterangan Universitas</h6>
                                <a href="{{ asset('storage/' . $registration->university_letter) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($registration->health_certificate)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-heart-pulse me-2"></i>Sertifikat Kesehatan</h6>
                                <a href="{{ asset('storage/' . $registration->health_certificate) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($registration->toefl_ielts_certificate)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="bi bi-translate me-2"></i>Sertifikat TOEFL/IELTS</h6>
                                <p class="small mb-2">Skor: {{ $registration->toefl_ielts_score ?: 'Tidak disebutkan' }}</p>
                                <a href="{{ asset('storage/' . $registration->toefl_ielts_certificate) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Health & Special Needs -->
        @if($registration->health_conditions || $registration->allergies || $registration->medications || $registration->dietary_restrictions || $registration->special_needs)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-heart-pulse me-2"></i>Informasi Kesehatan & Kebutuhan Khusus
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if($registration->health_conditions)
                        <div class="mb-3">
                            <strong>Kondisi Kesehatan:</strong><br>
                            <span class="text-muted">{{ $registration->health_conditions }}</span>
                        </div>
                        @endif
                        @if($registration->allergies)
                        <div class="mb-3">
                            <strong>Alergi:</strong><br>
                            <span class="text-muted">{{ $registration->allergies }}</span>
                        </div>
                        @endif
                        @if($registration->medications)
                        <div class="mb-3">
                            <strong>Obat-obatan:</strong><br>
                            <span class="text-muted">{{ $registration->medications }}</span>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($registration->dietary_restrictions)
                        <div class="mb-3">
                            <strong>Pembatasan Diet:</strong><br>
                            <span class="text-muted">{{ $registration->dietary_restrictions }}</span>
                        </div>
                        @endif
                        @if($registration->special_needs)
                        <div class="mb-3">
                            <strong>Kebutuhan Khusus:</strong><br>
                            <span class="text-muted">{{ $registration->special_needs }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Competition Specific Information -->
        @if($registration->zoom_account_email || $registration->previous_debate_experience || $registration->motivation_letter || $registration->preferred_debate_topics || $registration->language_proficiency_level)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>Informasi Spesifik Kompetisi
                </h5>
            </div>
            <div class="card-body">
                @if($registration->zoom_account_email)
                <div class="mb-3">
                    <strong>Email Akun Zoom:</strong><br>
                    <span class="text-muted">{{ $registration->zoom_account_email }}</span>
                </div>
                @endif
                @if($registration->previous_debate_experience)
                <div class="mb-3">
                    <strong>Pengalaman Debat Sebelumnya:</strong><br>
                    <span class="text-muted">{{ $registration->previous_debate_experience }}</span>
                </div>
                @endif
                @if($registration->motivation_letter)
                <div class="mb-3">
                    <strong>Surat Motivasi:</strong><br>
                    <span class="text-muted">{{ $registration->motivation_letter }}</span>
                </div>
                @endif
                @if($registration->preferred_debate_topics)
                <div class="mb-3">
                    <strong>Topik Debat Favorit:</strong><br>
                    <span class="text-muted">{{ $registration->preferred_debate_topics }}</span>
                </div>
                @endif
                @if($registration->language_proficiency_level)
                <div class="mb-3">
                    <strong>Level Kemampuan Bahasa:</strong><br>
                    <span class="text-muted">{{ $registration->language_proficiency_level }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Competition Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>Detail Kompetisi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nama Kompetisi:</td>
                                <td>{{ $registration->competition->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kategori:</td>
                                <td>{{ $registration->competition->category }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tipe:</td>
                                <td>{{ $registration->competition->is_team_competition ? 'Tim' : 'Individual' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Mulai Kompetisi:</td>
                                <td>{{ $registration->competition->competition_start ? $registration->competition->competition_start->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Selesai Kompetisi:</td>
                                <td>{{ $registration->competition->competition_end ? $registration->competition->competition_end->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Deadline Submission:</td>
                                <td>{{ $registration->competition->submission_deadline ? $registration->competition->submission_deadline->format('d M Y H:i') : 'TBA' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Members (if applicable) -->
        @if($registration->team_members && count($registration->team_members) > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>Anggota Tim
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($registration->team_members as $index => $member)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if(isset($member['foto']) && $member['foto'])
                                            <img src="{{ asset('storage/' . $member['foto']) }}"
                                                 class="rounded-circle" alt="Foto {{ $member['name'] }}"
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-person-fill text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-2">Peserta {{ $index + 1 }}</h6>
                                        <p class="card-text mb-0">
                                            <strong>Nama:</strong> {{ $member['name'] }}<br>
                                            @if(isset($member['email']))
                                            <strong>Email:</strong> {{ $member['email'] }}<br>
                                            @endif
                                            @if(isset($member['phone']))
                                            <strong>No HP:</strong> {{ $member['phone'] }}<br>
                                            @endif
                                            @if(isset($member['student_id']))
                                            <strong>NIM/ID:</strong> {{ $member['student_id'] }}<br>
                                            @endif
                                            @if(isset($member['role']))
                                            <strong>Peran:</strong> {{ $member['role'] }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">

        <!-- Payment Information -->
        @if($registration->payment)
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-semibold">Order ID:</td>
                        <td><code>{{ $registration->payment->order_id }}</code></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Status:</td>
                        <td>
                            <span class="badge bg-{{ $registration->payment->status_class }}">
                                {{ $registration->payment->status_label }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold">Jumlah:</td>
                        <td class="fw-bold">Rp {{ number_format($registration->payment->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($registration->payment->paid_at)
                    <tr>
                        <td class="fw-semibold">Dibayar:</td>
                        <td>{{ $registration->payment->paid_at->format('d M Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
                <div class="d-grid">
                    <a href="{{ route('admin.payments.show', $registration->payment) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-2"></i>Lihat Detail Pembayaran
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Emergency Contact -->
        @if($registration->emergency_contact)
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-telephone me-2"></i>Kontak Darurat
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td class="fw-semibold">Nama:</td>
                        <td>{{ $registration->emergency_contact }}</td>
                    </tr>
                    @if($registration->emergency_phone)
                    <tr>
                        <td class="fw-semibold">Telepon:</td>
                        <td>{{ $registration->emergency_phone }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

