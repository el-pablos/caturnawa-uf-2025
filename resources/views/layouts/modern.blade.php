<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    {!! SEOTools::generate() !!}
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
</head>
<body class="antialiased bg-gray-50" x-data="{ mobileMenuOpen: false }" :class="{ 'overflow-hidden': mobileMenuOpen }">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100" x-data="{ scrolled: false }" 
         @scroll.window="scrolled = window.pageYOffset > 20" 
         :class="{ 'shadow-lg': scrolled }">
        <div class="container-custom">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('public.home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-violet-600 to-blue-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-lg">UF</span>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-clash font-bold text-gray-900">UNAS Fest</h1>
                            <p class="text-xs text-gray-500 -mt-1">2025</p>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ route('public.home') }}" class="nav-link {{ request()->routeIs('public.home') ? 'text-violet-600' : '' }}">
                        Beranda
                    </a>
                    <a href="{{ route('public.competitions') }}" class="nav-link {{ request()->routeIs('public.competitions') ? 'text-violet-600' : '' }}">
                        Kompetisi
                    </a>
                    <a href="{{ route('public.about') }}" class="nav-link {{ request()->routeIs('public.about') ? 'text-violet-600' : '' }}">
                        Tentang
                    </a>
                    <a href="{{ route('public.testimonials') }}" class="nav-link {{ request()->routeIs('public.testimonials') ? 'text-violet-600' : '' }}">
                        Testimoni
                    </a>
                    <a href="{{ route('public.contact') }}" class="nav-link {{ request()->routeIs('public.contact') ? 'text-violet-600' : '' }}">
                        Kontak
                    </a>
                </div>
                
                <!-- CTA Button -->
                <div class="hidden lg:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-ghost">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-ghost">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn-primary">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="lg:hidden bg-white border-t border-gray-100">
            <div class="container-custom py-6">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('public.home') }}" class="text-gray-900 hover:text-violet-600 font-medium py-2 transition-colors">
                        Beranda
                    </a>
                    <a href="{{ route('public.competitions') }}" class="text-gray-900 hover:text-violet-600 font-medium py-2 transition-colors">
                        Kompetisi
                    </a>
                    <a href="{{ route('public.about') }}" class="text-gray-900 hover:text-violet-600 font-medium py-2 transition-colors">
                        Tentang
                    </a>
                    <a href="{{ route('public.testimonials') }}" class="text-gray-900 hover:text-violet-600 font-medium py-2 transition-colors">
                        Testimoni
                    </a>
                    <a href="{{ route('public.contact') }}" class="text-gray-900 hover:text-violet-600 font-medium py-2 transition-colors">
                        Kontak
                    </a>
                    
                    <div class="pt-4 border-t border-gray-100">
                        @auth
                            <a href="{{ route('dashboard') }}" class="block w-full text-center py-3 px-4 bg-gray-100 text-gray-900 rounded-xl font-medium mb-3 transition-colors hover:bg-gray-200">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-center py-3 px-4 bg-red-100 text-red-600 rounded-xl font-medium transition-colors hover:bg-red-200">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center py-3 px-4 bg-gray-100 text-gray-900 rounded-xl font-medium mb-3 transition-colors hover:bg-gray-200">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="block w-full text-center py-3 px-4 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-medium transition-all hover:shadow-lg">
                                Daftar Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container-custom">
            <!-- Main Footer -->
            <div class="py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-violet-600 to-blue-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">UF</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-clash font-bold">UNAS Fest 2025</h3>
                                <p class="text-gray-400 text-sm">Festival Kompetisi Nasional</p>
                            </div>
                        </div>
                        <p class="text-gray-400 leading-relaxed mb-6 max-w-md">
                            Festival kompetisi nasional terbesar di Indonesia yang menggabungkan teknologi, kesehatan, dan biodiversitas untuk masa depan berkelanjutan.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-violet-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-violet-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-violet-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-semibold mb-6">Menu Utama</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('public.home') }}" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="{{ route('public.competitions') }}" class="text-gray-400 hover:text-white transition-colors">Kompetisi</a></li>
                            <li><a href="{{ route('public.about') }}" class="text-gray-400 hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="{{ route('public.testimonials') }}" class="text-gray-400 hover:text-white transition-colors">Testimoni</a></li>
                            <li><a href="{{ route('public.contact') }}" class="text-gray-400 hover:text-white transition-colors">Kontak</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact Info -->
                    <div>
                        <h4 class="text-lg font-semibold mb-6">Kontak</h4>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-violet-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-gray-400">info@unasfest.com</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-violet-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="text-gray-400">+62 21 7806700</span>
                            </li>
                            <li class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-violet-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-400">Jakarta Selatan, Indonesia</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ date('Y') }} UNAS Fest. All rights reserved.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Scroll to Top Button -->
    <button x-data="{ show: false }" 
            @scroll.window="show = window.pageYOffset > 500"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-8 right-8 w-12 h-12 bg-violet-600 text-white rounded-full shadow-lg hover:bg-violet-700 transition-colors z-40 flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>
    
    <!-- Intersection Observer for Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);
            
            // Observe all elements with animation classes
            document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right, .scale-in').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
