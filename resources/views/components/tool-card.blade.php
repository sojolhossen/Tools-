@props([
    'tool',
])

@php
    $locale = app()->getLocale();
@endphp

<article class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm hover:shadow-md hover:border-indigo-200 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 transition-all duration-200">
    <div>
        <div class="flex items-center justify-between gap-3 mb-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <x-icon :name="$tool->icon" class="w-6 h-6" />
            </div>

            <div class="flex items-center gap-1.5 flex-wrap justify-end">
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600 capitalize">
                    {{ __('common.categories.' . $tool->category) }}
                </span>
                @if($tool->isPopular)
                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-semibold text-amber-700">
                        {{ __('common.badges.popular') }}
                    </span>
                @endif
            </div>
        </div>

        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
            <a href="{{ route('tools.show', ['locale' => $locale, 'slug' => $tool->slug]) }}" class="focus:outline-none">
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $tool->getName() }}
            </a>
        </h3>

        <p class="mt-2 text-sm text-slate-600 line-clamp-2 leading-relaxed">
            {{ $tool->getDescription() }}
        </p>
    </div>

    <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-indigo-600 group-hover:text-indigo-700">
        <span class="inline-flex items-center gap-1 text-slate-500 font-normal">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            {{ __('common.badges.browser_based') }}
        </span>
        <span class="inline-flex items-center gap-1 font-semibold">
            {{ __('common.actions.try_tool') }}
            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </span>
    </div>
</article>
