@extends('layouts.simple')

@php
    $seoPage = 'competition';
    $seoData = [
        'title' => $competition->name . ' - UNAS Fest 2025',
        'description' => $competition->description,
        'keywords' => 'kompetisi ' . strtolower($competition->category ?? 'umum') . ', ' . strtolower($competition->name) . ', unas fest 2025',
        'og_image' => $competition->image ? asset('storage/' . $competition->image) : null,
    ];
@endphp

@section('title', $competition->name . ' - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('public.home') }}">
                    <i class="bi bi-house me-1"></i>Beranda
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('public.competitions') }}">Kompetisi</a>
            </li>
            <li class="breadcrumb-item active">{{ $competition->name }}</li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5" data-aos="fade-down">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3" data-aos="zoom-in" data-aos-delay="200">
                        <i class="bi bi-trophy me-3"></i>{{ $competition->name }}
                    </h1>
                    <p class="lead mb-4">
                        {{ $competition->description ?? 'Kompetisi inovatif yang menantang kreativitas dan kemampuan peserta.' }}
                    </p>
                    
                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($competition->registration_start > now())
                            <span class="badge bg-warning fs-5 px-4 py-2">
                                <i class="bi bi-clock me-2"></i>Segera Dibuka
                            </span>
                        @elseif($competition->registration_end < now())
                            <span class="badge bg-danger fs-5 px-4 py-2">
                                <i class="bi bi-x-circle me-2"></i>Pendaftaran Ditutup
                            </span>
                        @else
                            <span class="badge bg-success fs-5 px-4 py-2">
                                <i class="bi bi-check-circle me-2"></i>Pendaftaran Dibuka
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="row justify-content-center">
                        @if($competition->registration_start <= now() && $competition->registration_end >= now())
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100">
                                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                </a>
                            </div>
                        @endif
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn btn-light btn-lg w-100">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Kompetisi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Image -->
    @if($competition->image)
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white text-center">
                        <h3 class="card-title mb-0">Gambar Kompetisi</h3>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $competition->image) }}" 
                             alt="{{ $competition->name }}" 
                             class="img-fluid rounded shadow" 
                             style="max-height: 400px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Info -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4" data-aos="fade-up">
                <i class="bi bi-info-circle text-primary"></i> 
                Informasi Kompetisi
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Detail Kompetisi</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3" data-aos="flip-left" data-aos-delay="300">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-event text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="text-primary">Periode Pendaftaran</h5>
                                    <p class="mb-0">
                                        {{ $competition->registration_start ? \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') : 'TBA' }} - 
                                        {{ $competition->registration_end ? \Carbon\Carbon::parse($competition->registration_end)->format('d M Y') : 'TBA' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" data-aos="flip-right" data-aos-delay="400">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="bi bi-people text-success mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="text-success">Maksimal Tim</h5>
                                    <p class="mb-0">{{ $competition->max_team_members ?? 'Maksimal 3' }} orang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Timeline -->
    @if($competition->competition_start || $competition->competition_end)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4">
                    <i class="bi bi-calendar-week text-success"></i> 
                    Jadwal Kompetisi
                </h2>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="card-title mb-0">Timeline Kompetisi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($competition->competition_start)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="bi bi-play-circle text-success mb-2" style="font-size: 2rem;"></i>
                                            <h5 class="text-success">Mulai Kompetisi</h5>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($competition->competition_start)->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($competition->competition_end)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-danger">
                                        <div class="card-body text-center">
                                            <i class="bi bi-stop-circle text-danger mb-2" style="font-size: 2rem;"></i>
                                            <h5 class="text-danger">Selesai Kompetisi</h5>
                                            <p class="mb-0">{{ \Carbon\Carbon::parse($competition->competition_end)->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rules & Requirements -->
    @if($competition->rules)
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <div class="text-center">
                            <i class="bi bi-list-check fs-2 text-white mb-2"></i>
                            <h3 class="text-white mb-0 fw-bold">Syarat & Ketentuan</h3>
                            <small class="text-white-50">Harap dibaca dengan teliti sebelum mendaftar</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="terms-content">
                            @if(is_array($competition->rules))
                                <div class="rules-list">
                                    @foreach($competition->rules as $index => $rule)
                                        <div class="rule-item d-flex align-items-start mb-3">
                                            <div class="rule-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; flex-shrink: 0;">
                                                <span class="fw-bold">{{ $index + 1 }}</span>
                                            </div>
                                            <div class="rule-text">
                                                <p class="mb-0">{{ $rule }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="formatted-content">
                                    {!! nl2br(e($competition->rules)) !!}
                                </div>
                            @endif
                        </div>
                        
                        <div class="alert alert-info mt-4 border-0" style="background: linear-gradient(45deg, #e3f2fd, #f3e5f5);">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                <div>
                                    <strong>Penting:</strong> Dengan mendaftar, Anda menyetujui semua syarat dan ketentuan yang telah ditetapkan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Competition Descriptions -->
    @if($descriptions && $descriptions->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4">
                    <i class="bi bi-file-text text-primary"></i> 
                    Deskripsi Kompetisi
                </h2>
            </div>
            <div class="col-12">
                @foreach($descriptions as $description)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>{{ $description->title }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="prose">
                                {!! nl2br(e($description->content)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-rocket text-primary"></i> 
                Siap Bergabung?
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Bergabunglah dengan {{ $competition->name }}</h3>
                    <p class="mb-0">Jangan lewatkan kesempatan emas ini untuk menunjukkan kemampuan terbaikmu!</p>
                </div>
                <div class="card-body text-center">
                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Pendaftaran Dibuka!</strong> Segera daftar sebelum kuota penuh.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.contact') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="bi bi-question-circle me-2"></i>Ada Pertanyaan?
                                </a>
                            </div>
                        </div>
                    @elseif($competition->registration_start > now())
                        <div class="alert alert-warning">
                            <i class="bi bi-clock me-2"></i>
                            <strong>Pendaftaran Belum Dibuka!</strong> Tunggu tanggal {{ \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') }}.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-arrow-left me-2"></i>Lihat Kompetisi Lain
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle me-2"></i>
                            <strong>Pendaftaran Sudah Ditutup!</strong> Jangan lewatkan kompetisi berikutnya.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-arrow-left me-2"></i>Lihat Kompetisi Lain
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
