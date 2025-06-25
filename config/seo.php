<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SEO optimization across the application
    |
    */

    'default' => [
        'title' => 'UNAS Fest 2025 - Festival Kompetisi Nasional Terbesar Indonesia',
        'description' => 'UNAS Fest 2025 adalah festival kompetisi nasional terbesar di Indonesia yang menggabungkan inovasi teknologi, kesehatan, dan biodiversitas. Daftar sekarang dan raih prestasi terbaikmu!',
        'keywords' => 'unas fest, kompetisi nasional, lomba indonesia, teknologi, kesehatan, biodiversitas, universitas nasional, festival mahasiswa, kompetisi mahasiswa, lomba karya tulis, debat, konten digital',
        'author' => 'UNAS Fest 2025 Committee',
        'robots' => 'index, follow',
        'canonical' => env('APP_URL'),
        'og_type' => 'website',
        'twitter_card' => 'summary_large_image',
    ],

    'pages' => [
        'home' => [
            'title' => 'UNAS Fest 2025 - Festival Kompetisi Nasional Terbesar Indonesia',
            'description' => 'Bergabunglah dengan UNAS Fest 2025, festival kompetisi nasional terbesar yang menggabungkan teknologi, kesehatan, dan biodiversitas. Daftar sekarang dan wujudkan potensi terbaikmu!',
            'keywords' => 'unas fest 2025, kompetisi nasional, lomba mahasiswa, teknologi, kesehatan, biodiversitas, festival indonesia',
        ],
        'competitions' => [
            'title' => 'Kompetisi UNAS Fest 2025 - Teknologi, Kesehatan & Biodiversitas',
            'description' => 'Ikuti berbagai kompetisi menarik di UNAS Fest 2025: Kompetisi Teknologi, Kesehatan, dan Biodiversitas. Daftar sekarang dan raih prestasi gemilang!',
            'keywords' => 'kompetisi teknologi, kompetisi kesehatan, kompetisi biodiversitas, lomba karya tulis, debat mahasiswa',
        ],
        'about' => [
            'title' => 'Tentang UNAS Fest 2025 - Panitia & Departemen Penyelenggara',
            'description' => 'Kenali tim panitia UNAS Fest 2025 dari berbagai departemen: IT, Keamanan, Infrastruktur, Hubungan Masyarakat, dan lainnya. Organisasi terpercaya untuk festival kompetisi nasional.',
            'keywords' => 'panitia unas fest, departemen unas fest, tim penyelenggara, universitas nasional, organisasi mahasiswa',
        ],
        'testimonials' => [
            'title' => 'Testimoni Peserta UNAS Fest - Pengalaman & Review',
            'description' => 'Baca testimoni dan pengalaman peserta UNAS Fest dari berbagai kompetisi. Dapatkan insight tentang manfaat mengikuti festival kompetisi nasional terbesar ini.',
            'keywords' => 'testimoni unas fest, review peserta, pengalaman kompetisi, feedback mahasiswa',
        ],
        'contact' => [
            'title' => 'Kontak UNAS Fest 2025 - Hubungi Kami Sekarang',
            'description' => 'Hubungi tim UNAS Fest 2025 untuk informasi lebih lanjut tentang kompetisi, pendaftaran, dan kerjasama. Kami siap membantu Anda 24/7.',
            'keywords' => 'kontak unas fest, hubungi panitia, informasi pendaftaran, customer service',
        ],
        'blog' => [
            'title' => 'Blog UNAS Fest 2025 - Tips, Guide & Berita Kompetisi',
            'description' => 'Baca artikel terbaru tentang tips kompetisi, guide pendaftaran, dan berita terkini seputar UNAS Fest 2025. Tingkatkan peluang menang Anda!',
            'keywords' => 'blog unas fest, tips kompetisi, guide pendaftaran, berita mahasiswa, artikel teknologi',
        ],
    ],

    'assets' => [
        'logo' => [
            'main' => 'assets/images/logo/unas-fest-logo.png',
            'white' => 'assets/images/logo/unas-fest-logo-white.png',
            'icon' => 'assets/images/logo/unas-fest-icon.png',
            'favicon' => 'assets/images/logo/favicon.ico',
        ],
        'hero' => [
            'background' => 'assets/images/hero/hero-bg.jpg',
            'illustration' => 'assets/images/hero/hero-illustration.svg',
            'video' => 'assets/videos/hero-video.mp4',
        ],
        'competitions' => [
            'technology' => 'assets/images/competitions/technology-banner.jpg',
            'health' => 'assets/images/competitions/health-banner.jpg',
            'biodiversity' => 'assets/images/competitions/biodiversity-banner.jpg',
        ],
        'departments' => [
            'security' => 'assets/images/departments/security.jpg',
            'infrastructure' => 'assets/images/departments/infrastructure.jpg',
            'fnb' => 'assets/images/departments/fnb.jpg',
            'health' => 'assets/images/departments/health.jpg',
            'debate' => 'assets/images/departments/debate.jpg',
            'pr' => 'assets/images/departments/pr.jpg',
            'finance' => 'assets/images/departments/finance.jpg',
            'scientific' => 'assets/images/departments/scientific.jpg',
            'digital' => 'assets/images/departments/digital.jpg',
            'partnership' => 'assets/images/departments/partnership.jpg',
            'entertainment' => 'assets/images/departments/entertainment.jpg',
            'secretarial' => 'assets/images/departments/secretarial.jpg',
            'advertising' => 'assets/images/departments/advertising.jpg',
            'it' => 'assets/images/departments/it.jpg',
        ],
        'testimonials' => [
            'default_avatar' => 'assets/images/testimonials/default-avatar.png',
            'background' => 'assets/images/testimonials/testimonials-bg.jpg',
        ],
        'animations' => [
            'hero_lottie' => 'assets/animations/hero-animation.json',
            'loading' => 'assets/animations/loading.json',
            'success' => 'assets/animations/success.json',
        ],
    ],

    'social' => [
        'facebook' => 'https://facebook.com/unasfest',
        'instagram' => 'https://instagram.com/unasfest',
        'twitter' => 'https://twitter.com/unasfest',
        'youtube' => 'https://youtube.com/unasfest',
        'linkedin' => 'https://linkedin.com/company/unasfest',
        'tiktok' => 'https://tiktok.com/@unasfest',
    ],

    'contact' => [
        'email' => 'info@unasfest.com',
        'phone' => '+62 21 7806700',
        'whatsapp' => '+62 812 3456 7890',
        'telegram' => '@unasfest_bot',
        'address' => 'Universitas Nasional, Jl. Sawo Manila No.61, Pejaten, Jakarta Selatan 12560',
    ],

    'structured_data' => [
        'organization' => [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'UNAS Fest 2025',
            'url' => env('APP_URL'),
            'logo' => env('APP_URL') . '/assets/images/logo/unas-fest-logo.png',
            'description' => 'Festival kompetisi nasional terbesar di Indonesia',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Jl. Sawo Manila No.61, Pejaten',
                'addressLocality' => 'Jakarta Selatan',
                'postalCode' => '12560',
                'addressCountry' => 'ID',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+62-21-7806700',
                'contactType' => 'customer service',
            ],
            'sameAs' => [
                'https://facebook.com/unasfest',
                'https://instagram.com/unasfest',
                'https://twitter.com/unasfest',
            ],
        ],
        'event' => [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => 'UNAS Fest 2025',
            'description' => 'Festival kompetisi nasional terbesar di Indonesia',
            'startDate' => '2025-03-01',
            'endDate' => '2025-03-31',
            'location' => [
                '@type' => 'Place',
                'name' => 'Universitas Nasional',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Jl. Sawo Manila No.61, Pejaten',
                    'addressLocality' => 'Jakarta Selatan',
                    'postalCode' => '12560',
                    'addressCountry' => 'ID',
                ],
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'UNAS Fest Committee',
                'url' => env('APP_URL'),
            ],
        ],
    ],
];
