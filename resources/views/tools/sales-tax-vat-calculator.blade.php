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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
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
        mode: 'add',
        amount: 1000,
        taxRate: 15,
        netAmount: 0,
        taxAmount: 0,
        grossAmount: 0,
        calculate() {
            let amt = parseFloat(this.amount) || 0;
            let rate = parseFloat(this.taxRate) || 0;

            if (this.mode === 'add') {
                this.netAmount = amt.toFixed(2);
                let tax = amt * (rate / 100);
                this.taxAmount = tax.toFixed(2);
                this.grossAmount = (amt + tax).toFixed(2);
            } else {
                // Remove tax
                let net = amt / (1 + (rate / 100));
                this.netAmount = net.toFixed(2);
                this.taxAmount = (amt - net).toFixed(2);
                this.grossAmount = amt.toFixed(2);
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-700 rounded-xl">
                <button type="button" @click="mode = 'add'; calculate()" :class="mode === 'add' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Add Tax (Exclusive)</button>
                <button type="button" @click="mode = 'remove'; calculate()" :class="mode === 'remove' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Remove Tax (Inclusive)</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" x-text="mode === 'add' ? 'Net Amount (Before Tax)' : 'Gross Amount (Including Tax)'"></label>
                    <input type="number" step="0.01" x-model="amount" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Tax / VAT / GST Rate (%)</label>
                    <input type="number" step="0.1" x-model="taxRate" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="space-y-3 p-6 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600 dark:text-slate-400">Net Amount:</span>
                    <span class="font-bold text-slate-900 dark:text-white" x-text="netAmount"></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-600 dark:text-slate-400">Tax Amount:</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="taxAmount"></span>
                </div>
                <div class="flex justify-between items-center text-lg pt-3 border-t border-slate-200 dark:border-slate-600 font-black">
                    <span class="text-slate-900 dark:text-white">Total Gross Amount:</span>
                    <span class="text-emerald-600 dark:text-emerald-400" x-text="grossAmount"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
