{{-- SEO Component for UNAS Fest 2025 --}}
@props([
    'title' => 'UNAS Fest 2025 - Festival Kompetisi Teknologi Terbesar',
    'description' => 'UNAS Fest 2025 adalah festival kompetisi teknologi terbesar di Indonesia. Ikuti berbagai kompetisi programming, design, business plan, dan essay writing dengan total hadiah jutaan rupiah.',
    'keywords' => 'unas fest, kompetisi teknologi, programming contest, design competition, business plan, essay writing, universitas nasional, jakarta, indonesia, teknologi, inovasi',
    'image' => null,
    'url' => null,
    'type' => 'website',
    'author' => 'UNAS Fest 2025 Committee',
    'robots' => 'index, follow',
    'canonical' => null
])

@php
    $defaultImage = asset('images/unas-fest-2025-og.jpg');
    $currentUrl = $url ?? request()->url();
    $canonicalUrl = $canonical ?? $currentUrl;
    $ogImage = $image ?? $defaultImage;
    $siteName = 'UNAS Fest 2025';
    $locale = 'id_ID';
@endphp

{{-- Basic Meta Tags --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO Meta Tags --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="{{ $robots }}">
<meta name="language" content="Indonesian">
<meta name="revisit-after" content="7 days">
<meta name="distribution" content="global">
<meta name="rating" content="general">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Open Graph Meta Tags --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:url" content="{{ $currentUrl }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:site" content="@unasfest">
<meta name="twitter:creator" content="@unasfest">

{{-- Additional Meta Tags for Better SEO --}}
<meta name="theme-color" content="#1e40af">
<meta name="msapplication-TileColor" content="#1e40af">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $siteName }}">

{{-- Favicon and Icons --}}
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

{{-- Preconnect for Performance --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net">

{{-- DNS Prefetch for External Resources --}}
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="dns-prefetch" href="//cdn.jsdelivr.net">
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">

{{-- Structured Data for Rich Snippets --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "UNAS Fest 2025",
    "description": "{{ $description }}",
    "image": "{{ $ogImage }}",
    "startDate": "2025-03-01",
    "endDate": "2025-03-31",
    "eventStatus": "https://schema.org/EventScheduled",
    "eventAttendanceMode": "https://schema.org/MixedEventAttendanceMode",
    "location": {
        "@type": "Place",
        "name": "Universitas Nasional",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Jl. Sawo Manila No.61",
            "addressLocality": "Jakarta Selatan",
            "addressRegion": "DKI Jakarta",
            "postalCode": "12520",
            "addressCountry": "ID"
        }
    },
    "organizer": {
        "@type": "Organization",
        "name": "Universitas Nasional",
        "url": "https://unas.ac.id"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ route('public.competitions') }}",
        "price": "0",
        "priceCurrency": "IDR",
        "availability": "https://schema.org/InStock",
        "validFrom": "2025-01-01"
    }
}
</script>

{{-- Organization Structured Data --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "UNAS Fest 2025",
    "url": "{{ config('app.url') }}",
    "logo": "{{ asset('images/unas-fest-logo.png') }}",
    "description": "{{ $description }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Sawo Manila No.61",
        "addressLocality": "Jakarta Selatan",
        "addressRegion": "DKI Jakarta",
        "postalCode": "12520",
        "addressCountry": "ID"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62-21-7806700",
        "contactType": "customer service",
        "availableLanguage": ["Indonesian", "English"]
    },
    "sameAs": [
        "https://www.facebook.com/unasfest",
        "https://www.instagram.com/unasfest",
        "https://www.twitter.com/unasfest",
        "https://www.youtube.com/unasfest"
    ]
}
</script>

{{-- Website Structured Data --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ config('app.url') }}",
    "description": "{{ $description }}",
    "publisher": {
        "@type": "Organization",
        "name": "Universitas Nasional"
    },
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "{{ route('public.competitions') }}?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>

{{-- Breadcrumb Structured Data (if breadcrumbs are provided) --}}
@if(isset($breadcrumbs) && count($breadcrumbs) > 1)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        @foreach($breadcrumbs as $index => $breadcrumb)
        {
            "@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $breadcrumb['name'] }}",
            "item": "{{ $breadcrumb['url'] }}"
        }@if(!$loop->last),@endif
        @endforeach
    ]
}
</script>
@endif

{{-- Performance Optimization --}}
<link rel="preload" href="{{ asset('css/unas-theme.css') }}" as="style">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" as="style">

{{-- Security Headers --}}
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; connect-src 'self' https:;">
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">

{{-- Additional Performance Hints --}}
<meta http-equiv="Accept-CH" content="DPR, Viewport-Width, Width">
<meta name="format-detection" content="telephone=no">
<meta name="mobile-web-app-capable" content="yes">

{{-- Geo Location (if applicable) --}}
<meta name="geo.region" content="ID-JK">
<meta name="geo.placename" content="Jakarta, Indonesia">
<meta name="geo.position" content="-6.2088;106.8456">
<meta name="ICBM" content="-6.2088, 106.8456">

{{-- Copyright and Legal --}}
<meta name="copyright" content="© 2025 UNAS Fest. All rights reserved.">
<meta name="generator" content="Laravel {{ app()->version() }}">

{{-- Hreflang for International SEO (if applicable) --}}
<link rel="alternate" hreflang="id" href="{{ $currentUrl }}">
<link rel="alternate" hreflang="en" href="{{ $currentUrl }}">
<link rel="alternate" hreflang="x-default" href="{{ $currentUrl }}">
