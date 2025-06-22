@extends('layouts.app')

@section('title', 'About UNAS Fest 2025')

@section('content')
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">About UNAS Fest 2025</h1>
                <p class="lead">Discover the future of academic excellence through innovation, competition, and collaboration.</p>
            </div>
            <div class="col-lg-4 text-center">
                <i class="fas fa-graduation-cap" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Mission & Vision -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                        <h3 class="fw-bold">Our Mission</h3>
                    </div>
                    <p class="lead text-muted">To provide a platform for students to showcase their talents, foster innovation, and build connections that will shape the future of academic excellence.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-eye fa-3x text-success mb-3"></i>
                        <h3 class="fw-bold">Our Vision</h3>
                    </div>
                    <p class="lead text-muted">To become the premier academic competition platform that nurtures tomorrow's leaders in biodiversity, health, and technology sectors.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Competition Categories -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="display-5 fw-bold">Competition Categories</h2>
            <p class="lead text-muted">Three exciting categories to challenge your expertise</p>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-leaf fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold text-success">Bio-diversity</h4>
                    <p class="text-muted">Environmental sustainability, conservation, and ecological innovation challenges that address our planet's most pressing environmental issues.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-heartbeat fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold text-danger">Health</h4>
                    <p class="text-muted">Healthcare innovation, medical research, and wellness solutions that aim to improve quality of life and advance medical science.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card text-center border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-microchip fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold text-primary">Technology</h4>
                    <p class="text-muted">Digital innovation, software development, and technological solutions that drive progress in the digital age.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Participate -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="display-5 fw-bold">Why Participate?</h2>
            <p class="lead text-muted">Join thousands of students in this exciting journey</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-trophy fa-lg text-warning"></i>
                </div>
                <h5 class="fw-bold">Win Amazing Prizes</h5>
                <p class="text-muted">Substantial cash prizes and certificates for winners</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users fa-lg text-info"></i>
                </div>
                <h5 class="fw-bold">Network & Collaborate</h5>
                <p class="text-muted">Connect with like-minded students and professionals</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-brain fa-lg text-purple"></i>
                </div>
                <h5 class="fw-bold">Enhance Skills</h5>
                <p class="text-muted">Develop critical thinking and problem-solving abilities</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-certificate fa-lg text-success"></i>
                </div>
                <h5 class="fw-bold">Gain Recognition</h5>
                <p class="text-muted">Boost your academic and professional portfolio</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-lg-8 mx-auto text-center">
            <div class="bg-gradient p-5 rounded-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h3 class="fw-bold mb-4">Ready to Join the Competition?</h3>
                <p class="lead mb-4">Don't miss this opportunity to showcase your talents and compete with the best minds from across the nation.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('public.competitions') }}" class="btn btn-light btn-lg me-md-2">
                        <i class="fas fa-trophy me-2"></i>View Competitions
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
