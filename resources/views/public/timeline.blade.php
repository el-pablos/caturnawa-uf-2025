@extends('layouts.caturnawa-2025')

@section('title', 'Timeline - Caturnawa UNAS FEST 2025')

@section('content')
<div class="container my-5">
    <!-- Hero Section -->
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="bi bi-clock-history me-3"></i>Timeline Caturnawa UNAS FEST 2025
                </h1>
                <p class="lead text-muted mb-4">
                    Ikuti setiap tahapan kompetisi dengan cermat agar tidak melewatkan kesempatan emas ini
                </p>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="timeline-container">
                @foreach($timeline as $index => $item)
                <div class="timeline-item {{ $item['status'] }}" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="timeline-marker">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-card">
                            <div class="timeline-header">
                                <span class="timeline-date">{{ $item['date'] }}</span>
                                <span class="timeline-status status-{{ $item['status'] }}">
                                    @if($item['status'] == 'active')
                                        <i class="bi bi-play-circle me-1"></i>Sedang Berlangsung
                                    @elseif($item['status'] == 'completed')
                                        <i class="bi bi-check-circle me-1"></i>Selesai
                                    @else
                                        <i class="bi bi-clock me-1"></i>Akan Datang
                                    @endif
                                </span>
                            </div>
                            <h4 class="timeline-title">{{ $item['title'] }}</h4>
                            <p class="timeline-description">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="text-center">
                <div class="bg-light p-5 rounded-4">
                    <h3 class="fw-bold text-primary mb-3">Siap Bergabung?</h3>
                    <p class="text-muted mb-4">
                        Jangan lewatkan kesempatan untuk menjadi bagian dari Caturnawa UNAS FEST 2025. 
                        Daftarkan diri Anda sekarang dan raih prestasi terbaik!
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('public.competitions') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-trophy me-2"></i>Lihat Kompetisi
                        </a>
                        <a href="{{ route('public.faq') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-question-circle me-2"></i>FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline-container {
    position: relative;
    padding: 2rem 0;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #007bff, #6f42c1);
    z-index: 1;
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
    padding-left: 80px;
}

.timeline-marker {
    position: absolute;
    left: 15px;
    top: 10px;
    width: 30px;
    height: 30px;
    background: #fff;
    border: 3px solid #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    font-size: 14px;
    color: #007bff;
    transition: all 0.3s ease;
}

.timeline-item.active .timeline-marker {
    background: #007bff;
    color: #fff;
    box-shadow: 0 0 20px rgba(0, 123, 255, 0.3);
    animation: pulse 2s infinite;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.timeline-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.timeline-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.timeline-date {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.timeline-status {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 500;
}

.status-active {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
}

.status-completed {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-upcoming {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.timeline-title {
    color: #2c3e50;
    margin-bottom: 0.75rem;
    font-size: 1.25rem;
}

.timeline-description {
    color: #6c757d;
    margin-bottom: 0;
    line-height: 1.6;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
    }
}

@media (max-width: 768px) {
    .timeline-container::before {
        left: 15px;
    }
    
    .timeline-item {
        padding-left: 50px;
    }
    
    .timeline-marker {
        left: 0;
        width: 25px;
        height: 25px;
        font-size: 12px;
    }
    
    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
});
</script>
@endpush
