<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .form-control, .form-select {
            border: 2px solid #f1f3f4;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-outline-primary {
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #f1f3f4;
            border-right: none;
        }
        .form-control.with-icon {
            border-left: none;
        }
        .password-strength {
            margin-top: 5px;
        }
        .strength-meter {
            height: 5px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card register-card">
                    <div class="register-header">
                        <h2 class="fw-bold mb-2">
                            <i class="fas fa-user-plus me-2"></i>Join UNAS Fest 2025
                        </h2>
                        <p class="mb-0 opacity-75">Create your account and start competing</p>
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

                        <form action="{{ route('register') }}" method="POST" id="registerForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control with-icon @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Enter your full name"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
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
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone text-muted"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control with-icon @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="Enter your phone number">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="institution" class="form-label fw-semibold">Institution/University</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-university text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control with-icon @error('institution') is-invalid @enderror" 
                                               id="institution" 
                                               name="institution" 
                                               value="{{ old('institution') }}" 
                                               placeholder="Enter your institution">
                                    </div>
                                    @error('institution')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="student_id" class="form-label fw-semibold">Student ID</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-id-card text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control with-icon @error('student_id') is-invalid @enderror" 
                                           id="student_id" 
                                           name="student_id" 
                                           value="{{ old('student_id') }}" 
                                           placeholder="Enter your student ID">
                                </div>
                                @error('student_id')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control with-icon @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Enter password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength">
                                        <div class="strength-meter bg-light" id="strengthMeter"></div>
                                        <small class="text-muted" id="strengthText">Password must be at least 8 characters</small>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control with-icon" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Confirm password"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted" id="confirmText">Passwords must match</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms') is-invalid @enderror" 
                                           type="checkbox" 
                                           id="terms" 
                                           name="terms" 
                                           required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a> 
                                        and <a href="#" class="text-decoration-none">Privacy Policy</a>
                                    </label>
                                    @error('terms')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-3">Already have an account?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-2">Or explore as guest</p>
                            <a href="{{ route('public.competitions') }}" class="btn btn-link text-decoration-none">
                                <i class="fas fa-eye me-2"></i>Browse Competitions
                            </a>
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

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
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

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let messages = [];
            
            if (password.length >= 8) strength += 1;
            else messages.push('at least 8 characters');
            
            if (/[a-z]/.test(password)) strength += 1;
            else messages.push('lowercase letter');
            
            if (/[A-Z]/.test(password)) strength += 1;
            else messages.push('uppercase letter');
            
            if (/[0-9]/.test(password)) strength += 1;
            else messages.push('number');
            
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            else messages.push('special character');
            
            const colors = ['bg-danger', 'bg-warning', 'bg-info', 'bg-primary', 'bg-success'];
            const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
            
            strengthMeter.className = 'strength-meter ' + (colors[strength - 1] || 'bg-light');
            strengthMeter.style.width = (strength * 20) + '%';
            
            if (strength < 3) {
                strengthText.textContent = `Password needs: ${messages.join(', ')}`;
                strengthText.className = 'text-danger';
            } else {
                strengthText.textContent = labels[strength - 1] + ' password';
                strengthText.className = strength >= 4 ? 'text-success' : 'text-warning';
            }
        });

        // Password confirmation checker
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const confirmText = document.getElementById('confirmText');
            
            if (confirm === '') {
                confirmText.textContent = 'Passwords must match';
                confirmText.className = 'text-muted';
            } else if (password === confirm) {
                confirmText.textContent = 'Passwords match';
                confirmText.className = 'text-success';
            } else {
                confirmText.textContent = 'Passwords do not match';
                confirmText.className = 'text-danger';
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return false;
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
