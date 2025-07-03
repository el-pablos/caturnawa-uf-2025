<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - {{ config('app.name') }} Peserta</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- UNAS Theme CSS -->
    <link href="{{ asset('css/unas-theme.css') }}" rel="stylesheet">

    <!-- Peserta Specific CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --peserta-primary: #7c3aed;
            --peserta-secondary: #8b5cf6;
            --peserta-accent: #a78bfa;
            --peserta-dark: #6d28d9;
            --peserta-light: #ede9fe;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #faf5ff;
            color: #1e293b;
        }

        .peserta-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--peserta-primary);
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.15);
            display: flex;
            flex-direction: column;
        }
        
        .peserta-sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .peserta-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            padding: 12px 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .peserta-sidebar .nav-link:hover {
            color: white;
            background-color: var(--peserta-secondary);
            transform: translateX(5px);
        }

        .peserta-sidebar .nav-link.active {
            color: var(--peserta-primary);
            background-color: white;
            transform: translateX(5px);
            font-weight: 600;
        }

        .peserta-main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .peserta-main-content.expanded {
            margin-left: 0;
        }
        
        .peserta-navbar {
            background: white;
            border-bottom: 1px solid #ddd6fe;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .peserta-brand {
            font-weight: 700;
            color: var(--peserta-primary) !important;
        }

        .peserta-content {
            padding: 2rem;
            background: #faf5ff;
            min-height: calc(100vh - 80px);
        }

        .peserta-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
        }

        .peserta-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-peserta-primary {
            background: var(--peserta-primary);
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-peserta-primary:hover {
            background: var(--peserta-dark);
            transform: translateY(-1px);
            color: white;
        }
        
        @media (max-width: 768px) {
            .peserta-sidebar {
                transform: translateX(-100%);
            }
            
            .peserta-sidebar.show {
                transform: translateX(0);
            }
            
            .peserta-main-content {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Peserta Sidebar -->
    <nav class="peserta-sidebar" id="peserta-sidebar">
        <div class="position-sticky">
            <!-- Brand -->
            <div class="peserta-brand px-3 py-3 border-bottom border-light border-opacity-25">
                <i class="bi bi-person-badge me-2"></i>
                Portal Peserta
            </div>
            
            <!-- Navigation Menu -->
            <div class="nav-menu-container flex-grow-1 overflow-auto">
                <div class="nav nav-pills flex-column p-3">
                    <a class="nav-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}" href="{{ route('peserta.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>

                    <a class="nav-link {{ request()->routeIs('peserta.competitions.*') ? 'active' : '' }}" href="{{ route('peserta.competitions.index') }}">
                        <i class="bi bi-trophy me-2"></i>Kompetisi
                    </a>

                    <a class="nav-link {{ request()->routeIs('peserta.registrations.*') ? 'active' : '' }}" href="{{ route('peserta.registrations.index') }}">
                        <i class="bi bi-clipboard-check me-2"></i>Registrasi Saya
                    </a>

                    <a class="nav-link {{ request()->routeIs('peserta.submissions.*') ? 'active' : '' }}" href="{{ route('peserta.submissions.index') }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Karya Saya
                    </a>

                    <div class="nav-divider mt-4 mb-3" style="height: 1px; background: rgba(255, 255, 255, 0.1); margin: 0 16px;"></div>
                    <div class="nav-section-title" style="padding: 8px 16px 4px;">
                        <small class="text-white-50">PENGATURAN</small>
                    </div>

                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                        <i class="bi bi-person-circle me-2"></i>Profil
                    </a>
                </div>
            </div>

            <!-- User Info at Bottom -->
            <div class="nav-user-info border-top border-light border-opacity-25">
                <div class="d-flex align-items-center p-3">
                    <div class="user-avatar me-3">
                        <img src="{{ auth()->user()->avatar_url }}" width="40" height="40"
                             class="rounded-circle" alt="Avatar">
                    </div>
                    <div class="user-details flex-grow-1">
                        <div class="user-name text-white fw-semibold">{{ auth()->user()->name }}</div>
                        <div class="user-role text-white-50 small">{{ auth()->user()->getRoleNames()->first() }}</div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-white p-0" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="bi bi-person-circle me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="peserta-main-content" id="peserta-main-content">
        <!-- Top Header -->
        <header class="peserta-navbar navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <!-- Sidebar Toggle -->
                <button class="btn btn-outline-primary me-3" type="button" onclick="togglePesertaSidebar()" style="border-color: var(--peserta-primary); color: var(--peserta-primary);">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Page Title -->
                <h1 class="navbar-brand mb-0 h1 peserta-brand">@yield('page-title')</h1>

                <!-- Header Actions -->
                <div class="navbar-nav ms-auto">
                    @yield('header-actions')
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="peserta-content">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Main Content -->
            @yield('content')
        </div>
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Peserta JavaScript -->
    <script>
        // Toggle Peserta Sidebar
        function togglePesertaSidebar() {
            const sidebar = document.getElementById('peserta-sidebar');
            const mainContent = document.getElementById('peserta-main-content');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }
        
        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
        
        // CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Global success message
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }
        
        // Global error message
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        }
        
        // Global confirm dialog
        function confirmAction(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
