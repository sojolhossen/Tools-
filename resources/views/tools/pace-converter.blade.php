<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-50 dark:bg-cyan-950/60 text-cyan-700 dark:text-cyan-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed">
                {{ $tool->getDescription() }}
            </p>
        </div>

        
    <div x-data="{
        mode: 'km',
        paceMin: 5,
        paceSec: 30,
        speedKmh: 0,
        speedMph: 0,
        equivMin: 0,
        equivSec: 0,
        calculate() {
            let m = parseInt(this.paceMin) || 0;
            let s = parseInt(this.paceSec) || 0;
            let totalSec = (m * 60) + s;

            if (totalSec > 0) {
                if (this.mode === 'km') {
                    this.speedKmh = (3600 / totalSec).toFixed(2);
                    this.speedMph = (this.speedKmh / 1.60934).toFixed(2);
                    let secPerMile = totalSec * 1.60934;
                    this.equivMin = Math.floor(secPerMile / 60);
                    this.equivSec = Math.round(secPerMile % 60);
                } else {
                    this.speedMph = (3600 / totalSec).toFixed(2);
                    this.speedKmh = (this.speedMph * 1.60934).toFixed(2);
                    let secPerKm = totalSec / 1.60934;
                    this.equivMin = Math.floor(secPerKm / 60);
                    this.equivSec = Math.round(secPerKm % 60);
                }
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                <button type="button" @click="mode = 'km'; calculate()" :class="mode === 'km' ? 'bg-white dark:bg-slate-700 text-cyan-600 dark:text-cyan-400 shadow-xs' : 'text-slate-500'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Pace per Kilometer (min/km)</button>
                <button type="button" @click="mode = 'mile'; calculate()" :class="mode === 'mile' ? 'bg-white dark:bg-slate-700 text-cyan-600 dark:text-cyan-400 shadow-xs' : 'text-slate-500'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Pace per Mile (min/mi)</button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto mb-8">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Minutes</label>
                <input type="number" min="1" max="59" x-model="paceMin" @input="calculate()" class="w-full text-center px-4 py-3 text-2xl font-bold font-mono rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Seconds</label>
                <input type="number" min="0" max="59" x-model="paceSec" @input="calculate()" class="w-full text-center px-4 py-3 text-2xl font-bold font-mono rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-5 rounded-2xl bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-900">
                <span class="text-xs uppercase font-bold text-cyan-700 dark:text-cyan-400">Equivalent Pace</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-1" x-text="equivMin + ':' + (equivSec < 10 ? '0' : '') + equivSec + (mode === 'km' ? ' /mi' : ' /km')"></div>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs uppercase font-bold text-slate-500">Speed (km/h)</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-1" x-text="speedKmh + ' km/h'"></div>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs uppercase font-bold text-slate-500">Speed (mph)</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-1" x-text="speedMph + ' mph'"></div>
            </div>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
