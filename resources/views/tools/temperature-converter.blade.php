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
        valC: 25,
        valF: 77,
        valK: 298.15,
        convertFrom(source) {
            if (source === 'c') {
                let c = parseFloat(this.valC);
                if (isNaN(c)) { this.valF = ''; this.valK = ''; return; }
                this.valF = ((c * 9/5) + 32).toFixed(2);
                this.valK = (c + 273.15).toFixed(2);
            } else if (source === 'f') {
                let f = parseFloat(this.valF);
                if (isNaN(f)) { this.valC = ''; this.valK = ''; return; }
                let c = (f - 32) * 5/9;
                this.valC = c.toFixed(2);
                this.valK = (c + 273.15).toFixed(2);
            } else if (source === 'k') {
                let k = parseFloat(this.valK);
                if (isNaN(k)) { this.valC = ''; this.valF = ''; return; }
                let c = k - 273.15;
                this.valC = c.toFixed(2);
                this.valF = ((c * 9/5) + 32).toFixed(2);
            }
        }
    }" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Celsius (°C)</label>
                <input type="number" step="any" x-model="valC" @input="convertFrom('c')" class="w-full px-4 py-3 text-xl font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-xs text-slate-400 mt-2 block font-mono">0 °C = Freezing Point of Water</span>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Fahrenheit (°F)</label>
                <input type="number" step="any" x-model="valF" @input="convertFrom('f')" class="w-full px-4 py-3 text-xl font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-xs text-slate-400 mt-2 block font-mono">32 °F = Freezing Point of Water</span>
            </div>
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kelvin (K)</label>
                <input type="number" step="any" x-model="valK" @input="convertFrom('k')" class="w-full px-4 py-3 text-xl font-mono rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <span class="text-xs text-slate-400 mt-2 block font-mono">0 K = Absolute Zero</span>
            </div>
        </div>

        <div class="mt-8 p-4 rounded-2xl bg-cyan-50 dark:bg-cyan-950/30 border border-cyan-100 dark:border-cyan-900/40 text-xs text-cyan-800 dark:text-cyan-300 space-y-1">
            <div class="font-bold">Conversion Formulas:</div>
            <div>• °F = (°C × 9/5) + 32</div>
            <div>• °C = (°F - 32) × 5/9</div>
            <div>• K = °C + 273.15</div>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
