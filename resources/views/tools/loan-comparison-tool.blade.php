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
            pA: 1000000, rA: 8.5, tA: 10, feeA: 5000,
            pB: 1000000, rB: 9.0, tB: 10, feeB: 1000,
            emiA: 0, intA: 0, totalA: 0,
            emiB: 0, intB: 0, totalB: 0,
            calculate() {
                let calc = (p, r, t, fee) => {
                    let rate = (r / 12) / 100;
                    let n = t * 12;
                    let emi = (rate > 0 && n > 0 && p > 0) ? (p * rate * Math.pow(1 + rate, n)) / (Math.pow(1 + rate, n) - 1) : 0;
                    let totInt = (emi * n) - p;
                    return { emi: Math.round(emi), interest: Math.round(totInt), total: Math.round(p + totInt + fee) };
                };
                let resA = calc(parseFloat(this.pA)||0, parseFloat(this.rA)||0, parseFloat(this.tA)||0, parseFloat(this.feeA)||0);
                this.emiA = resA.emi; this.intA = resA.interest; this.totalA = resA.total;

                let resB = calc(parseFloat(this.pB)||0, parseFloat(this.rB)||0, parseFloat(this.tB)||0, parseFloat(this.feeB)||0);
                this.emiB = resB.emi; this.intB = resB.interest; this.totalB = resB.total;
            }
        }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Loan Option A -->
                <div class="p-5 rounded-xl border border-indigo-200 dark:border-indigo-800/60 bg-indigo-50/30 dark:bg-indigo-950/20 space-y-4">
                    <h3 class="font-bold text-indigo-700 dark:text-indigo-400 text-lg">Loan Option A (Bank 1)</h3>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Loan Amount</label>
                        <input type="number" x-model="pA" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Interest Rate (%)</label>
                            <input type="number" step="0.1" x-model="rA" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Tenure (Years)</label>
                            <input type="number" x-model="tA" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Processing Fee</label>
                        <input type="number" x-model="feeA" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <!-- Loan Option B -->
                <div class="p-5 rounded-xl border border-teal-200 dark:border-teal-800/60 bg-teal-50/30 dark:bg-teal-950/20 space-y-4">
                    <h3 class="font-bold text-teal-700 dark:text-teal-400 text-lg">Loan Option B (Bank 2)</h3>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Loan Amount</label>
                        <input type="number" x-model="pB" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Interest Rate (%)</label>
                            <input type="number" step="0.1" x-model="rB" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Tenure (Years)</label>
                            <input type="number" x-model="tB" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Processing Fee</label>
                        <input type="number" x-model="feeB" @input="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 font-bold border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="p-4">Comparison Metric</th>
                            <th class="p-4 text-indigo-600 dark:text-indigo-400">Option A</th>
                            <th class="p-4 text-teal-600 dark:text-teal-400">Option B</th>
                            <th class="p-4">Savings Difference</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <tr>
                            <td class="p-4 font-medium text-slate-600 dark:text-slate-400">Monthly EMI</td>
                            <td class="p-4 font-bold" x-text="emiA.toLocaleString()"></td>
                            <td class="p-4 font-bold" x-text="emiB.toLocaleString()"></td>
                            <td class="p-4 font-semibold text-emerald-600 dark:text-emerald-400" x-text="Math.abs(emiA - emiB).toLocaleString() + (emiA < emiB ? ' (Option A cheaper)' : ' (Option B cheaper)')"></td>
                        </tr>
                        <tr>
                            <td class="p-4 font-medium text-slate-600 dark:text-slate-400">Total Interest</td>
                            <td class="p-4 font-bold" x-text="intA.toLocaleString()"></td>
                            <td class="p-4 font-bold" x-text="intB.toLocaleString()"></td>
                            <td class="p-4 font-semibold" x-text="Math.abs(intA - intB).toLocaleString()"></td>
                        </tr>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/80 font-bold">
                            <td class="p-4 text-slate-900 dark:text-white">Overall Total Cost</td>
                            <td class="p-4 text-indigo-600 dark:text-indigo-400" x-text="totalA.toLocaleString()"></td>
                            <td class="p-4 text-teal-600 dark:text-teal-400" x-text="totalB.toLocaleString()"></td>
                            <td class="p-4 text-emerald-600 dark:text-emerald-400" x-text="'Net Difference: ' + Math.abs(totalA - totalB).toLocaleString()"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="loan-emi-calculator" related-slug2="sip-calculator" />
        </div>
    </div>
</x-layouts.app>
