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
        valL100: 8,
        valMpgUS: 29.4,
        valMpgUK: 35.3,
        valKmL: 12.5,
        convertFrom(src) {
            if (src === 'l100') {
                let l = parseFloat(this.valL100);
                if (l > 0) {
                    this.valKmL = (100 / l).toFixed(2);
                    this.valMpgUS = (235.214583 / l).toFixed(2);
                    this.valMpgUK = (282.481 / l).toFixed(2);
                }
            } else if (src === 'kml') {
                let k = parseFloat(this.valKmL);
                if (k > 0) {
                    this.valL100 = (100 / k).toFixed(2);
                    this.valMpgUS = (k * 2.35214583).toFixed(2);
                    this.valMpgUK = (k * 2.82481).toFixed(2);
                }
            } else if (src === 'mpgus') {
                let m = parseFloat(this.valMpgUS);
                if (m > 0) {
                    this.valL100 = (235.214583 / m).toFixed(2);
                    this.valKmL = (m / 2.35214583).toFixed(2);
                    this.valMpgUK = (m * 1.20095).toFixed(2);
                }
            }
        }
    }" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Liters / 100 km</label>
                <input type="number" step="0.1" x-model="valL100" @input="convertFrom('l100')" class="w-full text-center px-3 py-2.5 text-xl font-bold font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-[10px] text-slate-400 mt-1 block text-center">European Standard</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kilometers / Liter</label>
                <input type="number" step="0.1" x-model="valKmL" @input="convertFrom('kml')" class="w-full text-center px-3 py-2.5 text-xl font-bold font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-[10px] text-slate-400 mt-1 block text-center">Asian / Japanese Standard</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Miles per Gallon (US)</label>
                <input type="number" step="0.1" x-model="valMpgUS" @input="convertFrom('mpgus')" class="w-full text-center px-3 py-2.5 text-xl font-bold font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-[10px] text-slate-400 mt-1 block text-center">US Standard</span>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Miles per Gallon (UK)</label>
                <input type="number" step="0.1" x-model="valMpgUK" readonly class="w-full text-center px-3 py-2.5 text-xl font-bold font-mono rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500">
                <span class="text-[10px] text-slate-400 mt-1 block text-center">Imperial Gallon</span>
            </div>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
