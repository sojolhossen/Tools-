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
            monthlyInvestment: 5000,
            expectedReturn: 12,
            timePeriodYears: 10,
            totalInvested: 0,
            estimatedReturns: 0,
            totalValue: 0,
            schedule: [],
            calculate() {
                let p = parseFloat(this.monthlyInvestment) || 0;
                let r = (parseFloat(this.expectedReturn) || 0) / 12 / 100;
                let n = (parseFloat(this.timePeriodYears) || 0) * 12;

                if (p > 0 && r > 0 && n > 0) {
                    let fv = p * ((Math.pow(1 + r, n) - 1) / r) * (1 + r);
                    this.totalValue = Math.round(fv);
                    this.totalInvested = Math.round(p * n);
                    this.estimatedReturns = Math.round(fv - this.totalInvested);

                    this.schedule = [];
                    let y = Math.min(Math.ceil(n / 12), 35);
                    for (let yr = 1; yr <= y; yr++) {
                        let mCount = yr * 12;
                        let fvYr = p * ((Math.pow(1 + r, mCount) - 1) / r) * (1 + r);
                        let invYr = p * mCount;
                        this.schedule.push({
                            year: yr,
                            invested: Math.round(invYr),
                            returns: Math.round(fvYr - invYr),
                            total: Math.round(fvYr)
                        });
                    }
                } else {
                    this.totalValue = 0;
                    this.totalInvested = 0;
                    this.estimatedReturns = 0;
                    this.schedule = [];
                }
            }
        }" x-init="calculate()" class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Monthly Investment</label>
                            <input type="number" x-model="monthlyInvestment" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Expected Annual Return Rate (%)</label>
                            <input type="number" step="0.5" x-model="expectedReturn" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Investment Period (Years)</label>
                            <input type="number" x-model="timePeriodYears" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-800">
                        <div class="text-center">
                            <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Total Expected Maturity</span>
                            <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-3" x-text="totalValue.toLocaleString()"></div>
                        </div>
                        <div class="space-y-3 pt-6 border-t border-emerald-200 dark:border-emerald-800">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Invested Amount:</span>
                                <span class="font-bold text-slate-900 dark:text-white" x-text="totalInvested.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Est. Returns (Wealth Gain):</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="estimatedReturns.toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template x-if="schedule.length > 0">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">SIP Wealth Growth Trajectory</h3>
                    <div class="overflow-x-auto max-h-80 overflow-y-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 dark:bg-slate-700/50 sticky top-0 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th class="p-3">Year</th>
                                    <th class="p-3">Total Invested</th>
                                    <th class="p-3">Returns Accrued</th>
                                    <th class="p-3">Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in schedule" :key="row.year">
                                    <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                        <td class="p-3 font-semibold" x-text="'Year ' + row.year"></td>
                                        <td class="p-3" x-text="row.invested.toLocaleString()"></td>
                                        <td class="p-3 text-emerald-600 dark:text-emerald-400" x-text="row.returns.toLocaleString()"></td>
                                        <td class="p-3 font-bold" x-text="row.total.toLocaleString()"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>
        
        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="loan-emi-calculator" related-slug2="sip-calculator" />
        </div>
    </div>
</x-layouts.app>
