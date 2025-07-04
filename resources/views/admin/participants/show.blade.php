@extends('layouts.admin')

@section('title', 'Detail Peserta - ' . $participant->name)

@section('breadcrumb')
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0">Detail Peserta</h1>
        <nav class="ms-auto">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.participants.index') }}">Data Peserta</a></li>
                <li class="breadcrumb-item active">{{ $participant->name }}</li>
            </ol>
        </nav>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Participant Profile -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>Profil Peserta
                </h5>
            </div>
            <div class="card-body text-center">
                <img src="{{ $participant->avatar_url }}" class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                <h5 class="mb-1">{{ $participant->name }}</h5>
                <p class="text-muted mb-3">{{ $participant->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($participant->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-danger">Tidak Aktif</span>
                    @endif
                    
                    @if($participant->email_verified_at)
                        <span class="badge bg-info">Email Verified</span>
                    @else
                        <span class="badge bg-warning">Email Unverified</span>
                    @endif
                </div>
                
                <div class="text-start">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Institusi:</strong></div>
                        <div class="col-7">{{ $participant->institution ?? 'Tidak ada' }}</div>
                    </div>
                    
                    @if($participant->student_id)
                    <div class="row mb-2">
                        <div class="col-5"><strong>NIM/NIS:</strong></div>
                        <div class="col-7">{{ $participant->student_id }}</div>
                    </div>
                    @endif
                    
                    @if($participant->birth_date)
                    <div class="row mb-2">
                        <div class="col-5"><strong>Tanggal Lahir:</strong></div>
                        <div class="col-7">{{ $participant->birth_date->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    
                    @if($participant->gender)
                    <div class="row mb-2">
                        <div class="col-5"><strong>Jenis Kelamin:</strong></div>
                        <div class="col-7">{{ $participant->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                    @endif
                    
                    <div class="row mb-2">
                        <div class="col-5"><strong>Bergabung:</strong></div>
                        <div class="col-7">{{ $participant->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        @if($participant->address || $participant->emergency_contact_name)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt me-2"></i>Informasi Kontak
                </h5>
            </div>
            <div class="card-body">
                @if($participant->address)
                <div class="mb-3">
                    <strong>Alamat:</strong>
                    <p class="mb-1">{{ $participant->address }}</p>
                    @if($participant->city || $participant->province)
                        <small class="text-muted">
                            {{ $participant->city }}{{ $participant->city && $participant->province ? ', ' : '' }}{{ $participant->province }}
                            {{ $participant->postal_code ? ' ' . $participant->postal_code : '' }}
                        </small>
                    @endif
                </div>
                @endif
                
                @if($participant->emergency_contact_name)
                <div class="mb-2">
                    <strong>Kontak Darurat:</strong>
                    <p class="mb-1">{{ $participant->emergency_contact_name }}</p>
                    @if($participant->emergency_contact_phone)
                        <small class="text-muted">{{ $participant->emergency_contact_phone }}</small>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-8">
        <!-- Registrations -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>Kompetisi yang Diikuti
                </h5>
            </div>
            <div class="card-body">
                @if($participant->registrations->count() > 0)
                    @foreach($participant->registrations as $registration)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1">{{ $registration->competition->name }}</h6>
                                    <small class="text-muted">{{ $registration->competition->category }}</small>
                                </div>
                                <span class="badge bg-{{ $registration->status === 'confirmed' ? 'success' : ($registration->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($registration->status) }}
                                </span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <small><strong>No. Registrasi:</strong> {{ $registration->registration_number }}</small>
                                </div>
                                <div class="col-md-6">
                                    <small><strong>Tanggal Daftar:</strong> {{ $registration->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            
                            @if($registration->team_name)
                                <div class="mt-2">
                                    <small><strong>Nama Tim:</strong> {{ $registration->team_name }}</small>
                                </div>
                            @endif
                            
                            <!-- Payment Info -->
                            @if($registration->payment)
                                <div class="mt-2">
                                    <small><strong>Status Pembayaran:</strong> 
                                        <span class="badge bg-{{ $registration->payment->status === 'success' ? 'success' : ($registration->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($registration->payment->status) }}
                                        </span>
                                        @if($registration->payment->is_confirmed)
                                            <span class="badge bg-info">Dikonfirmasi</span>
                                        @endif
                                    </small>
                                </div>
                            @endif
                            
                            <!-- Submissions -->
                            @if($registration->submissions->count() > 0)
                                <div class="mt-3">
                                    <strong>Karya yang Disubmit:</strong>
                                    @foreach($registration->submissions as $submission)
                                        <div class="border-start border-primary ps-3 mt-2">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $submission->title }}</h6>
                                                    @if($submission->description)
                                                        <p class="small text-muted mb-1">{{ Str::limit($submission->description, 100) }}</p>
                                                    @endif
                                                    <small class="text-muted">Submit: {{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'Belum disubmit' }}</small>
                                                </div>
                                                <span class="badge bg-{{ $submission->status === 'submitted' ? 'success' : ($submission->status === 'draft' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($submission->status) }}
                                                </span>
                                            </div>
                                            
                                            <!-- Scores -->
                                            @if($submission->scores->count() > 0)
                                                <div class="mt-2">
                                                    <small><strong>Penilaian:</strong></small>
                                                    <div class="row">
                                                        @foreach($submission->scores as $score)
                                                            <div class="col-md-6">
                                                                <small class="text-muted">
                                                                    {{ $score->jury->name ?? 'Unknown' }}: 
                                                                    <strong>{{ $score->total_score }}/100</strong>
                                                                    @if($score->is_final)
                                                                        <span class="badge bg-success">Final</span>
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @php
                                                        $finalScores = $submission->scores->where('is_final', true);
                                                        $averageScore = $finalScores->count() > 0 ? $finalScores->avg('total_score') : null;
                                                    @endphp
                                                    @if($averageScore)
                                                        <div class="mt-1">
                                                            <small><strong>Rata-rata:</strong> {{ number_format($averageScore, 2) }}/100</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-trophy fs-1 text-muted"></i>
                        <h6 class="mt-2 text-muted">Belum Mengikuti Kompetisi</h6>
                        <p class="text-muted">Peserta ini belum mendaftar untuk kompetisi apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
