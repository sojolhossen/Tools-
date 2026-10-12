@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogType' => 'website',
])

@php
    $locale = app()->getLocale();
    $pageTitle = $title 
        ? $title . ' — ' . config('app.name', 'OmniTools') 
        : config('app.name', 'OmniTools') . ' — ' . __('common.tagline');
    $pageDesc = $description ?? __('common.meta_description_default');
    $currentUrl = $canonical ?? url()->current();

    // Hreflang URLs
    $currentRoute = Route::currentRouteName() ?? 'home';
    $params = Route::current() ? Route::current()->parameters() : [];
    
    try {
        $enUrl = route($currentRoute, array_merge($params, ['locale' => 'en']));
        $bnUrl = route($currentRoute, array_merge($params, ['locale' => 'bn']));
    } catch (\Throwable $e) {
        $enUrl = url('/en');
        $bnUrl = url('/bn');
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">

    <!-- Canonical & Multilingual SEO -->
    <link rel="canonical" href="{{ $currentUrl }}">
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}">
    <link rel="alternate" hreflang="bn" href="{{ $bnUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $enUrl }}">

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ config('app.name', 'OmniTools') }}">
    <meta property="og:locale" content="{{ $locale === 'bn' ? 'bn_BD' : 'en_US' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50/70 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">
    <!-- Skip to Content for Accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-indigo-600 focus:text-white focus:rounded-lg focus:shadow-lg focus:outline-none">
        {{ __('common.nav.skip_to_content') }}
    </a>

    <!-- Header Navigation -->
    <x-header />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Global Ad Slot (Discreet Footer Banner Placement - Phase 1 disabled) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ad-slot slot="footer-banner" />
    </div>

    <!-- Footer -->
    <x-footer />

    <!-- Global Search Modal -->
    <x-search-modal />

    @stack('scripts')
</body>
</html>
