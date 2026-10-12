@props([
    'size' => 'md',
    'withText' => true,
    'class' => '',
])

@php
    $iconSizes = [
        'sm' => 'w-7 h-7 rounded-lg',
        'md' => 'w-9 h-9 rounded-xl',
        'lg' => 'w-11 h-11 rounded-2xl',
    ];
    $svgSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    $textSizes = [
        'sm' => 'text-lg',
        'md' => 'text-xl',
        'lg' => 'text-2xl',
    ];

    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
    $svgClass = $svgSizes[$size] ?? $svgSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="inline-flex items-center gap-2.5 {{ $class }}">
    <!-- ToolZeno Brand Icon -->
    <div class="{{ $iconClass }} relative bg-gradient-to-tr from-indigo-700 via-indigo-600 to-indigo-500 flex items-center justify-center text-white shadow-sm shadow-indigo-300/40 dark:shadow-indigo-950/60 ring-1 ring-white/20 shrink-0">
        <svg class="{{ $svgClass }}" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M37 10 L18 34 L30 34 L21 54 L46 28 L33 28 Z" fill="currentColor" stroke="currentColor" stroke-width="0.5"/>
            <circle cx="45" cy="17" r="3.5" fill="#38BDF8"/>
        </svg>
    </div>

    @if($withText)
        <span class="{{ $textClass }} font-extrabold tracking-tight flex items-baseline select-none">
            <span class="text-slate-900 dark:text-white">Tool</span><span class="bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400 bg-clip-text text-transparent">Zeno</span>
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-indigo-600 dark:bg-indigo-400 ml-0.5"></span>
        </span>
    @endif
</div>
