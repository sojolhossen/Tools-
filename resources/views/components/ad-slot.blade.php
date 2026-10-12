@props([
    'slot' => 'standard',
    'class' => '',
])

@php
    // In Phase 1, advertising integration is inactive and can be enabled via config('services.adsense.enabled')
    $adsEnabled = config('services.adsense.enabled', false);
@endphp

@if($adsEnabled)
    <div class="my-6 p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 text-center {{ $class }}">
        <span class="inline-block text-[10px] font-semibold tracking-wider uppercase text-slate-400 dark:text-slate-500 mb-2">
            {{ __('common.ads.label') }}
        </span>
        <div class="min-h-[90px] flex items-center justify-center text-xs text-slate-400">
            <!-- Ad code container: Injected here in Phase 2 when AdSense account is approved -->
        </div>
    </div>
@endif
