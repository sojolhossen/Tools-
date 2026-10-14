@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogType' => 'website',
    'schema' => null,
])

@php
    $locale = app()->getLocale();
    $appName = config('app.name', 'ToolZeno');
    
    // Title deduplication: avoid duplicate branding if title already contains ToolZeno
    if ($title) {
        if (str_contains($title, $appName) || str_contains($title, 'ToolZeno')) {
            $pageTitle = $title;
        } else {
            $pageTitle = $title . ' — ' . $appName;
        }
    } else {
        $pageTitle = $appName . ' — ' . __('common.tagline');
    }

    $pageDesc = $description ?? __('common.meta_description_default');
    
    // Canonical URL with production origin normalization
    $prodOrigin = rtrim(config('app.url', 'https://toolzeno.site'), '/');
    if (empty($canonical)) {
        $path = request()->path();
        $currentUrl = $prodOrigin . '/' . ltrim($path, '/');
    } else {
        $currentUrl = preg_replace('#^https?://[^/]+#', $prodOrigin, $canonical);
    }

    // Hreflang URLs
    $currentRoute = Route::currentRouteName() ?? 'home';
    $params = Route::current() ? Route::current()->parameters() : [];
    
    try {
        $enUrl = route($currentRoute, array_merge($params, ['locale' => 'en']));
        $bnUrl = route($currentRoute, array_merge($params, ['locale' => 'bn']));
        $enUrl = preg_replace('#^https?://[^/]+#', $prodOrigin, $enUrl);
        $bnUrl = preg_replace('#^https?://[^/]+#', $prodOrigin, $bnUrl);
    } catch (\Throwable $e) {
        $enUrl = $prodOrigin . '/en';
        $bnUrl = $prodOrigin . '/bn';
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" class="h-full scroll-smooth">
<head>
    @php
        $gaId = (string) (\App\Models\SiteSetting::get('analytics_measurement_id') ?: env('ANALYTICS_MEASUREMENT_ID', 'G-HJ4N0PWKP7'));
        $gaEnabled = \App\Models\SiteSetting::get('analytics_enabled');
        $gaActive = ($gaEnabled === null || $gaEnabled === '' || (bool) $gaEnabled);
    @endphp
    @if($gaActive && !empty($gaId))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif

    @php
        $adsensePub = (string) (\App\Models\SiteSetting::get('adsense_publisher_id') ?: env('ADSENSE_PUBLISHER_ID', 'ca-pub-8336443496687004'));
        $adsenseEnabled = \App\Models\SiteSetting::get('adsense_enabled');
        $adsenseActive = ($adsenseEnabled === null || $adsenseEnabled === '' || (bool) $adsenseEnabled);
    @endphp
    @if($adsenseActive && !empty($adsensePub))
    <!-- Google AdSense -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsensePub }}" crossorigin="anonymous"></script>
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    <!-- Dark Mode Initializer (Zero-FOUC) -->
    <script>
        (function() {
            try {
                const stored = localStorage.getItem('theme');
                if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (_) {}
        })();
    </script>

    <!-- Favicon & Brand Icons (Google Search & Multi-Device Compliant) -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#4F46E5">

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
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:locale" content="{{ $locale === 'bn' ? 'bn_BD' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ $locale === 'bn' ? 'en_US' : 'bn_BD' }}">
    <meta property="og:image" content="{{ asset('android-chrome-512x512.png') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">
    <meta name="twitter:url" content="{{ $currentUrl }}">
    <meta name="twitter:image" content="{{ asset('android-chrome-512x512.png') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', 'Noto Sans Bengali', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    </style>

    @if(isset($schema) && !empty($schema))
        <!-- Structured Data (JSON-LD) -->
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif
    @stack('schema')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen bg-slate-50/70 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased selection:bg-indigo-500 selection:text-white transition-colors duration-200">
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

    <!-- Alpine Category Marquee Ticker -->
    <script>
        (function () {
            function registerCategoryTicker() {
                if (!window.Alpine) {
                    document.addEventListener('alpine:init', registerCategoryTicker, { once: true });
                    return;
                }

                window.Alpine.data('categoryTicker', () => ({
                    timer: null,
                    isHovered: false,
                    ro: null,

                    init() {
                        this.$nextTick(() => {
                            this.checkAndScroll();
                        });

                        if (typeof ResizeObserver !== 'undefined' && this.$refs.ticker) {
                            this.ro = new ResizeObserver(() => {
                                this.checkAndScroll();
                            });
                            this.ro.observe(this.$refs.ticker);
                        }

                        window.addEventListener('resize', () => this.checkAndScroll());

                        if (document.fonts && document.fonts.ready) {
                            document.fonts.ready.then(() => this.checkAndScroll());
                        }
                    },

                    destroy() {
                        if (this.timer) clearTimeout(this.timer);
                        if (this.ro) this.ro.disconnect();
                    },

                    checkAndScroll() {
                        const el = this.$refs.ticker;
                        if (!el) return;
                        if (this.timer) clearTimeout(this.timer);

                        setTimeout(() => {
                            if (!this.$refs.ticker) return;
                            const max = el.scrollWidth - el.clientWidth;
                            if (max <= 2) {
                                el.scrollLeft = 0;
                                return;
                            }

                            let dir = 1;
                            const tick = () => {
                                if (!this.$refs.ticker) return;
                                if (this.isHovered) {
                                    this.timer = setTimeout(tick, 200);
                                    return;
                                }

                                if (dir === 1) {
                                    const prevLeft = el.scrollLeft;
                                    el.scrollLeft += 1;
                                    if (el.scrollLeft >= max || el.scrollLeft === prevLeft) {
                                        dir = -1;
                                        this.timer = setTimeout(tick, 1800);
                                    } else {
                                        this.timer = setTimeout(tick, 28);
                                    }
                                } else {
                                    const prevLeft = el.scrollLeft;
                                    el.scrollLeft -= 1;
                                    if (el.scrollLeft <= 0 || el.scrollLeft === prevLeft) {
                                        el.scrollLeft = 0;
                                        dir = 1;
                                        this.timer = setTimeout(tick, 2000);
                                    } else {
                                        this.timer = setTimeout(tick, 28);
                                    }
                                }
                            };

                            this.timer = setTimeout(tick, 1200);
                        }, 100);
                    }
                }));
            }

            if (window.Alpine) {
                registerCategoryTicker();
            } else {
                document.addEventListener('alpine:init', registerCategoryTicker, { once: true });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
