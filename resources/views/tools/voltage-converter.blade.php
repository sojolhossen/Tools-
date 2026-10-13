<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="unitConverter()">
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

        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8 mb-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- From Section -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">From</label>
                    <input 
                        type="number" 
                        step="any"
                        x-model="fromValue" 
                        @input="convert('from')"
                        class="w-full px-4 py-3 text-lg font-mono rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                    >
                    <select 
                        x-model="fromUnit" 
                        @change="convert('from')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-cyan-500"
                    >
                        <template x-for="(unit, key) in units" :key="key">
                            <option :value="key" x-text="unit.name + ' (' + unit.symbol + ')'"></option>
                        </template>
                    </select>
                </div>

                <!-- Swap Button on Desktop & Mobile -->
                <div class="hidden md:flex justify-center -my-2">
                    <button 
                        type="button" 
                        @click="swap()"
                        class="p-3 rounded-full bg-cyan-50 dark:bg-cyan-950/50 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-100 dark:hover:bg-cyan-900/50 hover:scale-110 active:scale-95 transition-all shadow-sm"
                        title="Swap Units"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    </button>
                </div>

                <!-- To Section -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">To</label>
                        <button 
                            type="button" 
                            @click="swap()" 
                            class="md:hidden inline-flex items-center gap-1 text-xs font-semibold text-cyan-600 dark:text-cyan-400"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            Swap
                        </button>
                    </div>
                    <input 
                        type="number" 
                        step="any"
                        x-model="toValue" 
                        @input="convert('to')"
                        class="w-full px-4 py-3 text-lg font-mono rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-cyan-500 focus:outline-none"
                    >
                    <select 
                        x-model="toUnit" 
                        @change="convert('from')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-cyan-500"
                    >
                        <template x-for="(unit, key) in units" :key="key">
                            <option :value="key" x-text="unit.name + ' (' + unit.symbol + ')'"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Instant Live Results Grid -->
            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-4">
                    All Equivalent Conversions (For <span class="text-cyan-600 dark:text-cyan-400" x-text="fromValue + ' ' + (units[fromUnit]?.symbol || '')"></span>)
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    <template x-for="(unit, key) in units" :key="key">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80">
                            <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400 truncate" x-text="unit.name"></div>
                            <div class="text-base font-bold font-mono text-slate-900 dark:text-white mt-0.5 truncate" :title="getEquiv(key)" x-text="getEquiv(key)"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>

<script>
function unitConverter() {
    return {
        units: {"uv": {"name": "Microvolt", "symbol": "\u00b5V", "ratio": 1e-06}, "mv": {"name": "Millivolt", "symbol": "mV", "ratio": 0.001}, "v": {"name": "Volt", "symbol": "V", "ratio": 1.0}, "kv": {"name": "Kilovolt", "symbol": "kV", "ratio": 1000.0}, "mv_big": {"name": "Megavolt", "symbol": "MV", "ratio": 1000000.0}},
        fromUnit: 'v',
        toUnit: 'mv',
        fromValue: 5,
        toValue: 0,
        init() {
            this.convert('from');
        },
        convert(source) {
            if (source === 'from') {
                let val = parseFloat(this.fromValue);
                if (isNaN(val)) { this.toValue = ''; return; }
                let base = val * this.units[this.fromUnit].ratio;
                let converted = base / this.units[this.toUnit].ratio;
                this.toValue = this.formatNum(converted);
            } else {
                let val = parseFloat(this.toValue);
                if (isNaN(val)) { this.fromValue = ''; return; }
                let base = val * this.units[this.toUnit].ratio;
                let converted = base / this.units[this.fromUnit].ratio;
                this.fromValue = this.formatNum(converted);
            }
        },
        swap() {
            let tempUnit = this.fromUnit;
            this.fromUnit = this.toUnit;
            this.toUnit = tempUnit;
            this.convert('from');
        },
        getEquiv(key) {
            let val = parseFloat(this.fromValue);
            if (isNaN(val)) return '--';
            let base = val * this.units[this.fromUnit].ratio;
            let converted = base / this.units[key].ratio;
            return this.formatNum(converted) + ' ' + this.units[key].symbol;
        },
        formatNum(num) {
            if (Math.abs(num) < 0.000001 && num !== 0) return num.toExponential(4);
            let str = num.toFixed(6);
            return parseFloat(str).toString();
        }
    };
}
</script>
