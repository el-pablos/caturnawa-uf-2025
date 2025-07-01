@extends('layouts.modern')

@section('content')
<!-- Hero Section with Asymmetric Layout -->
<section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900">
    <!-- Animated Background Blobs -->
    <div class="absolute inset-0">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="floating-element w-20 h-20 bg-white/10 rounded-full top-20 left-20 animate-float"></div>
        <div class="floating-element w-32 h-32 bg-accent-400/20 rounded-full top-40 right-32 animate-float" style="animation-delay: 2s;"></div>
        <div class="floating-element w-16 h-16 bg-violet-400/30 rounded-full bottom-32 left-40 animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="container-custom relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content - Asymmetric -->
            <div class="space-y-8 fade-in-left">
                <div class="space-y-6">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 text-white">
                        <svg class="w-5 h-5 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-medium">Festival Kompetisi #1 di Indonesia</span>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-clash font-bold text-white leading-tight">
                        UNAS Fest
                        <span class="block text-gradient-accent">2025</span>
                    </h1>

                    <p class="text-xl text-gray-300 leading-relaxed max-w-2xl">
                        Bergabunglah dengan festival kompetisi nasional terbesar di Indonesia yang menggabungkan
                        <span class="text-accent-400 font-semibold">Teknologi</span>,
                        <span class="text-emerald-400 font-semibold">Kesehatan</span>, dan
                        <span class="text-cyan-400 font-semibold">Biodiversitas</span>
                        untuk masa depan berkelanjutan.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('public.competitions') }}" class="btn-primary group">
                        <span>Daftar Kompetisi</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="{{ route('public.about') }}" class="btn-secondary">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-6 pt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">500M</div>
                        <div class="text-sm text-gray-400">Total Hadiah</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">10K+</div>
                        <div class="text-sm text-gray-400">Peserta</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">100+</div>
                        <div class="text-sm text-gray-400">Universitas</div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Visual Stats -->
            <div class="relative fade-in-right">
                <div class="relative">
                    <!-- Main Visual Element -->
                    <div class="relative w-full h-96 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                        <!-- Floating Stats Cards -->
                        <div class="absolute -top-6 -left-6 bg-white rounded-2xl p-6 shadow-xl scale-in" style="animation-delay: 0.5s;">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-violet-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">10,000+</div>
                                    <div class="text-sm text-gray-600">Peserta Terdaftar</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-6 -right-6 bg-white rounded-2xl p-6 shadow-xl scale-in" style="animation-delay: 0.7s;">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-accent-500 to-orange-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">500M</div>
                                    <div class="text-sm text-gray-600">Total Hadiah</div>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 bg-white rounded-2xl p-6 shadow-xl scale-in" style="animation-delay: 0.9s;">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-gray-900">100+</div>
                                    <div class="text-sm text-gray-600">Universitas Partner</div>
                                </div>
                            </div>
                        </div>

                        <!-- Central Content -->
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-white">
                                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold mb-2">Festival Terpercaya</h3>
                                <p class="text-gray-300 text-sm">Diselenggarakan oleh Universitas Nasional</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white animate-bounce">
        <div class="flex flex-col items-center space-y-2">
            <span class="text-sm">Scroll untuk melihat lebih banyak</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

<!-- Competition Categories Section -->
<section class="section bg-gray-50">
    <div class="container-custom">
        <div class="text-center mb-16 fade-in-up">
            <div class="inline-flex items-center space-x-2 bg-violet-100 text-violet-700 rounded-full px-6 py-3 mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                <span class="font-semibold">Kategori Kompetisi</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-clash font-bold text-gray-900 mb-6">
                Tiga Pilar <span class="text-gradient">Inovasi</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Setiap kategori kompetisi dirancang untuk mendorong inovasi dalam bidang yang akan membentuk masa depan Indonesia
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Technology Competition -->
            <div class="group card card-hover p-8 fade-in-up" style="animation-delay: 0.1s;" x-data="{ expanded: false }">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-violet-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Teknologi</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Kompetisi pengembangan aplikasi, AI, IoT, dan solusi teknologi inovatif untuk menyelesaikan masalah nyata di masyarakat.
                    </p>
                </div>

                <div class="space-y-3 mb-8" x-show="expanded" x-transition>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700">Mobile App Development</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-violet-500 rounded-full"></div>
                        <span class="text-gray-700">Web Development</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        <span class="text-gray-700">AI & Machine Learning</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <span class="text-gray-700">IoT Solutions</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button @click="expanded = !expanded" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        <span x-text="expanded ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    </button>
                    <a href="{{ route('public.competitions') }}#technology" class="btn-ghost text-blue-600 hover:bg-blue-50">
                        Daftar →
                    </a>
                </div>

                <!-- Prize Badge -->
                <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-500 to-violet-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    50M
                </div>
            </div>

            <!-- Health Competition -->
            <div class="group card card-hover p-8 fade-in-up" style="animation-delay: 0.2s;" x-data="{ expanded: false }">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Kesehatan</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Ciptakan inovasi kesehatan untuk meningkatkan kualitas hidup masyarakat melalui teknologi dan penelitian medis.
                    </p>
                </div>

                <div class="space-y-3 mb-8" x-show="expanded" x-transition>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <span class="text-gray-700">Health Tech Innovation</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                        <span class="text-gray-700">Medical Device Design</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-gray-700">Public Health Solutions</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-cyan-500 rounded-full"></div>
                        <span class="text-gray-700">Digital Health Platform</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button @click="expanded = !expanded" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                        <span x-text="expanded ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    </button>
                    <a href="{{ route('public.competitions') }}#health" class="btn-ghost text-emerald-600 hover:bg-emerald-50">
                        Daftar →
                    </a>
                </div>

                <!-- Prize Badge -->
                <div class="absolute top-4 right-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    45M
                </div>
            </div>

            <!-- Biodiversity Competition -->
            <div class="group card card-hover p-8 fade-in-up" style="animation-delay: 0.3s;" x-data="{ expanded: false }">
                <div class="relative mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Biodiversitas</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Kembangkan solusi berkelanjutan untuk pelestarian lingkungan dan keanekaragaman hayati Indonesia.
                    </p>
                </div>

                <div class="space-y-3 mb-8" x-show="expanded" x-transition>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-cyan-500 rounded-full"></div>
                        <span class="text-gray-700">Environmental Conservation</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-700">Sustainable Technology</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                        <span class="text-gray-700">Ecosystem Research</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                        <span class="text-gray-700">Climate Solutions</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button @click="expanded = !expanded" class="text-cyan-600 hover:text-cyan-700 font-medium transition-colors">
                        <span x-text="expanded ? 'Tutup Detail' : 'Lihat Detail'"></span>
                    </button>
                    <a href="{{ route('public.competitions') }}#biodiversity" class="btn-ghost text-cyan-600 hover:bg-cyan-50">
                        Daftar →
                    </a>
                </div>

                <!-- Prize Badge -->
                <div class="absolute top-4 right-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    40M
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Interactive Timeline Section -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="text-center mb-16 fade-in-up">
            <div class="inline-flex items-center space-x-2 bg-accent-100 text-accent-700 rounded-full px-6 py-3 mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">Timeline Kompetisi</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-clash font-bold text-gray-900 mb-6">
                Jadwal <span class="text-gradient-accent">Penting</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Ikuti setiap tahapan kompetisi dengan cermat agar tidak melewatkan kesempatan emas ini
            </p>
        </div>

        <div class="relative max-w-4xl mx-auto" x-data="{ activeStep: 1 }">
            <!-- Timeline Line -->
            <div class="timeline-line h-full"></div>

            <!-- Timeline Items -->
            <div class="space-y-16">
                <!-- Step 1: Registration -->
                <div class="relative flex items-center" @click="activeStep = 1">
                    <div class="timeline-dot cursor-pointer" :class="{ 'active': activeStep === 1 }"></div>
                    <div class="ml-8 lg:ml-16 w-full">
                        <div class="grid lg:grid-cols-2 gap-8 items-center">
                            <div class="fade-in-left">
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="bg-violet-100 text-violet-700 px-3 py-1 rounded-full text-sm font-semibold">Tahap 1</span>
                                    <span class="text-gray-500">1 Jan - 28 Feb 2025</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pendaftaran Dibuka</h3>
                                <p class="text-gray-600 leading-relaxed mb-6">
                                    Daftarkan tim Anda dan pilih kategori kompetisi yang sesuai dengan keahlian dan minat. Proses pendaftaran mudah dan cepat.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Registrasi online 24/7</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Tim maksimal 4 orang</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Konfirmasi otomatis</span>
                                    </div>
                                </div>
                            </div>
                            <div class="fade-in-right">
                                <div class="bg-gradient-to-br from-violet-50 to-blue-50 rounded-3xl p-8">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-gradient-to-r from-violet-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">Mulai Perjalanan Anda</h4>
                                        <p class="text-gray-600 mb-6">Bergabung dengan ribuan peserta lainnya</p>
                                        <a href="{{ route('register') }}" class="btn-primary">
                                            Daftar Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Submission -->
                <div class="relative flex items-center" @click="activeStep = 2">
                    <div class="timeline-dot cursor-pointer" :class="{ 'active': activeStep === 2 }"></div>
                    <div class="ml-8 lg:ml-16 w-full">
                        <div class="grid lg:grid-cols-2 gap-8 items-center">
                            <div class="lg:order-2 fade-in-right">
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="bg-accent-100 text-accent-700 px-3 py-1 rounded-full text-sm font-semibold">Tahap 2</span>
                                    <span class="text-gray-500">1 - 15 Mar 2025</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengumpulan Karya</h3>
                                <p class="text-gray-600 leading-relaxed mb-6">
                                    Kumpulkan hasil karya terbaik tim Anda. Pastikan semua dokumen dan file telah sesuai dengan ketentuan yang berlaku.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Upload sistem terintegrasi</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Format file fleksibel</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Bantuan teknis 24/7</span>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:order-1 fade-in-left">
                                <div class="bg-gradient-to-br from-accent-50 to-orange-50 rounded-3xl p-8">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-gradient-to-r from-accent-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">Wujudkan Ide Anda</h4>
                                        <p class="text-gray-600 mb-6">Saatnya menunjukkan karya terbaik</p>
                                        <div class="bg-white rounded-xl p-4">
                                            <div class="text-2xl font-bold text-accent-600">15 Hari</div>
                                            <div class="text-sm text-gray-600">Waktu pengumpulan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Evaluation -->
                <div class="relative flex items-center" @click="activeStep = 3">
                    <div class="timeline-dot cursor-pointer" :class="{ 'active': activeStep === 3 }"></div>
                    <div class="ml-8 lg:ml-16 w-full">
                        <div class="grid lg:grid-cols-2 gap-8 items-center">
                            <div class="fade-in-left">
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-semibold">Tahap 3</span>
                                    <span class="text-gray-500">16 - 25 Mar 2025</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Penilaian Juri</h3>
                                <p class="text-gray-600 leading-relaxed mb-6">
                                    Tim juri profesional akan mengevaluasi setiap karya berdasarkan kriteria yang telah ditetapkan dengan standar internasional.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Juri berpengalaman internasional</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Penilaian transparan</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Feedback konstruktif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="fade-in-right">
                                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl p-8">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">Evaluasi Profesional</h4>
                                        <p class="text-gray-600 mb-6">Standar penilaian internasional</p>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-white rounded-xl p-3">
                                                <div class="text-lg font-bold text-emerald-600">15+</div>
                                                <div class="text-xs text-gray-600">Juri Ahli</div>
                                            </div>
                                            <div class="bg-white rounded-xl p-3">
                                                <div class="text-lg font-bold text-emerald-600">10</div>
                                                <div class="text-xs text-gray-600">Hari Evaluasi</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Announcement -->
                <div class="relative flex items-center" @click="activeStep = 4">
                    <div class="timeline-dot cursor-pointer" :class="{ 'active': activeStep === 4 }"></div>
                    <div class="ml-8 lg:ml-16 w-full">
                        <div class="grid lg:grid-cols-2 gap-8 items-center">
                            <div class="lg:order-2 fade-in-right">
                                <div class="flex items-center space-x-3 mb-4">
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">Tahap 4</span>
                                    <span class="text-gray-500">30 Mar 2025</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">Pengumuman Pemenang</h3>
                                <p class="text-gray-600 leading-relaxed mb-6">
                                    Momen yang ditunggu-tunggu! Pengumuman pemenang akan dilakukan secara live streaming dan dihadiri oleh seluruh peserta.
                                </p>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Live streaming ceremony</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Sertifikat digital</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700">Networking session</span>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:order-1 fade-in-left">
                                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-3xl p-8">
                                    <div class="text-center">
                                        <div class="w-20 h-20 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-xl font-bold text-gray-900 mb-2">Momen Bersejarah</h4>
                                        <p class="text-gray-600 mb-6">Rayakan pencapaian luar biasa</p>
                                        <div class="bg-white rounded-xl p-4">
                                            <div class="text-2xl font-bold text-yellow-600">500M</div>
                                            <div class="text-sm text-gray-600">Total Hadiah</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900 text-white">
    <div class="container-custom text-center">
        <div class="max-w-4xl mx-auto fade-in-up">
            <h2 class="text-4xl md:text-5xl font-clash font-bold mb-6">
                Siap Menjadi Bagian dari <span class="text-gradient-accent">Sejarah?</span>
            </h2>
            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                Bergabunglah dengan ribuan mahasiswa terbaik Indonesia dan wujudkan inovasi yang akan mengubah masa depan bangsa
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4">
                    <span>Daftar Sekarang</span>
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
                <a href="{{ route('public.competitions') }}" class="btn-secondary text-lg px-8 py-4 border-white text-white hover:bg-white hover:text-gray-900">
                    Lihat Kompetisi
                </a>
            </div>

            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-8 border-t border-white/20">
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1">10,000+</div>
                    <div class="text-sm text-gray-400">Peserta Terdaftar</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1">100+</div>
                    <div class="text-sm text-gray-400">Universitas Partner</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1">15</div>
                    <div class="text-sm text-gray-400">Kategori Kompetisi</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold mb-1">500M</div>
                    <div class="text-sm text-gray-400">Total Hadiah</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection