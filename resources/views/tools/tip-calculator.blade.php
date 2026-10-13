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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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
        bill: 120,
        tipPercent: 15,
        people: 3,
        tipAmount: 0,
        totalBill: 0,
        perPerson: 0,
        calculate() {
            let b = parseFloat(this.bill) || 0;
            let tipP = parseFloat(this.tipPercent) || 0;
            let ppl = parseInt(this.people) || 1;

            let tip = b * (tipP / 100);
            let total = b + tip;
            this.tipAmount = tip.toFixed(2);
            this.totalBill = total.toFixed(2);
            this.perPerson = (total / Math.max(1, ppl)).toFixed(2);
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bill Amount</label>
                    <input type="number" step="0.01" x-model="bill" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tip Percentage (%)</label>
                    <div class="grid grid-cols-4 gap-2 mb-2">
                        <button type="button" @click="tipPercent = 10; calculate()" class="py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">10%</button>
                        <button type="button" @click="tipPercent = 15; calculate()" class="py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">15%</button>
                        <button type="button" @click="tipPercent = 18; calculate()" class="py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">18%</button>
                        <button type="button" @click="tipPercent = 20; calculate()" class="py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700">20%</button>
                    </div>
                    <input type="number" step="0.5" x-model="tipPercent" @input="calculate()" class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Number of People (Split)</label>
                    <input type="number" min="1" x-model="people" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-indigo-50/80 via-white to-indigo-50/50 dark:from-indigo-950/50 dark:via-slate-900 dark:to-indigo-950/30 border border-indigo-100 dark:border-indigo-800/60 shadow-sm">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-indigo-600 dark:text-indigo-400">Total Per Person</span>
                    <div class="text-5xl font-black text-indigo-600 dark:text-indigo-300 my-3 tracking-tight" x-text="perPerson"></div>
                </div>
                <div class="space-y-3 pt-6 border-t border-indigo-200 dark:border-indigo-800 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Tip Amount:</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="tipAmount"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Total Bill + Tip:</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="totalBill"></span>
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
