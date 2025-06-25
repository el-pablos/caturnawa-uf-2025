@extends('layouts.public')

@php
    $seoPage = 'testimonials';
@endphp

@section('content')
<!-- Hero Section -->
<section class="testimonials-hero">
    <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="floating-quotes">
            <div class="quote quote-1">"</div>
            <div class="quote quote-2">"</div>
            <div class="quote quote-3">"</div>
        </div>
    </div>

    <div class="container position-relative">
        <div class="row align-items-center justify-content-center text-center py-5">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="hero-badge">
                    <i class="bi bi-chat-quote me-2"></i>
                    Testimoni Peserta
                </div>

                <h1 class="hero-title">
                    Cerita
                    <span class="text-gradient">Sukses</span> Mereka
                </h1>

                <p class="hero-subtitle">
                    Dengarkan pengalaman dan cerita inspiratif dari para peserta UNAS Fest yang telah merasakan transformasi melalui kompetisi nasional terbesar ini
                </p>

                <div class="testimonial-stats" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <span class="stat-number">4.9</span>
                        <div class="stat-stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span class="stat-label">Rating Rata-rata</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Testimoni Positif</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-number">95%</span>
                        <span class="stat-label">Kepuasan Peserta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="section-title font-poppins">Apa Kata Mereka?</h2>
            <p class="section-subtitle">
                Testimoni nyata dari peserta yang telah merasakan pengalaman luar biasa di UNAS Fest
            </p>
        </div>
        
        <div class="row g-4">
            @foreach($testimonials as $index => $testimonial)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="card h-100 border-0 shadow-lg">
                    <div class="card-body p-4 text-center">
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
    </div>
</section>

<!-- Add Testimonial Section -->
<section class="section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="section-title font-poppins">Bagikan Pengalaman Anda</h2>
                    <p class="section-subtitle">
                        Sudah pernah mengikuti UNAS Fest? Ceritakan pengalaman Anda untuk menginspirasi peserta lainnya!
                    </p>
                </div>
                
                <div class="card border-0 shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-5">
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
                                    <div class="rating-input">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                                   {{ old('rating') == $i ? 'checked' : '' }} required>
                                            <label for="star{{ $i }}" class="star-label">
                                                <i class="bi bi-star-fill"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <div class="text-danger small">{{ $message }}</div>
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
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
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
</section>

<!-- Statistics Section -->
<section class="section">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="h-100">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-chat-quote fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-primary mb-2">{{ count($testimonials) }}+</h4>
                    <p class="text-muted mb-0">Testimoni Positif</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="h-100">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-star-fill fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-warning mb-2">4.9/5</h4>
                    <p class="text-muted mb-0">Rating Rata-rata</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="h-100">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-2">95%</h4>
                    <p class="text-muted mb-0">Kepuasan Peserta</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="h-100">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-award fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-info mb-2">100%</h4>
                    <p class="text-muted mb-0">Rekomendasi</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .rating-input {
        display: flex;
        gap: 5px;
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    .rating-input input[type="radio"] {
        display: none;
    }
    
    .star-label {
        font-size: 1.5rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .rating-input input[type="radio"]:checked ~ .star-label,
    .rating-input input[type="radio"]:checked + .star-label,
    .star-label:hover {
        color: #ffc107;
    }
    
    .rating-input input[type="radio"]:checked + .star-label ~ .star-label {
        color: #ddd;
    }
    
    /* Fix for proper star rating */
    .rating-input {
        flex-direction: row-reverse;
        justify-content: center;
    }
    
    .star-label:hover,
    .star-label:hover ~ .star-label,
    .rating-input input[type="radio"]:checked + .star-label,
    .rating-input input[type="radio"]:checked + .star-label ~ .star-label {
        color: #ffc107;
    }
</style>
@endpush
