<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #87CEEB 0%, #98FB98 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #00BCD4 0%, #4FC3F7 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .form-control {
            border: 2px solid #f1f3f4;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #00BCD4;
            box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #00BCD4 0%, #4FC3F7 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0097A7 0%, #29B6F6 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 188, 212, 0.3);
        }
        .btn-outline-primary {
            border: 2px solid #00BCD4;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            color: #00BCD4;
        }
        .btn-outline-primary:hover {
            background: #00BCD4;
            border-color: #00BCD4;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #f1f3f4;
            border-right: none;
        }
        .form-control.with-icon {
            border-left: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card login-card">
                    <div class="login-header">
                        <h2 class="fw-bold mb-2">
                            <i class="fas fa-trophy me-2"></i>Welcome Back
                        </h2>
                        <p class="mb-0 opacity-75">Sign in to your UNAS Fest account</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Alerts -->
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control with-icon @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required>
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control with-icon @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-3">Don't have an account?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Create New Account
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-2">Or explore as guest</p>
                            <a href="{{ route('public.competitions') }}" class="btn btn-link text-decoration-none">
                                <i class="fas fa-eye me-2"></i>Browse Competitions
                            </a>
                        </div>

                        <!-- Demo Accounts -->
                        <div class="mt-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2"><i class="fas fa-info-circle me-2 text-info"></i>Demo Accounts</h6>
                                    <small class="text-muted">
                                        <strong>Admin:</strong> admin@unasfest.ac.id / admin123<br>
                                        <strong>Juri:</strong> juri1@unasfest.ac.id / juri123<br>
                                        <strong>Peserta:</strong> peserta@unasfest.ac.id / peserta123
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
