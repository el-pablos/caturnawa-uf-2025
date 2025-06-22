<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - {{ config('app.name') }}</title>
    
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

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 280px;
            --navy: #0B1D51;
            --purple: #725CAD;
            --blue: #8CCDEB;
            --beige: #FFE3A9;
            --navy-rgb: 11, 29, 81;
            --purple-rgb: 114, 92, 173;
            --blue-rgb: 140, 205, 235;
            --beige-rgb: 255, 227, 169;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--navy) 0%, var(--purple) 100%);
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 0 0 20px rgba(var(--navy-rgb), 0.3);
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s ease;
            padding: 12px 16px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(var(--blue-rgb), 0.2);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(var(--blue-rgb), 0.3);
        }

        .sidebar .nav-link.active {
            color: var(--navy);
            background-color: var(--beige);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(var(--beige-rgb), 0.4);
            font-weight: 600;
        }

        .nav-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 0 16px;
        }

        .nav-section-title {
            padding: 8px 16px 4px;
        }

        .nav-user-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(var(--blue-rgb), 0.1) 0%, rgba(var(--beige-rgb), 0.1) 100%);
            transition: margin-left 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }
        
        .card {
            border: none;
            box-shadow: var(--unas-shadow);
            border-radius: var(--unas-radius);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--unas-shadow-lg);
        }

        .btn {
            border-radius: var(--unas-radius);
            font-weight: 600;
            padding: var(--unas-space-3) var(--unas-space-5);
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--unas-primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--unas-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--unas-shadow);
        }

        .badge {
            border-radius: var(--unas-radius-full);
            padding: var(--unas-space-2) var(--unas-space-3);
            font-weight: 600;
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .form-control,
        .form-select {
            border-radius: var(--unas-radius);
            border: 2px solid var(--unas-gray-200);
            padding: var(--unas-space-3) var(--unas-space-4);
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--unas-primary);
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
            background: var(--unas-white);
        }
        
        .stats-card {
            background: var(--unas-white);
            color: var(--unas-gray-800);
            border-radius: var(--unas-radius-lg);
            padding: var(--unas-space-6);
            box-shadow: var(--unas-shadow);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--unas-primary);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--unas-shadow-xl);
        }

        .stats-card .stats-number {
            font-size: var(--unas-font-size-3xl);
            font-weight: 700;
            color: var(--unas-primary);
            margin-bottom: var(--unas-space-2);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="position-sticky">
            <!-- Brand -->
            <div class="navbar-brand px-3 py-3 border-bottom border-light border-opacity-25">
                <i class="bi bi-building me-2"></i>
                {{ config('app.name') }}
            </div>
            
            <!-- Navigation Menu -->
            <div class="nav nav-pills flex-column p-3" id="nav-tab">
                @include('partials.admin-sidebar')
            </div>

        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <!-- Top Header -->
        <header class="navbar navbar-expand-lg navbar-light sticky-top" style="background: linear-gradient(90deg, var(--blue) 0%, var(--beige) 100%); border-bottom: 2px solid var(--navy);">
            <div class="container-fluid">
                <!-- Sidebar Toggle -->
                <button class="btn btn-outline-dark me-3" type="button" onclick="toggleSidebar()" style="border-color: var(--navy); color: var(--navy);">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Page Title -->
                <h1 class="navbar-brand mb-0 h1" style="color: var(--navy); font-weight: 700;">@yield('page-title')</h1>

                <!-- Header Actions -->
                <div class="navbar-nav ms-auto">
                    @yield('header-actions')
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="container-fluid py-4" style="background: rgba(255, 255, 255, 0.9); border-radius: 20px 20px 0 0; margin-top: 20px; min-height: calc(100vh - 120px); box-shadow: 0 -4px 20px rgba(var(--navy-rgb), 0.1);">
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
    
    <!-- Custom JavaScript -->
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
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
        
        // Global loading state handler
        function showLoading(element) {
            $(element).addClass('loading');
        }
        
        function hideLoading(element) {
            $(element).removeClass('loading');
        }
        
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
