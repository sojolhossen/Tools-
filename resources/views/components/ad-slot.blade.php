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

@if($globalAdsEnabled)
    <!-- Ad Container: Clean, Policy-Compliant Labeling -->
    <div class="my-8 sm:my-10 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/50 text-center relative overflow-hidden transition-all {{ $class }}" role="region" aria-label="Advertisement">
        <div class="flex items-center justify-center gap-2 mb-3">
            <span class="h-px w-8 bg-slate-200 dark:bg-slate-800"></span>
            <span class="text-[10px] font-bold tracking-widest uppercase text-slate-400 dark:text-slate-500 select-none">
                {{ __('common.ads.label') }}
            </span>
            <span class="h-px w-8 bg-slate-200 dark:bg-slate-800"></span>
        </div>
        <div class="min-h-[100px] flex items-center justify-center overflow-hidden">
            @if($placement && !empty($placement->slot_id) && $placement->provider === 'adsense')
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="{{ SiteSetting::get('adsense_publisher_id', '') }}"
                     data-ad-slot="{{ $placement->slot_id }}"
                     data-ad-format="{{ $placement->format }}"></ins>
            @else
                <!-- Adsterra Native Banner (4:1 Widget) -->
                <script async="async" data-cfasync="false" src="https://pl31920286.profitableratecpmnetwork.com/8978f9d4538ebe4052f2e79a6e98249b/invoke.js"></script>
                <div id="container-8978f9d4538ebe4052f2e79a6e98249b"></div>
            @endif
        </div>
    </div>
@endif
