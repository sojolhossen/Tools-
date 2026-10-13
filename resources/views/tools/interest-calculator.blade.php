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

    <div class="mt-6 mb-8 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 text-indigo-600 dark:text-indigo-400 mb-3 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl tracking-tight">{{ $tool->getName() }}</h1>
        <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
            {{ $tool->getDescription() }}
        </p>
        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ __('common.badges.browser_based') }}
        </div>
    </div>

    
    <div x-data="{
        type: 'compound',
        principal: 100000,
        rate: 7,
        years: 5,
        frequency: 1,
        totalInterest: 0,
        futureValue: 0,
        calculate() {
            let p = parseFloat(this.principal) || 0;
            let r = (parseFloat(this.rate) || 0) / 100;
            let t = parseFloat(this.years) || 0;
            let n = parseFloat(this.frequency) || 1;

            if (p > 0 && t > 0) {
                if (this.type === 'simple') {
                    let interest = p * r * t;
                    this.totalInterest = Math.round(interest);
                    this.futureValue = Math.round(p + interest);
                } else {
                    let amount = p * Math.pow(1 + (r / n), n * t);
                    this.futureValue = Math.round(amount);
                    this.totalInterest = Math.round(amount - p);
                }
            } else {
                this.totalInterest = 0;
                this.futureValue = 0;
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-700 rounded-xl">
                <button type="button" @click="type = 'compound'; calculate()" :class="type === 'compound' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Compound Interest</button>
                <button type="button" @click="type = 'simple'; calculate()" :class="type === 'simple' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Simple Interest</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Principal Amount</label>
                    <input type="number" x-model="principal" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Annual Interest Rate (%)</label>
                    <input type="number" step="0.1" x-model="rate" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Time Period (Years)</label>
                    <input type="number" x-model="years" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div x-show="type === 'compound'">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Compounding Frequency</label>
                    <select x-model="frequency" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="1">Annually (Once a year)</option>
                        <option value="2">Semi-Annually (Twice a year)</option>
                        <option value="4">Quarterly (4 times a year)</option>
                        <option value="12">Monthly (12 times a year)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col justify-center p-6 rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-900/50 space-y-6">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Total Future Balance</span>
                    <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-2" x-text="futureValue.toLocaleString()"></div>
                </div>
                <div class="space-y-3 pt-4 border-t border-emerald-200 dark:border-emerald-800">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Principal Amount:</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="parseFloat(principal).toLocaleString()"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Total Interest Earned:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="'+ ' + totalInterest.toLocaleString()"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
