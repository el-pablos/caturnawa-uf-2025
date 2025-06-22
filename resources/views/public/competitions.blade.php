<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Competitions - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .competition-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .competition-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }
        .price-tag {
            background: #28a745;
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
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
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
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to UNAS Fest 2025</h1>
            <p class="lead mb-4">Join the most exciting academic competitions and showcase your talents!</p>
            <div class="row text-center mt-5">
                <div class="col-md-4">
                    <i class="fas fa-leaf fa-3x mb-3"></i>
                    <h5>Bio-diversity</h5>
                    <p>Environmental and sustainability challenges</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-heartbeat fa-3x mb-3"></i>
                    <h5>Health</h5>
                    <p>Healthcare innovation and wellness</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-microchip fa-3x mb-3"></i>
                    <h5>Technology</h5>
                    <p>Digital innovation and tech solutions</p>
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
                    @foreach($competitions as $competition)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card competition-card h-100 shadow-sm">
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
