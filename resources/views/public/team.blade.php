@extends('layouts.modern')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900 text-white">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-6">
                <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-semibold">Tim Profesional</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-clash font-bold mb-6">
                Tim <span class="text-gradient-accent">UNAS Fest 2025</span>
            </h1>
            
            <p class="text-xl text-gray-300 leading-relaxed">
                Bertemu dengan tim profesional yang berdedikasi untuk menyelenggarakan festival kompetisi nasional terbesar di Indonesia
            </p>
        </div>
    </div>
</section>

<!-- Leadership Team -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-clash font-bold text-gray-900 mb-6">
                Tim Kepemimpinan
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Para pemimpin berpengalaman yang memandu visi dan misi UNAS Fest 2025
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Leadership Team Members -->
            @for($i = 1; $i <= 6; $i++)
            <div class="card card-hover text-center p-8">
                <div class="w-32 h-32 bg-gradient-to-br from-violet-100 to-blue-100 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-16 h-16 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    Leader {{ $i }}
                </h3>
                <p class="text-violet-600 font-medium mb-3">
                    {{ $i == 1 ? 'Project Director' : ($i == 2 ? 'Technical Lead' : ($i == 3 ? 'Operations Manager' : ($i == 4 ? 'Marketing Director' : ($i == 5 ? 'Finance Manager' : 'Community Relations')))) }}
                </p>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Berpengalaman dalam mengelola event skala nasional dengan track record yang terbukti dalam industri.
                </p>
                <div class="flex justify-center space-x-3 mt-4">
                    <a href="#" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-violet-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                        </svg>
                    </a>
                    <a href="#" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center hover:bg-violet-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<!-- Departments -->
<section class="section bg-gray-50">
    <div class="container-custom">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-clash font-bold text-gray-900 mb-6">
                Departemen & Divisi
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Tim yang terdiri dari berbagai departemen dengan keahlian khusus untuk memastikan kesuksesan event
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Departments -->
            @php
            $departments = [
                ['name' => 'Technology', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'color' => 'violet'],
                ['name' => 'Marketing', 'icon' => 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z', 'color' => 'blue'],
                ['name' => 'Operations', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'emerald'],
                ['name' => 'Finance', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'color' => 'amber'],
                ['name' => 'Design', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM7 3H5v12a2 2 0 002 2 2 2 0 002-2V3z M15 3h4a2 2 0 012 2v12a4 4 0 01-4 4 4 4 0 01-4-4V5a2 2 0 012-2z', 'color' => 'pink'],
                ['name' => 'Security', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'red'],
                ['name' => 'Media', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'color' => 'indigo'],
                ['name' => 'Logistics', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'teal']
            ];
            @endphp
            
            @foreach($departments as $dept)
            <div class="card card-hover text-center p-6">
                <div class="w-16 h-16 bg-{{ $dept['color'] }}-100 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-{{ $dept['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $dept['icon'] }}"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $dept['name'] }}</h3>
                <p class="text-gray-600 text-sm">
                    Tim profesional yang menangani aspek {{ strtolower($dept['name']) }} dari event
                </p>
                <div class="mt-4 text-{{ $dept['color'] }}-600 font-medium text-sm">
                    {{ rand(5, 15) }} Anggota
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Join Team CTA -->
<section class="section bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900 text-white">
    <div class="container-custom text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-clash font-bold mb-6">
                Bergabung dengan Tim Kami
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Kami selalu mencari talenta terbaik untuk bergabung dengan tim UNAS Fest. Mari bersama-sama menciptakan event yang luar biasa!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.contact') }}" class="btn-primary text-lg px-8 py-4">
                    Hubungi Kami
                </a>
                <a href="{{ route('public.about') }}" class="btn-secondary border-white text-white hover:bg-white hover:text-gray-900 text-lg px-8 py-4">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
