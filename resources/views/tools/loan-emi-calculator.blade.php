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
            principal: 500000,
            rate: 8.5,
            tenureYears: 5,
            monthlyEmi: 0,
            totalInterest: 0,
            totalPayment: 0,
            schedule: [],
            calculate() {
                let p = parseFloat(this.principal) || 0;
                let r = (parseFloat(this.rate) || 0) / 12 / 100;
                let n = (parseFloat(this.tenureYears) || 0) * 12;

                if (p > 0 && r > 0 && n > 0) {
                    let emi = (p * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
                    this.monthlyEmi = Math.round(emi);
                    this.totalPayment = Math.round(emi * n);
                    this.totalInterest = Math.round(this.totalPayment - p);
                    
                    // Simple annual schedule
                    this.schedule = [];
                    let balance = p;
                    let years = Math.min(Math.ceil(n / 12), 30);
                    for (let y = 1; y <= years; y++) {
                        let interestYear = 0;
                        let principalYear = 0;
                        for (let m = 1; m <= 12; m++) {
                            if (balance <= 0) break;
                            let intM = balance * r;
                            let prinM = emi - intM;
                            balance -= prinM;
                            interestYear += intM;
                            principalYear += prinM;
                        }
                        this.schedule.push({
                            year: y,
                            principal: Math.round(principalYear),
                            interest: Math.round(interestYear),
                            balance: Math.max(0, Math.round(balance))
                        });
                    }
                } else if (p > 0 && n > 0 && r === 0) {
                    this.monthlyEmi = Math.round(p / n);
                    this.totalPayment = p;
                    this.totalInterest = 0;
                    this.schedule = [];
                } else {
                    this.monthlyEmi = 0;
                    this.totalPayment = 0;
                    this.totalInterest = 0;
                    this.schedule = [];
                }
            }
        }" x-init="calculate()" class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Loan Amount</label>
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="Number(principal).toLocaleString()"></span>
                            </div>
                            <input type="number" x-model="principal" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <input type="range" min="10000" max="50000000" step="10000" x-model="principal" @input="calculate()" class="w-full mt-2 accent-emerald-600">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Annual Interest Rate (%)</label>
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="rate + '%'"></span>
                            </div>
                            <input type="number" step="0.1" x-model="rate" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <input type="range" min="1" max="30" step="0.1" x-model="rate" @input="calculate()" class="w-full mt-2 accent-emerald-600">
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Loan Tenure (Years)</label>
                                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="tenureYears + ' Yrs (' + (tenureYears * 12) + ' Mos)'"></span>
                            </div>
                            <input type="number" x-model="tenureYears" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <input type="range" min="1" max="35" step="1" x-model="tenureYears" @input="calculate()" class="w-full mt-2 accent-emerald-600">
                        </div>
                    </div>

                    <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-800">
                        <div class="text-center">
                            <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">Monthly EMI</span>
                            <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-3" x-text="monthlyEmi ? monthlyEmi.toLocaleString() : '--'"></div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Fixed Monthly Installment</p>
                        </div>
                        <div class="space-y-3 pt-6 border-t border-emerald-200 dark:border-emerald-800">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Principal Amount:</span>
                                <span class="font-bold text-slate-900 dark:text-white" x-text="Number(principal).toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-600 dark:text-slate-400">Total Interest Payable:</span>
                                <span class="font-bold text-rose-600 dark:text-rose-400" x-text="totalInterest.toLocaleString()"></span>
                            </div>
                            <div class="flex justify-between items-center text-base pt-2 border-t border-emerald-200 dark:border-emerald-800/60 font-black">
                                <span class="text-slate-900 dark:text-white">Total Payment (Principal + Interest):</span>
                                <span class="text-emerald-700 dark:text-emerald-300" x-text="totalPayment.toLocaleString()"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template x-if="schedule.length > 0">
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Amortization Schedule (Yearly Breakdown)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-700 dark:text-slate-300">
                            <thead class="text-xs uppercase bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-4 py-3">Year</th>
                                    <th class="px-4 py-3">Principal Repaid</th>
                                    <th class="px-4 py-3">Interest Paid</th>
                                    <th class="px-4 py-3">Ending Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="row in schedule" :key="row.year">
                                    <tr class="border-b border-slate-100 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 font-semibold" x-text="'Year ' + row.year"></td>
                                        <td class="px-4 py-3 text-emerald-600 dark:text-emerald-400" x-text="row.principal.toLocaleString()"></td>
                                        <td class="px-4 py-3 text-rose-600 dark:text-rose-400" x-text="row.interest.toLocaleString()"></td>
                                        <td class="px-4 py-3 font-bold" x-text="row.balance.toLocaleString()"></td>
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
