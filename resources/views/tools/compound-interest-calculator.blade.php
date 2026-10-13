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
            principal: 100000,
            monthlyDeposit: 5000,
            rate: 10,
            years: 10,
            freq: 12,
            maturityAmount: 0,
            totalDeposited: 0,
            interestEarned: 0,
            schedule: [],
            calculate() {
                let p = parseFloat(this.principal) || 0;
                let pmt = parseFloat(this.monthlyDeposit) || 0;
                let r = (parseFloat(this.rate) || 0) / 100;
                let y = parseFloat(this.years) || 1;
                let n = parseInt(this.freq) || 12;

                let total = p;
                let dep = p;
                this.schedule = [];

                for (let yr = 1; yr <= y; yr++) {
                    for (let m = 1; m <= 12; m++) {
                        total += pmt;
                        dep += pmt;
                        total *= (1 + (r / 12));
                    }
                    this.schedule.push({
                        year: yr,
                        deposited: Math.round(dep),
                        interest: Math.round(total - dep),
                        balance: Math.round(total)
                    });
                }

                this.maturityAmount = Math.round(total);
                this.totalDeposited = Math.round(dep);
                this.interestEarned = Math.round(total - dep);
            }
        }" x-init="calculate()" class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Initial Principal Investment</label>
                            <input type="number" x-model="principal" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Monthly Contribution</label>
                            <input type="number" x-model="monthlyDeposit" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Annual Rate (%)</label>
                                <input type="number" step="0.1" x-model="rate" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Horizon (Years)</label>
                                <input type="number" x-model="years" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-800">
                        <div class="text-center">
                            <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Estimated Future Value</span>
                            <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-3" x-text="maturityAmount.toLocaleString()"></div>
                        </div>
                        <div class="space-y-3 pt-6 border-t border-emerald-200 dark:border-emerald-800">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Total Invested Principal:</span>
                                <span class="font-bold text-slate-900 dark:text-white" x-text="totalDeposited.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Compound Wealth Earned:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="interestEarned.toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Yearly Wealth Accumulation</h3>
                <div class="overflow-x-auto max-h-80 overflow-y-auto">
                    <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                        <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-700/50 sticky top-0">
                            <tr>
                                <th class="px-4 py-2.5">Year</th>
                                <th class="px-4 py-2.5">Deposits</th>
                                <th class="px-4 py-2.5">Interest Profit</th>
                                <th class="px-4 py-2.5">Ending Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="r in schedule" :key="r.year">
                                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                    <td class="px-4 py-2.5 font-semibold" x-text="'Year ' + r.year"></td>
                                    <td class="px-4 py-2.5" x-text="r.deposited.toLocaleString()"></td>
                                    <td class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400" x-text="r.interest.toLocaleString()"></td>
                                    <td class="px-4 py-2.5 font-bold" x-text="r.balance.toLocaleString()"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="loan-emi-calculator" related-slug2="sip-calculator" />
        </div>
    </div>
</x-layouts.app>
