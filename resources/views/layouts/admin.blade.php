<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - {{ config('app.name') }} Admin</title>
    
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

    <!-- Admin Specific CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --admin-primary: #2563eb;
            --admin-secondary: #3b82f6;
            --admin-accent: #60a5fa;
            --admin-dark: #1e40af;
            --admin-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1e293b;
        }

        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .admin-sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .admin-sidebar .nav-link {
            color: #6b7280;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            padding: 12px 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .nav-link:hover {
            color: var(--admin-primary);
            background-color: #f3f4f6;
            transform: translateX(5px);
        }

        .admin-sidebar .nav-link.active {
            color: white;
            background-color: var(--admin-primary);
            transform: translateX(5px);
            font-weight: 600;
        }

        .admin-main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        .admin-main-content.expanded {
            margin-left: 0;
        }
        
        .admin-navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .admin-brand {
            font-weight: 700;
            color: var(--admin-primary) !important;
        }

        .admin-content {
            padding: 2rem;
            background: #ffffff;
            min-height: calc(100vh - 80px);
        }

        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
        }

        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-admin-primary {
            background: var(--admin-primary);
            border: none;
            color: white;
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-admin-primary:hover {
            background: var(--admin-dark);
            transform: translateY(-1px);
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                width: 100%;
                max-width: 300px;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main-content {
                margin-left: 0;
            }

            .admin-navbar .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .admin-brand {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --sidebar-width: 100%;
            }

            .admin-sidebar {
                width: 100%;
            }

            .admin-brand {
                font-size: 0.9rem;
            }

            .admin-content {
                padding: 1rem;
            }
        }

        /* Scrollbar styling for sidebar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Navigation menu container */
        .nav-menu-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 1rem;
        }

        /* Responsive navigation adjustments */
        .nav-menu-container .nav {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .admin-sidebar .nav-link {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 10px 12px;
            margin: 2px 0;
        }

        /* Additional responsive utilities */
        .min-width-0 {
            min-width: 0;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Mobile navbar improvements */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .btn-outline-primary {
                padding: 0.375rem 0.75rem;
            }
        }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Improved scrolling for small screens */
        @media (max-height: 600px) {
            .nav-menu-container {
                max-height: calc(100vh - 200px);
            }

            .nav-user-info {
                position: sticky;
                bottom: 0;
                background: #ffffff;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Admin Sidebar -->
    <nav class="admin-sidebar" id="admin-sidebar">
        <!-- Brand -->
        <div class="admin-brand px-3 py-3 border-bottom flex-shrink-0" style="color: var(--admin-primary); font-weight: 700;">
            <i class="bi bi-shield-check me-2"></i>
            <span class="brand-text">Admin Panel</span>
        </div>

        <!-- Navigation Menu -->
        <div class="nav-menu-container">
            <div class="nav nav-pills flex-column p-3">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>

                    @can('users.view')
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i>Kelola Pengguna
                    </a>
                    @endcan

                    @can('roles.view')
                    <a class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                        <i class="bi bi-person-vcard me-2"></i>Kelola Role
                    </a>
                    @endcan

                    <a class="nav-link {{ request()->routeIs('admin.competitions.*') ? 'active' : '' }}" href="{{ route('admin.competitions.index') }}">
                        <i class="bi bi-trophy me-2"></i>Kelola Kompetisi
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">
                        <i class="bi bi-person-check me-2"></i>Registrasi
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}" href="{{ route('admin.submissions.index') }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Karya Peserta
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                        <i class="bi bi-credit-card me-2"></i>Pembayaran
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-graph-up me-2"></i>Laporan
                    </a>

                    <a class="nav-link {{ request()->routeIs('admin.qr-scanner.*') ? 'active' : '' }}" href="{{ route('admin.qr-scanner.index') }}">
                        <i class="bi bi-qr-code-scan me-2"></i>QR Scanner
                    </a>

                    <div class="nav-divider mt-4 mb-3" style="height: 1px; background: #e5e7eb; margin: 0 16px;"></div>
                    <div class="nav-section-title" style="padding: 8px 16px 4px;">
                        <small style="color: #9ca3af;">PENGATURAN</small>
                    </div>

                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <i class="bi bi-gear me-2"></i>Pengaturan
                    </a>

                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                        <i class="bi bi-person-circle me-2"></i>Profil
                    </a>
                </div>
            </div>

        <!-- User Info at Bottom -->
        <div class="nav-user-info border-top flex-shrink-0">
            <div class="d-flex align-items-center p-3">
                <div class="user-avatar me-3">
                    <img src="{{ auth()->user()->avatar_url }}" width="40" height="40"
                         class="rounded-circle" alt="Avatar">
                </div>
                <div class="user-details flex-grow-1 min-width-0">
                    <div class="user-name fw-semibold text-truncate" style="color: #374151;">{{ auth()->user()->name }}</div>
                    <div class="user-role small text-truncate" style="color: #9ca3af;">{{ auth()->user()->getRoleNames()->first() }}</div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" style="color: #6b7280;">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
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
    <main class="admin-main-content" id="admin-main-content">
        <!-- Top Header -->
        <header class="admin-navbar navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <!-- Sidebar Toggle -->
                <button class="btn btn-outline-primary me-3" type="button" id="sidebar-toggle" onclick="toggleAdminSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Page Title -->
                <h1 class="navbar-brand mb-0 h1 admin-brand">@yield('page-title')</h1>

                <!-- Header Actions -->
                <div class="navbar-nav ms-auto">
                    @yield('header-actions')
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="admin-content">
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
    
    <!-- Admin JavaScript -->
    <script>
        // Toggle Admin Sidebar
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const mainContent = document.getElementById('admin-main-content');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.classList.toggle('sidebar-open');
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

            // Mobile sidebar functionality
            const overlay = document.getElementById('sidebar-overlay');
            const sidebar = document.getElementById('admin-sidebar');

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                });
            }

            // Close sidebar on window resize if mobile
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                }

                // Handle sidebar scrolling on small screens
                const navContainer = document.querySelector('.nav-menu-container');
                if (navContainer && window.innerHeight < 600) {
                    navContainer.style.maxHeight = (window.innerHeight - 200) + 'px';
                }
            });

            // Initial sidebar scroll handling
            const navContainer = document.querySelector('.nav-menu-container');
            if (navContainer && window.innerHeight < 600) {
                navContainer.style.maxHeight = (window.innerHeight - 200) + 'px';
            }
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
