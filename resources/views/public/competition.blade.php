@extends('layouts.simple')

@php
    $seoPage = 'competition';
    $seoData = [
        'title' => $competition->name . ' - Caturnawa UNAS FEST 2025',
        'description' => $competition->description,
        'keywords' => 'kompetisi ' . strtolower($competition->category ?? 'umum') . ', ' . strtolower($competition->name) . ', Caturnawa UNAS FEST 2025',
        'og_image' => $competition->image ? asset('storage/' . $competition->image) : null,
    ];
@endphp

@push('styles')
<style>
    .modern-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
    }
    
    .modern-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        letter-spacing: -1px;
    }
    
    .modern-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        line-height: 1.6;
    }
    
    .modern-btn {
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(255,107,107,0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    .modern-btn:hover::before {
        left: 100%;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(255,107,107,0.4);
    }

    .modern-btn-outline {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 13px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        transition: all 0.3s ease;
    }

    .modern-btn-outline:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.5);
    }
    
    /* Bubbles animation */
    .bubbles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
        margin: 0;
        padding: 0;
    }

    .bubbles li {
        position: absolute;
        list-style: none;
        display: block;
        width: 20px;
        height: 20px;
        background: rgba(255, 255, 255, 0.2);
        animation: animate-bubbles 25s linear infinite;
        bottom: -150px;
        border-radius: 50%;
    }

    .bubbles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bubbles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bubbles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bubbles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
    .bubbles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bubbles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
    .bubbles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubbles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .bubbles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .bubbles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

    @keyframes animate-bubbles {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; }
        100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }
    @media (max-width: 768px) {
        .modern-title {
            font-size: 2.5rem;
        }
        .modern-subtitle {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('title', $competition->name . ' - Caturnawa UNAS FEST 2025')

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('public.home') }}">
                    <i class="bi bi-house me-1"></i>Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('public.competitions') }}">Competitions</a>
            </li>
            <li class="breadcrumb-item active">{{ $competition->name }}</li>
        </ol>
    </nav>

    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5 rounded mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-3">
                        <i class="bi bi-trophy"></i> {{ $competition->name }}
                    </h1>
                    <p class="modern-subtitle mb-4">
                        {{ $competition->description ?? 'Innovative competition that challenges participants\' creativity and abilities.' }}
                    </p>
                    
                    <!-- Status Badge -->
                    <div class="mb-4">
                        @if($competition->registration_start > now())
                            <span class="badge bg-light text-dark fs-5 px-4 py-2" style="border-radius: 50px;">
                                <i class="bi bi-clock me-2"></i>Opening Soon
                            </span>
                        @elseif($competition->registration_end < now())
                            <span class="badge bg-danger fs-5 px-4 py-2" style="border-radius: 50px;">
                                <i class="bi bi-x-circle me-2"></i>Registration Closed
                            </span>
                        @else
                            <span class="badge bg-success fs-5 px-4 py-2" style="border-radius: 50px;">
                                <i class="bi bi-check-circle me-2"></i>Registration Open
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="row justify-content-center">
                        @if($competition->registration_start <= now() && $competition->registration_end >= now())
                            <div class="col-md-4 col-lg-3 mb-3">
                                <a href="{{ route('login') }}" class="btn modern-btn btn-auto w-100">
                                    <i class="bi bi-person-plus me-2"></i>Register Now
                                </a>
                            </div>
                        @endif
                        <div class="col-md-4 col-lg-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn modern-btn-outline btn-auto w-100">
                                <i class="bi bi-arrow-left me-2"></i>Back to Competition
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
                        <h3 class="card-title mb-0">Competition Image</h3>
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
            <h2 class="text-center mb-4 fw-bold" style="color:#667eea;" data-aos="fade-up">
                <i class="bi bi-info-circle"></i> 
                Competition Information
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Competition Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3" data-aos="flip-left" data-aos-delay="300">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-event text-primary mb-2" style="font-size: 2rem;"></i>
                                    <h5 class="text-primary">Registration Period</h5>
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
                                    <h5 class="text-success">Maximum Team</h5>
                                    <p class="mb-0">{{ $competition->max_team_members ?? 'Maximum 3' }} people</p>
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
                <h2 class="text-center mb-4 fw-bold " style="color:#667eea;">
                    <i class="bi bi-calendar-week"></i> 
                    Competition Schedule
                </h2>
            </div>
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="card-title mb-0">Competition Timeline</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($competition->competition_start)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-success">
                                        <div class="card-body text-center">
                                            <i class="bi bi-play-circle text-success mb-2" style="font-size: 2rem;"></i>
                                            <h5 class="text-success">Competition Start</h5>
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
                                            <h5 class="text-danger">Competition End</h5>
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
                            <h3 class="mb-0 fw-bold" style="color: #667eea;">Terms & Conditions</h3>
                            <small class="text-muted d-block mt-50">Please read carefully before registering</small>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="terms-content">
                            @php
                                $rules = is_array($competition->rules) ? $competition->rules : json_decode($competition->rules, true);
                            @endphp
                            @if(is_array($rules) && !empty($rules))
                                <div class="rules-list">
                                    @foreach($rules as $index => $rule)
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
                                    <strong>Important:</strong> By registering, you agree to all the terms and conditions that have been set.
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
                    Competition Description
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
            <h2 class="text-center mb-4 fw-bold" style="color:#667eea;" data-aos="fade-up">
                <i class="bi bi-rocket"></i> 
                Ready to Join?
            </h2>
        </div>
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="card-title mb-0">Join {{ $competition->name }}</h3>
                    <p class="mb-0">Don't miss this golden opportunity to showcase your best abilities!</p>
                </div>
                <div class="card-body text-center">
                    @if($competition->registration_start <= now() && $competition->registration_end >= now())
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Registration Open!</strong> Register immediately before the quota is full.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-person-plus me-2"></i>Register Now
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.contact') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="bi bi-question-circle me-2"></i>Have Questions?
                                </a>
                            </div>
                        </div>
                    @elseif($competition->registration_start > now())
                        <div class="alert alert-warning">
                            <i class="bi bi-clock me-2"></i>
                            <strong>Registration Not Open Yet!</strong> Wait until {{ \Carbon\Carbon::parse($competition->registration_start)->format('d M Y') }}.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-arrow-left me-2"></i>View Other Competitions
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-x-circle me-2"></i>
                            <strong>Registration Closed!</strong> Don't miss the next competition.
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-arrow-left me-2"></i>View Other Competitions
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
