@extends('layouts.modern')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900 text-white">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-6">
                <svg class="w-5 h-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="font-semibold">{{ $post['category'] ?? 'Blog' }}</span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-clash font-bold mb-6">
                {{ $post['title'] }}
            </h1>
            
            <div class="flex items-center justify-center space-x-6 text-gray-300">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ $post['author'] }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $post['published_at']->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Article Content -->
<section class="section bg-white">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            <div class="prose prose-lg max-w-none">
                <!-- Featured Image -->
                @if(isset($post['featured_image']))
                <div class="mb-8">
                    <img src="{{ $post['featured_image'] }}" 
                         alt="{{ $post['title'] }}" 
                         class="w-full h-96 object-cover rounded-3xl shadow-lg"
                         onerror="this.style.display='none';">
                </div>
                @endif
                
                <!-- Article Content -->
                <div class="text-gray-700 leading-relaxed">
                    {!! $post['content'] !!}
                </div>
                
                <!-- Tags -->
                @if(isset($post['tags']) && is_array($post['tags']))
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Tags:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post['tags'] as $tag)
                        <span class="bg-violet-100 text-violet-700 px-3 py-1 rounded-full text-sm font-medium">
                            #{{ $tag }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
<section class="section bg-gray-50">
    <div class="container-custom">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-clash font-bold text-gray-900 mb-4">
                Artikel Terkait
            </h2>
            <p class="text-gray-600">
                Baca artikel lainnya yang mungkin menarik untuk Anda
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Sample Related Articles -->
            @for($i = 1; $i <= 3; $i++)
            <article class="card card-hover">
                <div class="h-48 bg-gradient-to-br from-violet-100 to-blue-100 rounded-t-3xl flex items-center justify-center">
                    <svg class="w-16 h-16 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div class="p-6">
                    <div class="text-sm text-violet-600 font-medium mb-2">Tips</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">
                        Artikel Terkait {{ $i }}
                    </h3>
                    <p class="text-gray-600 mb-4">
                        Deskripsi singkat artikel yang memberikan gambaran tentang isi artikel tersebut.
                    </p>
                    <a href="{{ route('public.blog.detail', 'artikel-' . $i) }}" class="text-violet-600 hover:text-violet-700 font-medium">
                        Baca Selengkapnya →
                    </a>
                </div>
            </article>
            @endfor
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section bg-gradient-to-br from-violet-900 via-blue-900 to-indigo-900 text-white">
    <div class="container-custom text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-clash font-bold mb-6">
                Siap Bergabung dengan UNAS Fest 2025?
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Jangan lewatkan kesempatan emas untuk menjadi bagian dari festival kompetisi nasional terbesar
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary text-lg px-8 py-4">
                    Daftar Sekarang
                </a>
                <a href="{{ route('public.competitions') }}" class="btn-secondary border-white text-white hover:bg-white hover:text-gray-900 text-lg px-8 py-4">
                    Lihat Kompetisi
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
