<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $competition->name }} - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        .competition-image {
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
        }
        .info-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .price-display {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }
        .early-bird-price {
            background: linear-gradient(135deg, #ff6b35, #f7931e);
        }
        .timeline-item {
            border-left: 3px solid #667eea;
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .badge-category {
            font-size: 0.9rem;
            padding: 8px 15px;
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
                        <a class="nav-link" href="{{ route('public.competitions') }}">Competitions</a>
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
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb text-white-50">
                            <li class="breadcrumb-item"><a href="{{ route('public.competitions') }}" class="text-white-50">Competitions</a></li>
                            <li class="breadcrumb-item active text-white">{{ $competition->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 fw-bold mb-3">{{ $competition->name }}</h1>
                    <p class="lead mb-4">{{ $competition->description }}</p>
                    <span class="badge badge-category 
                        @if($competition->category === 'biodiversity') bg-success
                        @elseif($competition->category === 'health') bg-danger  
                        @else bg-primary @endif">
                        <i class="fas fa-tag me-2"></i>{{ ucfirst($competition->category) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Competition Details -->
                <div class="col-lg-8">
                    <!-- Competition Image -->
                    @if($competition->image)
                        <img src="{{ asset('storage/' . $competition->image) }}" class="img-fluid competition-image w-100 mb-4" alt="{{ $competition->name }}">
                    @else
                        <div class="competition-image w-100 mb-4 bg-gradient d-flex align-items-center justify-content-center" 
                             style="background: linear-gradient(45deg, #667eea, #764ba2);">
                            <i class="fas fa-trophy text-white" style="font-size: 5rem;"></i>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="card info-card mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>About This Competition</h4>
                            <p class="card-text">{{ $competition->description }}</p>
                            
                            @if($competition->theme)
                                <h6 class="mt-4"><i class="fas fa-lightbulb me-2 text-warning"></i>Theme</h6>
                                <p class="text-muted">{{ $competition->theme }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($competition->requirements)
                        <div class="card info-card mb-4">
                            <div class="card-body">
                                <h4 class="card-title"><i class="fas fa-clipboard-list me-2 text-primary"></i>Requirements</h4>
                                @if(is_array($competition->requirements))
                                    <ul class="list-unstyled">
                                        @foreach($competition->requirements as $requirement)
                                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ $requirement }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>{{ $competition->requirements }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Rules -->
                    @if($competition->rules)
                        <div class="card info-card mb-4">
                            <div class="card-body">
                                <h4 class="card-title"><i class="fas fa-gavel me-2 text-primary"></i>Rules & Regulations</h4>
                                @if(is_array($competition->rules))
                                    <ol>
                                        @foreach($competition->rules as $rule)
                                            <li class="mb-2">{{ $rule }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p>{{ $competition->rules }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Prizes -->
                    @if($competition->prizes)
                        <div class="card info-card mb-4">
                            <div class="card-body">
                                <h4 class="card-title"><i class="fas fa-award me-2 text-warning"></i>Prizes</h4>
                                @if(is_array($competition->prizes))
                                    <div class="row">
                                        @foreach($competition->prizes as $position => $prize)
                                            <div class="col-md-4 mb-3">
                                                <div class="text-center p-3 border rounded">
                                                    <i class="fas fa-medal fa-2x mb-2 
                                                        @if($loop->first) text-warning
                                                        @elseif($loop->iteration == 2) text-secondary
                                                        @else text-dark @endif"></i>
                                                    <h6>{{ is_numeric($position) ? 'Place ' . ($position + 1) : $position }}</h6>
                                                    <p class="mb-0">{{ $prize }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>{{ $competition->prizes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Registration Info -->
                    <div class="card info-card mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-center">Registration Information</h5>
                            
                            <!-- Price Display -->
                            <div class="mb-4">
                                @if($competition->early_bird_deadline && now() <= $competition->early_bird_deadline)
                                    <div class="price-display early-bird-price mb-3">
                                        <h6 class="mb-1"><i class="fas fa-bolt me-2"></i>Early Bird Special!</h6>
                                        <h3 class="mb-1">Rp {{ number_format($competition->early_bird_price, 0, ',', '.') }}</h3>
                                        <small class="text-decoration-line-through opacity-75">
                                            Rp {{ number_format($competition->price, 0, ',', '.') }}
                                        </small>
                                        <div class="mt-2">
                                            <small>Valid until {{ $competition->early_bird_deadline->format('M d, Y') }}</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="price-display">
                                        <h6 class="mb-1">Registration Fee</h6>
                                        <h3 class="mb-0">Rp {{ number_format($competition->price, 0, ',', '.') }}</h3>
                                    </div>
                                @endif
                            </div>

                            <!-- Timeline -->
                            <div class="timeline">
                                <div class="timeline-item">
                                    <h6 class="mb-1"><i class="fas fa-calendar-plus me-2"></i>Registration Opens</h6>
                                    <p class="text-muted mb-0">{{ $competition->registration_start->format('M d, Y - H:i') }}</p>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1"><i class="fas fa-calendar-times me-2"></i>Registration Closes</h6>
                                    <p class="text-muted mb-0">{{ $competition->registration_end->format('M d, Y - H:i') }}</p>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1"><i class="fas fa-play-circle me-2"></i>Competition Starts</h6>
                                    <p class="text-muted mb-0">{{ $competition->competition_start->format('M d, Y - H:i') }}</p>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1"><i class="fas fa-flag-checkered me-2"></i>Competition Ends</h6>
                                    <p class="text-muted mb-0">{{ $competition->competition_end->format('M d, Y - H:i') }}</p>
                                </div>
                            </div>

                            <!-- Participants Info -->
                            @if($competition->max_participants)
                                <div class="mb-4">
                                    @php
                                        $registered = $registrationsCount ?? 0;
                                        $percentage = $competition->max_participants > 0 ? ($registered / $competition->max_participants) * 100 : 0;
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Participants</h6>
                                        <span class="badge bg-info">{{ $registered }}/{{ $competition->max_participants }}</span>
                                    </div>
                                    <div class="progress mb-2" style="height: 10px;">
                                        <div class="progress-bar bg-info" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    @if($percentage >= 80)
                                        <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Almost full!</small>
                                    @endif
                                </div>
                            @endif

                            <!-- Team Information -->
                            @if($competition->is_team_competition)
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-users me-2"></i>Team Competition</h6>
                                    @if($competition->min_team_members && $competition->max_team_members)
                                        <p class="mb-0">Team size: {{ $competition->min_team_members }}-{{ $competition->max_team_members }} members</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Registration Button -->
                            <div class="d-grid gap-2">
                                @auth
                                    @if(auth()->user()->hasRole('Peserta'))
                                        @if(now() >= $competition->registration_start && now() <= $competition->registration_end)
                                            <a href="{{ route('peserta.competitions.show', $competition) }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>Register Now
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-lg" disabled>
                                                <i class="fas fa-calendar-times me-2"></i>Registration Closed
                                            </button>
                                        @endif
                                    @else
                                        <div class="alert alert-warning text-center">
                                            <small>Only participants can register for competitions</small>
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login to Register
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    @if($competition->contact_person)
                        <div class="card info-card">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-address-book me-2"></i>Contact Person</h6>
                                <p class="card-text">{{ $competition->contact_person }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-trophy me-2"></i>UNAS Fest 2025</h5>
                    <p class="text-muted">Join the most prestigious academic competitions.</p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('public.competitions') }}" class="text-muted text-decoration-none">Competitions</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6>Contact Info</h6>
                    <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i>info@unasfest.ac.id</p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i>+62 21 1234 5678</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
