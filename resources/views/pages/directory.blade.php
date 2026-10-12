<x-layouts.app
    :title="__('common.nav.tools')"
    :description="__('common.meta_description_default')"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            __('common.nav.tools') => null,
        ]" />

        <!-- Header -->
        <div class="max-w-3xl mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ __('common.nav.tools') }}
            </h1>
            <p class="mt-2 text-base text-slate-600">
                {{ $locale === 'bn' 
                    ? 'আমাদের সকল কার্যকরী ব্রাউজার-ভিত্তিক টুলসের সম্পূর্ণ ডিরেক্টরি। কোনো রেজিস্ট্রেশন ছাড়াই ব্যবহার করুন।' 
                    : 'Browse our complete catalog of browser-based utilities for text, calculations, developer formatting, and media.' }}
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="mb-8 p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
            <form method="GET" action="{{ route('tools.index', ['locale' => $locale]) }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <svg class="pointer-events-none absolute left-3.5 top-3.5 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ $searchQuery }}"
                        placeholder="{{ __('common.nav.search_placeholder') }}"
                        class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                    >
                </div>

                <input type="hidden" name="category" value="{{ $currentCategory }}">

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('common.actions.search') }}
                </button>

                @if($searchQuery || $currentCategory !== 'all')
                    <a
                        href="{{ route('tools.index', ['locale' => $locale]) }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors text-center"
                    >
                        {{ __('common.actions.clear') }}
                    </a>
                @endif
            </form>

            <!-- Category Pills Filter -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <a
                    href="{{ route('tools.index', ['locale' => $locale, 'q' => $searchQuery, 'category' => 'all']) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $currentCategory === 'all' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                >
                    <span>{{ __('common.categories.all') }}</span>
                    @if(isset($allTools))
                        <span class="inline-flex items-center justify-center min-w-4.5 h-4.5 px-1.5 rounded-full text-[10px] font-bold {{ $currentCategory === 'all' ? 'bg-indigo-700 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }}">
                            {{ $allTools->count() }}
                        </span>
                    @endif
                </a>

                @foreach($categories as $catKey => $catData)
                    @php
                        $catCount = isset($allTools) ? $allTools->where('category', $catKey)->count() : null;
                    @endphp
                    <a
                        href="{{ route('tools.index', ['locale' => $locale, 'q' => $searchQuery, 'category' => $catKey]) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $currentCategory === $catKey ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}"
                    >
                        <x-icon :name="$catData['icon']" class="w-3.5 h-3.5 {{ $currentCategory === $catKey ? 'text-white' : 'text-slate-400' }}" />
                        <span>{{ __('common.categories.' . $catKey) }}</span>
                        @if($catCount !== null)
                            <span class="inline-flex items-center justify-center min-w-4.5 h-4.5 px-1.5 rounded-full text-[10px] font-bold {{ $currentCategory === $catKey ? 'bg-indigo-700 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400' }}">
                                {{ $catCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Tool Cards Grid -->
        @if($tools->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tools as $tool)
                    <x-tool-card :tool="$tool" />
                @endforeach
            </div>
        @else
            <div class="p-12 text-center rounded-2xl bg-white border border-slate-200">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">
                    {{ __('common.search.no_results') }}
                </h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">
                    {{ $locale === 'bn' ? 'দয়া করে অন্য কোনো শব্দ দিয়ে অনুসন্ধান করুন অথবা সকল ক্যাটেগরি নির্বাচন করুন।' : 'Try adjusting your search terms or filter by a different category.' }}
                </p>
                <a
                    href="{{ route('tools.index', ['locale' => $locale]) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition-colors"
                >
                    {{ __('common.actions.view_all_tools') }}
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
