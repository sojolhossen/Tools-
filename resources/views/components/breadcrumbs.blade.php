@props([
    'items' => [],
])

@php
    $locale = app()->getLocale();
@endphp

<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex flex-wrap items-center gap-2 text-xs md:text-sm text-slate-500">
        <li class="inline-flex items-center">
            <a href="{{ route('home', ['locale' => $locale]) }}" class="inline-flex items-center gap-1 font-medium hover:text-indigo-600 focus-visible:ring-2 focus-visible:ring-indigo-600 rounded transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>{{ __('common.breadcrumbs.home') }}</span>
            </a>
        </li>

        @foreach($items as $label => $url)
            <li class="inline-flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
                @if($url && !$loop->last)
                    <a href="{{ $url }}" class="font-medium hover:text-indigo-600 focus-visible:ring-2 focus-visible:ring-indigo-600 rounded transition-colors">
                        {{ $label }}
                    </a>
                @else
                    <span class="font-semibold text-slate-900" aria-current="page">
                        {{ $label }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
