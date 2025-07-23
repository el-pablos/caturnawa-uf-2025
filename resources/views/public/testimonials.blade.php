@extends('layouts.simple')

@section('title', 'Testimonials - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="testimonial-hero text-white p-5 mb-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <div class="floating-icon mb-4">
                        <i class="bi bi-chat-heart me-3"></i>
                    </div>
                    <h1 class="modern-title mb-4">Testimoni
                    </h1>
                    <p class="modern-subtitle lead mb-4">
                        Dengarkan pengalaman dan cerita inspiratif dari para peserta UNAS FEST yang telah merasakan transformasi melalui kompetisi nasional terbesar ini
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn modern-btn btn-auto w-100">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#add-testimonial" class="btn modern-btn-outline btn-auto w-100">
                                <i class="bi bi-plus-circle me-2"></i>Beri Testimoni
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-star-fill text-warning" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">4.8/5</h3>
                    <p class="text-muted">Rating Rata-rata</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-chat-dots-fill text-primary" style="font-size: 2rem;"></i>
                    <h3 class="mt-2">{{ count($testimonials) }}+</h3>
                    <p class="text-muted">Testimoni Peserta</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    @if($testimonials && count($testimonials) > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-4">
                <i class="bi bi-chat-quote text-primary"></i> 
                Apa Kata Mereka?
            </h2>
        </div>
        @foreach($testimonials as $testimonial)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="{{ $testimonial['avatar'] }}" 
                             alt="{{ $testimonial['name'] }}" 
                             class="rounded-circle mb-3"
                             width="80" height="80"
                             style="object-fit: cover;">
                    </div>
                    
                    <div class="mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $testimonial['rating'] ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    
                    <blockquote class="mb-4">
                        <p class="text-muted fst-italic">"{{ $testimonial['comment'] }}"</p>
                    </blockquote>
                    
                    <div class="border-top pt-3">
                        <h6 class="fw-bold mb-1">{{ $testimonial['name'] }}</h6>
                        <small class="text-muted d-block">{{ $testimonial['institution'] }}</small>
                        <small class="text-primary">Kompetisi {{ $testimonial['competition'] }} {{ $testimonial['year'] }}</small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Add Testimonial Section -->
    <div class="row" id="add-testimonial">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success-modern text-white">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Bagikan Pengalaman Anda
                    </h2>
                    <p class="mb-0">Sudah pernah mengikuti UNAS Fest? Ceritakan pengalaman Anda untuk menginspirasi peserta lainnya!</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('public.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="institution" class="form-label">Institusi/Universitas</label>
                                <input type="text" class="form-control @error('institution') is-invalid @enderror" 
                                       id="institution" name="institution" value="{{ old('institution') }}" required>
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="competition" class="form-label">Kompetisi yang Diikuti</label>
                                <select class="form-select @error('competition') is-invalid @enderror" 
                                        id="competition" name="competition" required>
                                    <option value="">Pilih Kompetisi</option>
                                    <option value="Teknologi" {{ old('competition') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                    <option value="Kesehatan" {{ old('competition') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                    <option value="Biodiversitas" {{ old('competition') == 'Biodiversitas' ? 'selected' : '' }}>Biodiversitas</option>
                                </select>
                                @error('competition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="year" class="form-label">Tahun Mengikuti</label>
                                <select class="form-select @error('year') is-invalid @enderror" 
                                        id="year" name="year" required>
                                    <option value="">Pilih Tahun</option>
                                    @for($y = date('Y'); $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="rating" class="form-label">Rating Pengalaman</label>
                                <div class="rating-input d-flex justify-content-center mb-3">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                               {{ old('rating') == $i ? 'checked' : '' }} required class="d-none">
                                        <label for="star{{ $i }}" class="star-label me-1" style="font-size: 1.5rem; color: #ddd; cursor: pointer;">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="text-danger small text-center">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="comment" class="form-label">Testimoni Anda</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" 
                                          id="comment" name="comment" rows="4" 
                                          placeholder="Ceritakan pengalaman Anda mengikuti UNAS Fest..." required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label for="photo" class="form-label">Foto Profil (Opsional)</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success-modern text-white btn-lg">
                                    <i class="bi bi-send me-2"></i>Kirim Testimoni
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .testimonial-hero {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .bubbles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
        border-radius: 1.5rem;
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

    .card.shadow .card-header.bg-success-modern {
        background: linear-gradient(135deg, #4ade80, #16a34a);
        border-bottom: none;
    }

    .btn-success-modern {
        background: linear-gradient(135deg, #4ade80, #16a34a);
        border: none;
        transition: all 0.3s ease;
        padding: 12px 30px;
        font-size: 1.1rem;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
    }
    .btn-success-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(22, 163, 74, 0.4);
    }

    .card.h-100 {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        border-radius: 1rem;
    }
    .card.h-100:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-color: transparent;
    }

    .card.text-center {
        border-radius: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .card.text-center:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }

    .star-label:hover,
    .star-label:hover ~ .star-label,
    .rating-input input[type="radio"]:checked + .star-label,
    .rating-input input[type="radio"]:checked + .star-label ~ .star-label {
        color: #ffc107 !important;
    }

    .floating-icon {
        font-size: 4rem;
        animation: floatTrophy 3s ease-in-out infinite;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
    }
    
    @keyframes floatTrophy {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    .modern-title {
        font-size: 3rem;
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
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255,255,255,0.2);
        color: white;
    }


</style>
@endpush
