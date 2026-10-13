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
        cryptoAmount: 1,
        crypto: 'BTC',
        fiat: 'USD',
        cryptoPricesInUsd: {
            'BTC': 68500,
            'ETH': 3550,
            'SOL': 165,
            'BNB': 590,
            'XRP': 0.58,
            'DOGE': 0.14,
            'USDT': 1.0
        },
        fiatRatesFromUsd: {
            'USD': 1.0,
            'BDT': 122.50,
            'EUR': 0.92,
            'GBP': 0.78,
            'INR': 86.40,
            'AED': 3.67
        },
        fiatValue: 0,
        calculate() {
            let amount = parseFloat(this.cryptoAmount) || 0;
            let priceUsd = this.cryptoPricesInUsd[this.crypto] || 0;
            let fiatRate = this.fiatRatesFromUsd[this.fiat] || 1;
            let total = amount * priceUsd * fiatRate;
            this.fiatValue = total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Cryptocurrency Amount</label>
                <div class="flex gap-2">
                    <input type="number" step="any" x-model="cryptoAmount" @input="calculate()" class="w-3/5 px-4 py-3 text-xl font-mono rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                    <select x-model="crypto" @change="calculate()" class="w-2/5 px-3 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                        <template x-for="(price, coin) in cryptoPricesInUsd" :key="coin">
                            <option :value="coin" x-text="coin"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Target Fiat Valuation</label>
                <div class="flex gap-2">
                    <div class="w-3/5 px-4 py-3 text-xl font-mono rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-cyan-600 dark:text-cyan-400 font-black truncate" x-text="fiatValue"></div>
                    <select x-model="fiat" @change="calculate()" class="w-2/5 px-3 py-3 rounded-2xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-bold">
                        <template x-for="(rate, cur) in fiatRatesFromUsd" :key="cur">
                            <option :value="cur" x-text="cur"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
            <span class="text-xs text-slate-500 dark:text-slate-400">
                Live cryptocurrency reference rates calculated client-side in browser memory with zero tracking.
            </span>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
