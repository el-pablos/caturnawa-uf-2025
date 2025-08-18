<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - Caturnawa UNAS FEST 2025</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"></noscript>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, var(--accent-color) 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 1000"><defs><radialGradient id="a" cx=".66" cy=".15" r="1.5"><stop offset="0" stop-color="%23ffffff" stop-opacity=".1"/><stop offset="1" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><rect width="100" height="1000" fill="url(%23a)"/></svg>');
            opacity: 0.1;
            z-index: 0;
        }

        .register-container {
            position: relative;
            z-index: 1;
        }
        
        .register-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            position: relative;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }
        
        .register-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: rotate(45deg);
        }

        .register-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .register-header h2 {
            position: relative;
            z-index: 2;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .register-header p {
            position: relative;
            z-index: 2;
            opacity: 0.9;
            font-weight: 400;
        }

        .register-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 16px;
            background: #ffffff;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.1);
            background: #ffffff;
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            padding: 14px 24px;
            font-weight: 600;
            color: var(--primary-color);
            background: transparent;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }
        
        .input-group-text {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .form-control.with-icon, .form-select.with-icon {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background: #eff6ff;
            color: var(--primary-color);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .password-strength {
            margin-top: 8px;
        }
        
        .strength-meter {
            height: 6px;
            border-radius: 3px;
            transition: all 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .alert {
            border: none;
            border-radius: 12px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }

        .text-decoration-none:hover {
            color: var(--primary-color) !important;
            text-decoration: underline !important;
        }

        .btn-link {
            color: var(--primary-color);
            font-weight: 500;
        }

        .btn-link:hover {
            color: var(--secondary-color);
            text-decoration: none;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Animation for card entrance */
        .register-card {
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(60px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Spinner animation */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .register-header {
                padding: 2rem 1.5rem;
            }
            
            .register-icon {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container register-container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card register-card">
                    <div class="register-header">
                        <div class="register-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h2 class="fw-bold mb-2">
                            Bergabung UNAS FEST 2025
                        </h2>
                        <p class="mb-0">Buat akun dan mulai berkompetisi sekarang</p>
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
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-person text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control with-icon @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Masukkan nama lengkap"
                                               required>
                                    </div>
                                    @error('name')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control with-icon @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="Masukkan alamat email"
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-telephone text-muted"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control with-icon @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="Masukkan nomor telepon">
                                    </div>
                                    @error('phone')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="institution" class="form-label fw-semibold">Asal Instansi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-building text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control with-icon @error('institution') is-invalid @enderror"
                                               id="institution"
                                               name="institution"
                                               value="{{ old('institution') }}"
                                               placeholder="Nama universitas/sekolah"
                                               required>
                                    </div>
                                    @error('institution')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="participant_status" class="form-label fw-semibold">Status Peserta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-mortarboard text-muted"></i>
                                        </span>
                                        <select class="form-select with-icon @error('participant_status') is-invalid @enderror"
                                                id="participant_status"
                                                name="participant_status"
                                                required>
                                            <option value="">Pilih Status Peserta</option>
                                            <option value="Mahasiswa Unas" {{ old('participant_status') === 'Mahasiswa Unas' ? 'selected' : '' }}>Mahasiswa UNAS</option>
                                            <option value="Mahasiswa Eksternal" {{ old('participant_status') === 'Mahasiswa Eksternal' ? 'selected' : '' }}>Mahasiswa Eksternal</option>
                                            <option value="Siswa SMA/SMK" {{ old('participant_status') === 'Siswa SMA/SMK' ? 'selected' : '' }}>Siswa SMA/SMK</option>
                                        </select>
                                    </div>
                                    @error('participant_status')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label fw-semibold">NIM/ID Mahasiswa <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-credit-card text-muted"></i>
                                        </span>
                                        <input type="text"
                                               class="form-control with-icon @error('student_id') is-invalid @enderror"
                                               id="student_id"
                                               name="student_id"
                                               value="{{ old('student_id') }}"
                                               placeholder="Masukkan NIM/ID mahasiswa"
                                               required>
                                    </div>
                                    @error('student_id')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">Kata Sandi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control with-icon @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Masukkan kata sandi"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength">
                                        <div class="strength-meter bg-light" id="strengthMeter"></div>
                                        <small class="text-muted" id="strengthText">Kata sandi minimal 8 karakter</small>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control with-icon" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               placeholder="Konfirmasi kata sandi"
                                               required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted" id="confirmText">Kata sandi harus sama</small>
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
                                        Saya setuju dengan <a href="#" class="text-decoration-none">Syarat dan Ketentuan</a> 
                                        serta <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                                    </label>
                                    @error('terms')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-person-plus me-2"></i>Buat Akun
                                </button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="text-muted mb-3">Sudah punya akun?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-2">Atau jelajahi sebagai tamu</p>
                            <a href="{{ route('public.competitions') }}" class="btn btn-link text-decoration-none">
                                <i class="bi bi-search me-2"></i>Lihat Kompetisi
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
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
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
            else messages.push('minimal 8 karakter');
            
            if (/[a-z]/.test(password)) strength += 1;
            else messages.push('huruf kecil');
            
            if (/[A-Z]/.test(password)) strength += 1;
            else messages.push('huruf besar');
            
            if (/[0-9]/.test(password)) strength += 1;
            else messages.push('angka');
            
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            else messages.push('karakter khusus');
            
            const colors = ['bg-danger', 'bg-warning', 'bg-info', 'bg-primary', 'bg-success'];
            const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Baik', 'Kuat'];
            
            strengthMeter.className = 'strength-meter ' + (colors[strength - 1] || 'bg-light');
            strengthMeter.style.width = (strength * 20) + '%';
            
            if (strength < 3) {
                strengthText.textContent = `Kata sandi butuh: ${messages.join(', ')}`;
                strengthText.className = 'text-danger';
            } else {
                strengthText.textContent = 'Kata sandi ' + labels[strength - 1].toLowerCase();
                strengthText.className = strength >= 4 ? 'text-success' : 'text-warning';
            }
        });

        // Password confirmation checker
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            const confirmText = document.getElementById('confirmText');
            
            if (confirm === '') {
                confirmText.textContent = 'Kata sandi harus sama';
                confirmText.className = 'text-muted';
            } else if (password === confirm) {
                confirmText.textContent = 'Kata sandi cocok';
                confirmText.className = 'text-success';
            } else {
                confirmText.textContent = 'Kata sandi tidak cocok';
                confirmText.className = 'text-danger';
            }
        });

        // Debug CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const csrfInput = document.querySelector('input[name="_token"]');

        console.log('Register CSRF Meta Token:', csrfToken ? csrfToken.getAttribute('content') : 'Not found');
        console.log('Register CSRF Input Token:', csrfInput ? csrfInput.value : 'Not found');

        // Ensure form has CSRF token
        const form = document.querySelector('form[action*="register"]');
        if (form && !form.querySelector('input[name="_token"]')) {
            console.error('Register form missing CSRF token input!');
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken ? csrfToken.getAttribute('content') : '';
            form.appendChild(tokenInput);
            console.log('CSRF token input added to register form');
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Kata sandi tidak cocok');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Kata sandi minimal 8 karakter');
                return false;
            }
        });

        // Auto-dismiss alerts after 8 seconds (extended timing for better UX)
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 8000);
    </script>
</body>
</html>
