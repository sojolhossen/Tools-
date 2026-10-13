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
        amount: 100,
        fromCurr: 'USD',
        toCurr: 'BDT',
        rates: {
            'USD': 1.0,
            'EUR': 0.92,
            'GBP': 0.78,
            'BDT': 122.50,
            'INR': 86.40,
            'SAR': 3.75,
            'AED': 3.67,
            'CAD': 1.38,
            'AUD': 1.54,
            'JPY': 152.20,
            'CNY': 7.24,
            'SGD': 1.34,
            'MYR': 4.42
        },
        result: 0,
        calculate() {
            let a = parseFloat(this.amount) || 0;
            let fromRate = this.rates[this.fromCurr] || 1.0;
            let toRate = this.rates[this.toCurr] || 1.0;
            // Convert to USD base first
            let inUsd = a / fromRate;
            let converted = inUsd * toRate;
            this.result = converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        swap() {
            let temp = this.fromCurr;
            this.fromCurr = this.toCurr;
            this.toCurr = temp;
            this.calculate();
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Amount & Source Currency</label>
                <div class="flex gap-2">
                    <input type="number" step="any" x-model="amount" @input="calculate()" class="w-3/5 px-4 py-3 text-xl font-mono rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                    <select x-model="fromCurr" @change="calculate()" class="w-2/5 px-3 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                        <template x-for="(rate, c) in rates" :key="c">
                            <option :value="c" x-text="c"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Target Currency</label>
                    <button type="button" @click="swap()" class="text-xs text-cyan-600 dark:text-cyan-400 font-bold hover:underline">⇄ Swap Currencies</button>
                </div>
                <div class="flex gap-2">
                    <div class="w-3/5 px-4 py-3 text-xl font-mono rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-cyan-600 dark:text-cyan-400 font-black truncate" x-text="result"></div>
                    <select x-model="toCurr" @change="calculate()" class="w-2/5 px-3 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                        <template x-for="(rate, c) in rates" :key="c">
                            <option :value="c" x-text="c"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Indicative exchange rates updated for offline high-speed browser calculation. Zero latency with no third-party tracking.
            </span>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
