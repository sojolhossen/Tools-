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
            q1: 100, p1: 150,
            q2: 150, p2: 120,
            q3: 0, p3: 0,
            totalShares: 0,
            totalCapital: 0,
            avgPrice: 0,
            calculate() {
                let q1 = parseFloat(this.q1) || 0;
                let p1 = parseFloat(this.p1) || 0;
                let q2 = parseFloat(this.q2) || 0;
                let p2 = parseFloat(this.p2) || 0;
                let q3 = parseFloat(this.q3) || 0;
                let p3 = parseFloat(this.p3) || 0;

                let totQ = q1 + q2 + q3;
                let totC = (q1 * p1) + (q2 * p2) + (q3 * p3);

                this.totalShares = totQ;
                this.totalCapital = Math.round(totC);
                this.avgPrice = totQ > 0 ? (totC / totQ).toFixed(2) : 0;
            }
        }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40">
                        <span class="text-xs font-bold text-slate-500 uppercase">1st Purchase</span>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <input type="number" placeholder="Quantity" x-model="q1" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <input type="number" placeholder="Buy Price" x-model="p1" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40">
                        <span class="text-xs font-bold text-slate-500 uppercase">2nd Purchase (Average Down)</span>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <input type="number" placeholder="Quantity" x-model="q2" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <input type="number" placeholder="Buy Price" x-model="p2" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/40">
                        <span class="text-xs font-bold text-slate-500 uppercase">3rd Purchase (Optional)</span>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <input type="number" placeholder="Quantity" x-model="q3" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <input type="number" placeholder="Buy Price" x-model="p3" @input="calculate()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-between p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border border-emerald-200 dark:border-emerald-800">
                    <div class="text-center">
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-700 dark:text-emerald-400">New Average Price Per Share</span>
                        <div class="text-4xl sm:text-5xl font-black text-emerald-600 dark:text-emerald-400 my-3" x-text="avgPrice"></div>
                    </div>
                    <div class="space-y-3 pt-6 border-t border-emerald-200 dark:border-emerald-800">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 dark:text-slate-400">Total Shares Owned:</span>
                            <span class="font-bold text-slate-900 dark:text-white" x-text="totalShares"></span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-600 dark:text-slate-400">Total Capital Invested:</span>
                            <span class="font-bold text-emerald-700 dark:text-emerald-300 text-base" x-text="totalCapital.toLocaleString()"></span>
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
