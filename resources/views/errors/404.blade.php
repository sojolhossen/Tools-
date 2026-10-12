<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — {{ __('Page Not Found') }} | {{ config('app.name', 'ToolZeno') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col justify-between bg-slate-50 text-slate-800 antialiased">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 text-center flex-grow flex flex-col items-center justify-center">
        <p class="text-sm font-bold uppercase tracking-wider text-indigo-600">404 Error</p>
        <h1 class="mt-2 text-3xl sm:text-5xl font-black text-slate-900 tracking-tight">
            {{ app()->getLocale() === 'bn' ? 'পৃষ্ঠাটি খুঁজে পাওয়া যায়নি' : 'Page Not Found' }}
        </h1>
        <p class="mt-4 text-base text-slate-600 max-w-md mx-auto">
            {{ app()->getLocale() === 'bn' 
                ? 'দুঃখিত, আপনি যে পৃষ্ঠা বা টুলটি খুঁজছেন তা সরিয়ে ফেলা হয়েছে অথবা লিঙ্কটি ভুল।' 
                : 'Sorry, the tool or page you are looking for does not exist or may have been moved.' }}
        </p>
        
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a
                href="{{ route('home', ['locale' => app()->getLocale() ?: 'en']) }}"
                class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs sm:text-sm font-bold hover:bg-indigo-700 transition-colors shadow-2xs"
            >
                {{ __('common.nav.home') }}
            </a>
            <a
                href="{{ route('tools.index', ['locale' => app()->getLocale() ?: 'en']) }}"
                class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 transition-colors"
            >
                {{ __('common.nav.tools') }}
            </a>
        </div>
    </div>
</body>
</html>
