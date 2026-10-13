<x-layouts.app
    :title="__('common.tagline')"
    :description="__('common.meta_description_default')"
    :schema="$schema ?? null"
>
    <!-- Compact, Premium Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-indigo-50/70 via-white to-slate-50/70 dark:from-slate-900/80 dark:via-slate-950 dark:to-slate-950 border-b border-slate-200/80 dark:border-slate-800 py-8 sm:py-16 lg:py-20">
        <!-- Subtle Ambient Background Glow -->
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-80 sm:w-[500px] h-80 sm:h-[500px] bg-indigo-200/35 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Privacy Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-indigo-100 dark:border-indigo-900/50 shadow-2xs text-indigo-700 dark:text-indigo-400 text-xs font-semibold mb-4 sm:mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ __('common.badges.privacy_first') }}</span>
            </div>

            <!-- Main Heading with Modern Gradient Accent -->
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight max-w-3xl mx-auto leading-snug sm:leading-tight">
                {{ $locale === 'bn' ? 'ফ্রি, দ্রুত এবং নিরাপদ' : 'Free, Fast & Secure' }}
                <span class="bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-600 bg-clip-text text-transparent block sm:inline mt-1 sm:mt-0">
                    {{ $locale === 'bn' ? 'অনলাইন টুলস' : 'Online Tools' }}
                </span>
            </h1>

            <!-- Value Proposition -->
            <p class="mt-2.5 sm:mt-4 text-xs sm:text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                {{ __('common.meta_description_default') }}
            </p>

            @php
                $totalToolsCount = $allTools->count();
                $formattedCount = $locale === 'bn' 
                    ? str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], (string) $totalToolsCount) 
                    : (string) $totalToolsCount;
            @endphp

            <!-- Prominent App-Style Search Box -->
            <div x-data class="mt-5 sm:mt-8 max-w-xl mx-auto">
                <button
                    type="button"
                    @click="$dispatch('open-search')"
                    onclick="window.dispatchEvent(new CustomEvent('open-search'))"
                    class="w-full flex items-center justify-between p-2 sm:p-2.5 text-left bg-white/95 dark:bg-slate-900/95 backdrop-blur-sm hover:bg-white dark:hover:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-500/50 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600 group cursor-pointer"
                    aria-label="{{ __('common.nav.search_placeholder', ['count' => $formattedCount]) }}"
                >
                    <span class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <span class="text-xs sm:text-sm text-slate-400 dark:text-slate-500 font-normal truncate">
                            {{ __('common.nav.search_placeholder', ['count' => $formattedCount]) }}
                        </span>
                    </span>
                    <span class="hidden sm:inline-flex px-2 py-0.5 text-xs font-mono font-medium bg-slate-50 dark:bg-slate-800 rounded-md border border-slate-200 dark:border-slate-700 text-slate-400">Ctrl K</span>
                    <span class="sm:hidden px-2.5 py-1 text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 rounded-lg shrink-0">
                        {{ __('common.actions.search') }}
                    </span>
                </button>
            </div>

            <!-- Category Chips: Balanced 2-Column Grid on Mobile, Wrapped Flex on Desktop -->
            <div class="mt-5 sm:mt-8">
                <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:justify-center sm:gap-2.5 max-w-4xl mx-auto py-1">
                    @php
                        $mobileLimit = 9;
                        $totalCategories = count($categories);
                        $remainingCount = max(0, $totalCategories - $mobileLimit);
                        $catIndex = 0;
                    @endphp
                    @foreach($categories as $catKey => $catData)
                        @php
                            $catCount = $allTools->where('category', $catKey)->count();
                            $isMobileHidden = $catIndex >= $mobileLimit;
                            $catIndex++;
                        @endphp
                        <a
                            href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                            class="group {{ $isMobileHidden ? 'hidden sm:inline-flex' : 'flex' }} sm:inline-flex items-center justify-between sm:justify-start gap-1.5 sm:gap-2 px-2.5 py-2 sm:px-3.5 sm:py-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-indigo-400 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-2xs hover:shadow-xs active:scale-95 transition-all min-w-0"
                        >
                            <div class="flex items-center gap-1.5 min-w-0 flex-1 overflow-hidden" title="{{ __('common.categories.' . $catKey) }}">
                                <x-icon :name="$catData['icon']" class="w-3.5 h-3.5 text-indigo-500 shrink-0" />
                                <span class="truncate text-left font-semibold">{{ __('common.categories.' . $catKey) }}</span>
                            </div>
                            <span class="inline-flex items-center justify-center min-w-4.5 h-4.5 px-1.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-950/80 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors shrink-0">
                                {{ $catCount }}
                            </span>
                        </a>
                    @endforeach

                    @if($remainingCount > 0)
                        <a
                            href="{{ route('tools.index', ['locale' => $locale]) }}"
                            class="sm:hidden group flex items-center justify-center gap-1.5 px-2.5 py-2 rounded-xl bg-gradient-to-r from-indigo-500/15 via-indigo-600/20 to-violet-600/15 dark:from-indigo-950 dark:to-violet-950 border border-indigo-300 dark:border-indigo-700 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 shadow-2xs active:scale-95 transition-all"
                            title="{{ $locale === 'bn' ? 'সকল ক্যাটেগরি ও টুলস দেখুন' : 'View all categories and tools' }}"
                        >
                            <span>+{{ $remainingCount }} {{ $locale === 'bn' ? 'আরো' : 'More' }}</span>
                            <svg class="w-3.5 h-3.5 text-indigo-500 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Micro Trust & Platform Highlights Grid -->
            <div class="mt-6 sm:mt-10 grid grid-cols-3 gap-2 sm:gap-4 max-w-md mx-auto pt-5 sm:pt-6 border-t border-slate-200/60 dark:border-slate-800 text-center">
                <div class="p-2 sm:p-3 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/60 dark:border-slate-800 shadow-2xs">
                    <div class="text-base sm:text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $formattedCount }}+</div>
                    <div class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400">{{ $locale === 'bn' ? 'ফ্রি টুলস' : 'Free Tools' }}</div>
                </div>
                <div class="p-2 sm:p-3 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/60 dark:border-slate-800 shadow-2xs">
                    <div class="text-base sm:text-xl font-black text-emerald-600 dark:text-emerald-400">100%</div>
                    <div class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400">{{ $locale === 'bn' ? 'ব্রাউজার সেফ' : 'In-Browser' }}</div>
                </div>
                <div class="p-2 sm:p-3 rounded-xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/60 dark:border-slate-800 shadow-2xs">
                    <div class="text-base sm:text-xl font-black text-violet-600 dark:text-violet-400">0$</div>
                    <div class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400">{{ $locale === 'bn' ? 'চিরকাল ফ্রি' : 'Free Forever' }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tools Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="flex items-center justify-between gap-2 mb-4 sm:mb-8">
            <div>
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    {{ __('common.badges.featured') }}
                </span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5 sm:mt-1">
                    {{ $locale === 'bn' ? 'নির্বাচিত টুলসমূহ' : 'Featured Utilities' }}
                </h2>
            </div>
            <a
                href="{{ route('tools.index', ['locale' => $locale]) }}"
                class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 px-2.5 py-1.5 rounded-lg bg-indigo-50/70 dark:bg-indigo-950/40 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 sm:bg-transparent dark:sm:bg-transparent shrink-0 transition-colors"
            >
                <span>{{ __('common.actions.view_all_tools') }}</span>
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6">
            @foreach($featuredTools as $tool)
                <x-tool-card :tool="$tool" />
            @endforeach
        </div>
    </section>

    <!-- Optional Ad Slot (Hero-to-Content transition - Phase 1 disabled) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-ad-slot slot="homepage-mid" />
    </div>

    <!-- Categories Grid Section -->
    <section class="bg-slate-100/60 dark:bg-slate-900/40 border-y border-slate-200/80 dark:border-slate-800 py-8 sm:py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-6 sm:mb-10">
                <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    {{ __('common.nav.categories') }}
                </span>
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5 sm:mt-1">
                    {{ $locale === 'bn' ? 'ক্যাটেগরি অনুযায়ী খুঁজুন' : 'Browse by Category' }}
                </h2>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    {{ $locale === 'bn' ? 'আপনার প্রয়োজনীয় টুল সহজে খুঁজে পেতে ক্যাটেগরি নির্বাচন করুন।' : 'Find the right utility for text analysis, calculation, images, or development.' }}
                </p>
            </div>

            <!-- Mobile App-Style 2-Column Grid / Desktop 3-Column Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-6">
                @foreach($categories as $catKey => $catData)
                    @php
                        $catTools = $allTools->filter(fn($t) => $t->category === $catKey);
                    @endphp
                    <a
                        href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                        class="group block p-3.5 sm:p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-500/50 active:scale-[0.98] transition-all"
                    >
                        <div class="flex items-center gap-2.5 sm:gap-3 mb-1.5 sm:mb-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0">
                                <x-icon :name="$catData['icon']" class="w-4 h-4 sm:w-5 sm:h-5" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-xs sm:text-base font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">
                                    {{ __('common.categories.' . $catKey) }}
                                </h3>
                                <span class="text-[10px] sm:text-xs text-slate-400 dark:text-slate-500 block">
                                    {{ $catTools->count() }} {{ $locale === 'bn' ? 'টি টুল' : 'tools' }}
                                </span>
                            </div>
                        </div>
                        <p class="hidden sm:block text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('common.category_descriptions.' . $catKey) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Tools Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
        <div class="mb-5 sm:mb-8">
            <span class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                {{ __('common.badges.popular') }}
            </span>
            <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-0.5 sm:mt-1">
                {{ $locale === 'bn' ? 'সবচেয়ে বেশি ব্যবহৃত টুলস' : 'Frequently Used Utilities' }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                {{ $locale === 'bn' ? 'প্রতিদিনের কাজের জন্য সবচেয়ে প্রয়োজনীয় ও কার্যকর টুলসমূহ।' : 'Reliable, instant tools optimized for everyday productivity.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-6">
            @foreach($popularTools as $tool)
                <x-tool-card :tool="$tool" />
            @endforeach
        </div>
    </section>

    <!-- How the Platform Works (Transparent Value & Educational Architecture) -->
    <section class="bg-white dark:bg-slate-900/40 border-t border-slate-200/80 dark:border-slate-800 py-8 sm:py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-6 sm:mb-12">
                <h2 class="text-xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    {{ $locale === 'bn' ? 'প্ল্যাটফর্মটি কীভাবে কাজ করে?' : 'How Our Platform Works' }}
                </h2>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    {{ $locale === 'bn' ? 'কোনো সফটওয়্যার ইনস্টল বা অ্যাকাউন্ট খোলা ছাড়াই সরাসরি আপনার ব্রাউজারে কাজ করুন।' : 'Clean, browser-based architecture engineered for absolute privacy and zero friction.' }}
                </p>
            </div>

            <!-- Mobile List Layout / Desktop 3-Column Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 sm:gap-8">
                <div class="p-4 sm:p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-start gap-3.5 sm:block shadow-2xs">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm sm:text-lg shrink-0 sm:mb-4 shadow-sm shadow-indigo-200">
                        1
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1 sm:mb-2">
                            {{ $locale === 'bn' ? '১. কোনো অ্যাকাউন্টের ঝামেলা নেই' : '1. Zero Registration' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $locale === 'bn' ? 'কোনো সাইন-আপ বা ব্যক্তিগত তথ্যের প্রয়োজন নেই। সরাসরি টুলটি ওপেন করুন।' : 'No signups, credit cards, or passwords. Click on any tool and start working immediately without delays.' }}
                        </p>
                    </div>
                </div>

                <div class="p-4 sm:p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-start gap-3.5 sm:block shadow-2xs">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm sm:text-lg shrink-0 sm:mb-4 shadow-sm shadow-indigo-200">
                        2
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1 sm:mb-2">
                            {{ $locale === 'bn' ? '২. ১০০% ব্রাউজার প্রসেসিং' : '2. 100% In-Browser' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $locale === 'bn' ? 'আপনার ফাইল ও লেখার হিসাব আধুনিক জাভাস্ক্রিপ্ট প্রযুক্তিতে ডিভাইসের মেমোরিতেই সম্পন্ন হয়।' : 'Calculations and image modifications run strictly locally in your browser memory using modern web APIs.' }}
                        </p>
                    </div>
                </div>

                <div class="p-4 sm:p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-start gap-3.5 sm:block shadow-2xs">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm sm:text-lg shrink-0 sm:mb-4 shadow-sm shadow-indigo-200">
                        3
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white mb-1 sm:mb-2">
                            {{ $locale === 'bn' ? '৩. ফাইল সুরক্ষার নিশ্চয়তা' : '3. Complete Privacy' }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ $locale === 'bn' ? 'আপনার টেক্সট বা ছবি কখনোই কোনো সার্ভারে জমা হয় না। আপনার ডেটা আপনার ডিভাইসেই নিরাপদ।' : 'Because files are not uploaded to a backend server, your confidential data remains completely safe on your device.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
