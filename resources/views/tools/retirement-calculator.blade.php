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
            <div class="inline-flex p-3 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 text-emerald-600 dark:text-emerald-400 mb-3 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
            currentAge: 30,
            retireAge: 60,
            lifeExpectancy: 80,
            monthlyExpense: 40000,
            inflationRate: 6,
            postReturn: 8,
            preReturn: 12,
            corpusRequired: 0,
            monthlySavingsRequired: 0,
            calculate() {
                let ca = parseInt(this.currentAge) || 30;
                let ra = parseInt(this.retireAge) || 60;
                let le = parseInt(this.lifeExpectancy) || 80;
                let exp = parseFloat(this.monthlyExpense) || 0;
                let inf = (parseFloat(this.inflationRate) || 0) / 100;
                let postR = (parseFloat(this.postReturn) || 0) / 100;
                let preR = (parseFloat(this.preReturn) || 0) / 100;

                let yearsToRetire = Math.max(1, ra - ca);
                let yearsInRetire = Math.max(1, le - ra);

                // Future monthly expense at retirement
                let futureMonthlyExp = exp * Math.pow(1 + inf, yearsToRetire);
                let annualExpAtRetire = futureMonthlyExp * 12;

                // Real rate of return post-retirement
                let realRate = (1 + postR) / (1 + inf) - 1;

                // Present value of annuity for retirement corpus
                let corpus = 0;
                if (Math.abs(realRate) > 0.001) {
                    corpus = annualExpAtRetire * ((1 - Math.pow(1 + realRate, -yearsInRetire)) / realRate);
                } else {
                    corpus = annualExpAtRetire * yearsInRetire;
                }
                this.corpusRequired = Math.round(corpus);

                // Monthly SIP needed to reach corpus
                let rMonthly = preR / 12;
                let nMonths = yearsToRetire * 12;
                if (rMonthly > 0 && nMonths > 0) {
                    let sip = corpus / (((Math.pow(1 + rMonthly, nMonths) - 1) / rMonthly) * (1 + rMonthly));
                    this.monthlySavingsRequired = Math.round(sip);
                } else {
                    this.monthlySavingsRequired = 0;
                }
            }
        }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Current Age</label>
                            <input type="number" x-model="currentAge" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Retire Age</label>
                            <input type="number" x-model="retireAge" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Life Expectancy</label>
                            <input type="number" x-model="lifeExpectancy" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Current Monthly Living Expenses</label>
                        <input type="number" x-model="monthlyExpense" @input="calculate()" class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Inflation %</label>
                            <input type="number" step="0.5" x-model="inflationRate" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Pre-Ret. Return %</label>
                            <input type="number" step="0.5" x-model="preReturn" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Post-Ret. Return %</label>
                            <input type="number" step="0.5" x-model="postReturn" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-800">
                    <div class="text-center">
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Total Retirement Corpus Needed</span>
                        <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-3" x-text="corpusRequired.toLocaleString()"></div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Inflation-adjusted nest egg for living expenses</p>
                    </div>
                    <div class="space-y-3 pt-6 border-t border-emerald-200 dark:border-emerald-800">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 dark:text-slate-400">Required Monthly Savings Today:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 text-base" x-text="monthlySavingsRequired.toLocaleString()"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="loan-emi-calculator" related-slug2="sip-calculator" />
        </div>
    </div>
</x-layouts.app>
