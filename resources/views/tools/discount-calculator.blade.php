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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
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
        originalPrice: 1200,
        discountPercent: 20,
        additionalDiscount: 0,
        taxPercent: 0,
        finalPrice: 0,
        totalSavings: 0,
        calculate() {
            let p = parseFloat(this.originalPrice) || 0;
            let d1 = parseFloat(this.discountPercent) || 0;
            let d2 = parseFloat(this.additionalDiscount) || 0;
            let tax = parseFloat(this.taxPercent) || 0;

            let priceAfterD1 = p * (1 - (d1 / 100));
            let priceAfterD2 = priceAfterD1 * (1 - (d2 / 100));
            let savings = p - priceAfterD2;
            let finalWithTax = priceAfterD2 * (1 + (tax / 100));

            this.finalPrice = finalWithTax.toFixed(2);
            this.totalSavings = savings.toFixed(2);
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Original Price</label>
                    <input type="number" step="0.01" x-model="originalPrice" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Discount Percentage (%)</label>
                    <input type="number" step="0.1" x-model="discountPercent" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Additional Off (%)</label>
                        <input type="number" step="0.1" x-model="additionalDiscount" @input="calculate()" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Sales Tax (%)</label>
                        <input type="number" step="0.1" x-model="taxPercent" @input="calculate()" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center p-6 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 space-y-4">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-amber-700 dark:text-amber-400">Final Discounted Price</span>
                    <div class="text-5xl font-black text-amber-600 dark:text-amber-400 my-2" x-text="finalPrice"></div>
                </div>
                <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-amber-100 dark:border-amber-900/40 text-center">
                    <span class="text-xs text-slate-500">You Save</span>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400" x-text="totalSavings"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
