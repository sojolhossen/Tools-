<x-layouts.app
    :title="__('common.tagline')"
    :description="__('common.meta_description_default')"
>
    <!-- Compact Hero Section -->
    <section class="relative overflow-hidden bg-white border-b border-slate-200/80 py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                <span>{{ __('common.badges.privacy_first') }}</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight max-w-3xl mx-auto leading-tight">
                {{ __('common.tagline') }}
            </h1>

            <!-- Value Proposition -->
            <p class="mt-4 text-base sm:text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('common.meta_description_default') }}
            </p>

            <!-- Prominent Search Trigger / Input -->
            <div class="mt-8 max-w-xl mx-auto">
                <button
                    type="button"
                    @click="$dispatch('open-search')"
                    class="w-full flex items-center justify-between px-4 sm:px-5 py-3.5 text-left text-sm text-slate-500 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 shadow-xs hover:border-indigo-300 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <span>{{ __('common.nav.search_placeholder') }}</span>
                    </span>
                    <span class="hidden sm:inline-flex px-2 py-0.5 text-xs font-mono font-medium bg-white rounded-md border border-slate-200 text-slate-400">Ctrl K</span>
                </button>
            </div>

            <!-- Quick Category Pills -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                @foreach($categories as $catKey => $catData)
                    <a
                        href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200/80 text-xs font-semibold text-slate-700 hover:border-indigo-400 hover:text-indigo-600 shadow-2xs hover:shadow-xs transition-all"
                    >
                        <x-icon :name="$catData['icon']" class="w-3.5 h-3.5 text-indigo-500" />
                        <span>{{ __('common.categories.' . $catKey) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Tools Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    {{ __('common.badges.featured') }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    {{ $locale === 'bn' ? 'বিশেষ নির্বাচিত টুলসমূহ' : 'Featured Utilities' }}
                </h2>
            </div>
            <a
                href="{{ route('tools.index', ['locale' => $locale]) }}"
                class="inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 hover:text-indigo-700"
            >
                <span>{{ __('common.actions.view_all_tools') }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
    <section class="bg-slate-100/60 border-y border-slate-200/80 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    {{ __('common.nav.categories') }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    {{ $locale === 'bn' ? 'ক্যাটেগরি অনুযায়ী খুঁজুন' : 'Browse by Category' }}
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    {{ $locale === 'bn' ? 'আপনার প্রয়োজনীয় টুল সহজে খুঁজে পেতে ক্যাটেগরি নির্বাচন করুন।' : 'Find the right utility for text analysis, calculation, images, or development.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $catKey => $catData)
                    @php
                        $catTools = $allTools->filter(fn($t) => $t->category === $catKey);
                    @endphp
                    <a
                        href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}"
                        class="group block p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-indigo-300 transition-all"
                    >
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <x-icon :name="$catData['icon']" class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                    {{ __('common.categories.' . $catKey) }}
                                </h3>
                                <span class="text-xs text-slate-400">
                                    {{ $catTools->count() }} {{ $locale === 'bn' ? 'টি টুল' : 'tools' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ __('common.category_descriptions.' . $catKey) }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Tools Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="mb-8">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">
                {{ __('common.badges.popular') }}
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                {{ $locale === 'bn' ? 'সবচেয়ে বেশি ব্যবহৃত টুলস' : 'Frequently Used Utilities' }}
            </h2>
            <p class="mt-1 text-sm text-slate-600">
                {{ $locale === 'bn' ? 'প্রতিদিনের কাজের জন্য সবচেয়ে প্রয়োজনীয় ও কার্যকর টুলসমূহ।' : 'Reliable, instant tools optimized for everyday productivity.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($popularTools as $tool)
                <x-tool-card :tool="$tool" />
            @endforeach
        </div>
    </section>

    <!-- How the Platform Works (Transparent Value & Educational Architecture) -->
    <section class="bg-white border-t border-slate-200/80 py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $locale === 'bn' ? 'প্ল্যাটফর্মটি কীভাবে কাজ করে?' : 'How Our Platform Works' }}
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    {{ $locale === 'bn' ? 'কোনো সফটওয়্যার ইনস্টল বা অ্যাকাউন্ট খোলা ছাড়াই সরাসরি আপনার ব্রাউজারে কাজ করুন।' : 'Clean, browser-based architecture engineered for absolute privacy and zero friction.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-4">
                        1
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">
                        {{ $locale === 'bn' ? '১. কোনো অ্যাকাউন্টের ঝামেলা নেই' : '1. Zero Registration' }}
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $locale === 'bn' ? 'কোনো সাইন-আপ, পাসওয়ার্ড বা ব্যক্তিগত ইমেইল দেওয়ার প্রয়োজন নেই। সরাসরি টুলটি ওপেন করুন।' : 'No signups, credit cards, or passwords. Click on any tool and start working immediately without delays.' }}
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-4">
                        2
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">
                        {{ $locale === 'bn' ? '২. ১০০% ব্রাউজার প্রসেসিং' : '2. 100% In-Browser' }}
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $locale === 'bn' ? 'আপনার ফাইল ও লেখার হিসাব আধুনিক জাভাস্ক্রিপ্ট প্রযুক্তিতে ডিভাইসের মেমোরিতেই সম্পন্ন হয়।' : 'Calculations and image modifications run strictly locally in your browser memory using modern web APIs.' }}
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-4">
                        3
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-2">
                        {{ $locale === 'bn' ? '৩. ফাইল সুরক্ষার নিশ্চয়তা' : '3. Complete Privacy' }}
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        {{ $locale === 'bn' ? 'আপনার টেক্সট, ডকুমেন্টস বা ছবি কখনোই কোনো রিমোট সার্ভারে জমা হয় না। আপনার ডেটা আপনারই থাকে।' : 'Because files are not uploaded to a backend server, your confidential data remains completely safe on your device.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
