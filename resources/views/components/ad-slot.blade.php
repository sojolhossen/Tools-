@props([
    'slot' => 'standard',
    'class' => '',
])

@php
    use App\Models\AdPlacement;
    use App\Models\SiteSetting;
    use Illuminate\Support\Facades\Schema;

    $globalAdsEnabled = false;
    $placement = null;

    try {
        $globalAdsEnabled = (bool) SiteSetting::get('adsense_enabled', false);
        if ($globalAdsEnabled && Schema::hasTable('ad_placements')) {
            $placement = AdPlacement::where('identifier', $slot)->where('is_active', true)->first();
        }
    } catch (\Throwable $e) {
        $globalAdsEnabled = false;
        $placement = null;
    }
@endphp

@if($globalAdsEnabled && $placement)
    <div class="my-6 p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 text-center {{ $class }}">
        <span class="inline-block text-[10px] font-semibold tracking-wider uppercase text-slate-400 dark:text-slate-500 mb-2">
            {{ __('common.ads.label') }}
        </span>
        <div class="min-h-[90px] flex items-center justify-center text-xs text-slate-400 font-mono">
            @if(!empty($placement->slot_id))
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="{{ SiteSetting::get('adsense_publisher_id', '') }}"
                     data-ad-slot="{{ $placement->slot_id }}"
                     data-ad-format="{{ $placement->format }}"></ins>
            @else
                <span class="text-slate-400">Ad Placement: {{ $placement->name }}</span>
            @endif
        </div>
    </div>
@endif
