<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bio-green: #4CAF50;
            --bio-light: #81C784;
            --nature-light: #A5D6A7;
            --health-blue: #2196F3;
            --health-light: #64B5F6;
            --tech-purple: #9C27B0;
            --tech-light: #BA68C8;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--bio-green) 0%, var(--health-blue) 50%, var(--tech-purple) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23ffffff" opacity="0.1"/><circle cx="80" cy="30" r="1.5" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="70" r="2.5" fill="%23ffffff" opacity="0.1"/></svg>') repeat;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating-icon {
            position: absolute;
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }

        .competition-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .competition-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .bio-theme {
            border-top: 4px solid var(--bio-green);
        }

        .health-theme {
            border-top: 4px solid var(--health-blue);
        }

        .tech-theme {
            border-top: 4px solid var(--tech-purple);
        }

        .stats-counter {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(135deg, var(--bio-green), var(--health-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .theme-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            overflow: hidden;
        }

        .theme-icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
        }

        .theme-icon:hover::before {
            animation: shine 0.6s ease-in-out;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }

        .price-tag {
            background: var(--bio-green);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .early-bird {
            background: #ff6b35;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.competitions') }}">
                <i class="fas fa-trophy me-2"></i>UNAS Fest 2025
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('public.competitions') }}">Competitions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section position-relative">
        <!-- Floating Icons -->
        <div class="floating-icon">
            <i class="fas fa-seedling fa-2x text-white opacity-50"></i>
        </div>
        <div class="floating-icon">
            <i class="fas fa-heartbeat fa-2x text-white opacity-50"></i>
        </div>
        <div class="floating-icon">
            <i class="fas fa-microchip fa-2x text-white opacity-50"></i>
        </div>

        <div class="container text-center position-relative">
            <h1 class="display-3 fw-bold mb-4">
                Innovating for a
                <span style="background: linear-gradient(45deg, #FFD700, #FFA500); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                    Sustainable Future
                </span>
            </h1>
            <p class="lead mb-5 fs-4">Bergabunglah dalam kompetisi yang menggabungkan keanekaragaman hayati, kesehatan, dan teknologi masa depan</p>

            <div class="row text-center mt-5">
                <div class="col-md-4 mb-4">
                    <div class="theme-icon" style="background: linear-gradient(135deg, var(--bio-green), var(--nature-light));">
                        <i class="fas fa-tree fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold">Bio Diversity</h4>
                    <p class="lead">Solusi berkelanjutan untuk pelestarian keanekaragaman hayati dan lingkungan</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="theme-icon" style="background: linear-gradient(135deg, var(--health-blue), var(--health-light));">
                        <i class="fas fa-heart-pulse fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold">Health Innovation</h4>
                    <p class="lead">Inovasi teknologi kesehatan untuk meningkatkan kualitas hidup masyarakat</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="theme-icon" style="background: linear-gradient(135deg, var(--tech-purple), var(--tech-light));">
                        <i class="fas fa-robot fa-2x text-white"></i>
                    </div>
                    <h4 class="fw-bold">Future Technology</h4>
                    <p class="lead">Teknologi masa depan dengan AI, IoT, dan blockchain untuk transformasi digital</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-counter">{{ number_format($stats['total_participants']) }}+</div>
                    <h5 class="text-muted">Peserta Terdaftar</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-counter">{{ $stats['active_competitions'] }}+</div>
                    <h5 class="text-muted">Kompetisi Aktif</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-counter">{{ $stats['total_universities'] }}+</div>
                    <h5 class="text-muted">Universitas</h5>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-counter">{{ number_format($stats['total_prizes'] / 1000000, 0) }}M+</div>
                    <h5 class="text-muted">Total Hadiah (IDR)</h5>
                </div>
            </div>
        </div>
    </section>

    <!-- Competitions Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold">Available Competitions</h2>
                    <p class="lead text-muted">Choose your competition and start your journey to excellence</p>
                </div>
            </div>

            @if($competitions->count() > 0)
                <div class="row">
                    @foreach($competitions as $index => $competition)
                        @php
                            $themes = ['bio-theme', 'health-theme', 'tech-theme'];
                            $theme = $themes[$index % 3];
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card competition-card {{ $theme }} h-100 shadow-sm">
                                <div class="position-relative">
                                    @if($competition->image)
                                        <img src="{{ asset('storage/' . $competition->image) }}" class="card-img-top" alt="{{ $competition->name }}">
                                    @else
                                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                             style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                            <i class="fas fa-trophy text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    
                                    <span class="badge category-badge 
                                        @if($competition->category === 'biodiversity') bg-success
                                        @elseif($competition->category === 'health') bg-danger  
                                        @else bg-primary @endif">
                                        {{ ucfirst($competition->category) }}
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $competition->name }}</h5>
                                    <p class="card-text text-muted">{{ \Str::limit($competition->description, 100) }}</p>
                                    
                                    <div class="mb-3">
                                        @if($competition->early_bird_deadline && now() <= $competition->early_bird_deadline)
                                            <span class="price-tag early-bird me-2">
                                                <i class="fas fa-bolt me-1"></i>
                                                Early Bird: Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}
                                            </span>
                                            <small class="text-muted text-decoration-line-through">
                                                Rp {{ number_format($competition->price, 0, ',', '.') }}
                                            </small>
                                        @else
                                            <span class="price-tag">
                                                Rp {{ number_format($competition->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="row text-center mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Registration</small><br>
                                            <small class="fw-bold">
                                                {{ $competition->registration_start->format('M d') }} - 
                                                {{ $competition->registration_end->format('M d') }}
                                            </small>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Competition</small><br>
                                            <small class="fw-bold">{{ $competition->competition_start->format('M d, Y') }}</small>
                                        </div>
                                    </div>

                                    @if($competition->max_participants)
                                        <div class="mb-3">
                                            @php
                                                $registered = $competition->registrations()->where('status', 'confirmed')->count();
                                                $percentage = ($registered / $competition->max_participants) * 100;
                                            @endphp
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-muted">Participants</small>
                                                <small class="fw-bold">{{ $registered }}/{{ $competition->max_participants }}</small>
                                            </div>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('public.competition', $competition) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-info-circle me-2"></i>View Details
                                        </a>
                                        @auth
                                            @if(auth()->user()->hasRole('Peserta'))
                                                <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn btn-primary">
                                                    <i class="fas fa-user-plus me-2"></i>Register Now
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login to Register
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $competitions->links() }}
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-calendar-times fa-5x text-muted mb-4"></i>
                        <h3 class="text-muted">No Competitions Available</h3>
                        <p class="lead text-muted">Check back later for exciting competitions!</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-trophy me-2"></i>UNAS Fest 2025</h5>
                    <p class="text-muted">Join the most prestigious academic competitions and showcase your talents to the world.</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.competitions') }}" class="text-muted text-decoration-none">Competitions</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="{{ route('login') }}" class="text-muted text-decoration-none">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Contact Info</h6>
                    <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>info@unasfest.ac.id</p>
                    <p class="text-muted mb-1"><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
                    <p class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; 2025 UNAS Fest. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-muted"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
