@php
    $locale = app()->getLocale();
    $targetLocale = $locale === 'en' ? 'bn' : 'en';
    $targetLanguageLabel = $locale === 'en' ? 'বাংলা' : 'English';

    // Build URL for current route in the target locale
    $currentRouteName = Route::currentRouteName() ?? 'home';
    $currentParameters = Route::current() ? Route::current()->parameters() : [];
    $targetParameters = array_merge($currentParameters, ['locale' => $targetLocale]);
    try {
        $switchUrl = route($currentRouteName, $targetParameters);
    } catch (\Throwable $e) {
        $switchUrl = url('/' . $targetLocale);
    }

    $toolRegistry = app(\App\Support\Tools\ToolRegistry::class);
    $categories = $toolRegistry->getCategories();
@endphp

<header x-data="{ mobileMenuOpen: false, catDropdownOpen: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 transition-shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Brand Logo -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home', ['locale' => $locale]) }}" class="flex items-center gap-2.5 group focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 rounded-lg p-1">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white shadow-sm shadow-indigo-200 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-tight text-slate-900 group-hover:text-indigo-600 transition-colors">
                            {{ config('app.name', 'OmniTools') }}
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1" aria-label="Main Navigation">
                    <a href="{{ route('home', ['locale' => $locale]) }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:text-indigo-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('home') ? 'text-indigo-600 bg-indigo-50/70' : '' }}">
                        {{ __('common.nav.home') }}
                    </a>
                    
                    <a href="{{ route('tools.index', ['locale' => $locale]) }}" class="px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:text-indigo-600 hover:bg-slate-50 transition-colors {{ request()->routeIs('tools.index') ? 'text-indigo-600 bg-indigo-50/70' : '' }}">
                        {{ __('common.nav.tools') }}
                    </a>

                    <!-- Categories Dropdown -->
                    <div class="relative" @click.away="catDropdownOpen = false">
                        <button
                            type="button"
                            @click="catDropdownOpen = !catDropdownOpen"
                            :aria-expanded="catDropdownOpen"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold rounded-lg text-slate-700 hover:text-indigo-600 hover:bg-slate-50 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600"
                        >
                            <span>{{ __('common.nav.categories') }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform" :class="catDropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div
                            x-show="catDropdownOpen"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-56 rounded-2xl bg-white p-2 shadow-xl ring-1 ring-slate-900/10 z-50 focus:outline-none"
                            x-cloak
                        >
                            @foreach($categories as $catKey => $catData)
                                <a
                                    href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                                    class="flex items-center gap-2.5 px-3 py-2 text-sm font-medium rounded-xl text-slate-700 hover:text-indigo-600 hover:bg-indigo-50/80 transition-colors"
                                    @click="catDropdownOpen = false"
                                >
                                    <x-icon :name="$catData['icon']" class="w-4 h-4 text-slate-400" />
                                    <span>{{ __('common.categories.' . $catKey) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Header Right Controls -->
            <div class="flex items-center gap-3">
                <!-- Search Button (Cmd+K) -->
                <button
                    type="button"
                    @click="$dispatch('open-search')"
                    class="hidden sm:inline-flex items-center gap-3 px-3 py-1.5 text-xs font-medium text-slate-500 bg-slate-100/80 hover:bg-slate-100 hover:text-slate-800 rounded-xl border border-slate-200/60 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600"
                    aria-label="{{ __('common.nav.search_shortcut') }}"
                >
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span>{{ __('common.actions.search') }}...</span>
                    <kbd class="px-1.5 py-0.5 text-[10px] font-mono font-semibold bg-white rounded border border-slate-200 text-slate-400 shadow-2xs">Ctrl K</kbd>
                </button>

                <!-- Search Icon for Mobile -->
                <button
                    type="button"
                    @click="$dispatch('open-search')"
                    class="sm:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100"
                    aria-label="{{ __('common.actions.search') }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>

                <!-- Language Switcher -->
                <a
                    href="{{ $switchUrl }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600"
                    title="{{ $locale === 'en' ? 'বাংলা ভাষায় পরিবর্তন করুন' : 'Switch to English' }}"
                    aria-label="{{ __('common.nav.language') }}: {{ $targetLanguageLabel }}"
                >
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3" />
                    </svg>
                    <span>{{ $targetLanguageLabel }}</span>
                </a>

                <!-- Mobile Hamburger Button -->
                <button
                    type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen"
                    class="md:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600"
                    aria-label="{{ __('common.nav.open_menu') }}"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3"
        x-cloak
    >
        <a href="{{ route('home', ['locale' => $locale]) }}" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-800 hover:bg-slate-50 {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : '' }}">
            {{ __('common.nav.home') }}
        </a>

        <a href="{{ route('tools.index', ['locale' => $locale]) }}" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-800 hover:bg-slate-50 {{ request()->routeIs('tools.index') ? 'bg-indigo-50 text-indigo-600' : '' }}">
            {{ __('common.nav.tools') }}
        </a>

        <div class="pt-2 border-t border-slate-100">
            <span class="block px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                {{ __('common.nav.categories') }}
            </span>
            <div class="grid grid-cols-2 gap-1">
                @foreach($categories as $catKey => $catData)
                    <a
                        href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                        class="flex items-center gap-2 px-3 py-2 text-xs font-medium rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-indigo-50/50"
                    >
                        <x-icon :name="$catData['icon']" class="w-3.5 h-3.5 text-slate-400" />
                        <span>{{ __('common.categories.' . $catKey) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</header>
