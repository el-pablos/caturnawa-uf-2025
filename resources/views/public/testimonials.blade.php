@extends('layouts.simple')

@section('title', 'Testimonials - UNAS Fest 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-chat-heart me-3"></i>Testimoni
                    </h1>
                    <p class="lead mb-4">
                        Dengarkan pengalaman dan cerita inspiratif dari para peserta UNAS Fest yang telah merasakan transformasi melalui kompetisi nasional terbesar ini
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('public.competitions') }}" class="btn btn-warning btn-lg w-100">
                                <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#add-testimonial" class="btn btn-light btn-lg w-100">
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
                <div class="card-header bg-success text-white">
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
                                <button type="submit" class="btn btn-success btn-lg px-5">
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
    .star-label:hover,
    .star-label:hover ~ .star-label,
    .rating-input input[type="radio"]:checked + .star-label,
    .rating-input input[type="radio"]:checked + .star-label ~ .star-label {
        color: #ffc107 !important;
    }
</style>
@endpush