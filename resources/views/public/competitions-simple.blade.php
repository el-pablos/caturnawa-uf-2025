@extends('layouts.simple')

@section('title', 'Kompetisi - Caturnawa UNAS FEST 2025')

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

    .modern-container {
        position: relative;
        z-index: 1;
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
            font-size: 1.1rem;
        }
    }
    @media (min-width: 768px) {
        .border-md-start {
            border-left: 1px solid #dee2e6;
        }
    }

    /* Countdown Timer Styles */
    .countdown-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        position: relative;
        overflow: hidden;
    }

    .countdown-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="10" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="90" cy="30" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
        pointer-events: none;
    }

    .countdown-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .countdown-item {
        text-align: center;
        color: white;
        min-width: 100px;
    }

    .countdown-number {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(45deg, #fff, #f8f9fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        display: block;
        margin-bottom: 10px;
        animation: pulse 2s ease-in-out infinite alternate;
        transition: transform 0.15s ease-in-out;
    }

    .countdown-label {
        font-size: 1.1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .countdown-separator {
        font-size: 3rem;
        font-weight: 800;
        color: rgba(255,255,255,0.7);
        animation: blink 1s ease-in-out infinite;
        margin: 0 10px;
    }

    .event-info {
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .event-detail {
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
        font-weight: 500;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .event-detail i {
        font-size: 1.3rem;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        100% { transform: scale(1.05); }
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.3; }
    }

    /* Responsive Design for Countdown */
    @media (max-width: 768px) {
        .countdown-number {
            font-size: 2.5rem;
        }

        .countdown-separator {
            font-size: 2rem;
            margin: 0 5px;
        }

        .countdown-item {
            min-width: 70px;
        }

        .countdown-wrapper {
            gap: 10px;
        }

        .event-detail {
            font-size: 1rem;
            flex-direction: column;
            gap: 5px;
        }
    }

    @media (max-width: 480px) {
        .countdown-number {
            font-size: 2rem;
        }

        .countdown-label {
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .countdown-separator {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="container my-5 modern-container">
    <div class="row">
        <div class="col-12">
            <div class="modern-hero text-white p-5">
                <ul class="bubbles">
                    @for ($i = 0; $i < 10; $i++) <li></li> @endfor
                </ul>
                <div class="hero-content text-center">
                    <h1 class="modern-title mb-4">
                        COMPETITIONS<span style="background: linear-gradient(45deg, #ff6b6b, #feca57); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"> UNAS FEST 2025</span>
                    </h1>
                    <p class="modern-subtitle mb-5">Join Indonesia's largest national competition</p>
                    <hr class="my-4">
                    <p>Three competition categories: Technology, Health, and Biodiversity await!</p>
                    <a class="btn modern-btn btn-auto w-auto"
                       href="#competitions-list"
                       role="button">
                        <i class="bi bi-trophy"></i> View Competitions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Countdown Timer -->
    <div class="row mt-5 mb-5">
        <div class="col-12 mb-4">
            <div class="text-center" data-aos="fade-up">
                <h2 class="fw-bold text-primary">
                    <i class="bi bi-stopwatch me-2"></i>Countdown Caturnawa UNAS FEST 2025
                </h2>
                <p class="text-muted">Towards Grand Final & Awarding Ceremony</p>
            </div>
        </div>

        <!-- Countdown Display -->
        <div class="col-12" data-aos="fade-up" data-aos-delay="200">
            <div class="countdown-container">
                <div class="countdown-wrapper">
                    <div class="countdown-item">
                        <div class="countdown-number" id="days">0</div>
                        <div class="countdown-label">Days</div>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="hours">0</div>
                        <div class="countdown-label">Hours</div>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="minutes">0</div>
                        <div class="countdown-label">Minutes</div>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="seconds">0</div>
                        <div class="countdown-label">Seconds</div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Competitions List -->
    @if($competitions && $competitions->count() > 0)
        <div class="row mt-5" id="competitions-list">
            <div class="col-12 mb-4">
                <div class="text-center" data-aos="fade-up">
                    <h2 class="fw-bold text-primary">
                        <i class="bi bi-trophy me-2"></i>Available Competitions
                    </h2>
                    <p class="text-muted">Choose competitions that match your interests and expertise</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($competitions as $competition)
            <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 150 }}">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-trophy me-2"></i>{{ $competition->name }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="card-text mb-3 mb-md-0" style="text-align: justify;">{{ Str::limit($competition->description, 150) }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border-md-start ps-md-3">
                                    <i class="bi bi-calendar-event text-success d-block mb-2" style="font-size: 1.75rem;"></i>
                                    <small class="text-muted d-block">Registration</small>
                                    <div class="fw-bold">{{ $competition->registration_start->format('d M') }} - {{ $competition->registration_end->format('d M') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="{{ route('public.competition.detail', $competition->slug) }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            @if($competition->registration_start <= now() && $competition->registration_end >= now())
                                <a href="{{ route('register') }}" class="btn btn-success">
                                    <i class="bi bi-person-plus"></i> Register Now
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="bi bi-clock"></i>
                                    @if($competition->registration_start > now())
                                        Not Open Yet
                                    @else
                                        Registration Closed
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $competitions->links() }}
            </div>
        </div>
    @else
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow text-center">
                    <div class="card-body py-5">
                        <i class="bi bi-trophy text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No Competitions Yet</h4>
                        <p class="text-muted mb-4">Competitions will be opened soon. Keep monitoring this website for the latest information!</p>
                        <a href="{{ route('public.home') }}" class="btn modern-btn">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection



@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set target date: November 10, 2025, 00:00:00 WIB (UTC+7)
    const targetDate = new Date('2025-11-10T00:00:00+07:00').getTime();

    // Update countdown every second
    const countdownInterval = setInterval(function() {
        const now = new Date().getTime();
        const distance = targetDate - now;

        // Calculate time units
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Update display with animation
        updateCountdownDisplay('days', days);
        updateCountdownDisplay('hours', hours);
        updateCountdownDisplay('minutes', minutes);
        updateCountdownDisplay('seconds', seconds);

        // Check if countdown is finished
        if (distance < 0) {
            clearInterval(countdownInterval);
            showEventStarted();
        }
    }, 1000);

    function updateCountdownDisplay(elementId, value) {
        const element = document.getElementById(elementId);
        const formattedValue = value.toString().padStart(2, '0');

        if (element && element.textContent !== formattedValue) {
            element.style.transform = 'scale(1.1)';
            element.textContent = formattedValue;

            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 150);
        }
    }

    function showEventStarted() {
        const countdownContainer = document.querySelector('.countdown-container');
        if (countdownContainer) {
            countdownContainer.innerHTML = `
                <div class="text-center text-white">
                    <i class="bi bi-trophy-fill" style="font-size: 4rem; color: #ffd700;"></i>
                    <h2 class="mt-3 mb-2" style="color: #ffd700;">🎉 Caturnawa UNAS FEST 2025 DIMULAI! 🎉</h2>
                    <p class="mb-0" style="font-size: 1.2rem;">Selamat datang di Grand Final & Awarding Ceremony!</p>
                </div>
            `;
        }
    }

    // Add smooth transition effects
    const style = document.createElement('style');
    style.textContent = `
        .countdown-number {
            transition: transform 0.15s ease-in-out;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush
