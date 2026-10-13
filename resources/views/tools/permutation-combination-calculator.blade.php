<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="permCombCalc()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $tool->getName() }}</h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">{{ $tool->getDescription() }}</p>
        </div>

        <div class="max-w-xl mx-auto mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Total items (n)</label>
                        <input type="number" x-model.number="n" @input="calc()" min="0" class="w-full text-sm font-mono p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Items to choose (r)</label>
                        <input type="number" x-model.number="r" @input="calc()" min="0" class="w-full text-sm font-mono p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="p-5 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                        <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Permutation (nPr)</span>
                        <span class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="nPr"></span>
                    </div>
                    <div class="p-5 bg-teal-50 dark:bg-teal-950/60 rounded-2xl border border-teal-100 dark:border-teal-800">
                        <span class="text-xs uppercase font-bold text-teal-800 dark:text-teal-300 block mb-1">Combination (nCr)</span>
                        <span class="text-2xl font-extrabold text-teal-700 dark:text-teal-300 font-mono" x-text="nCr"></span>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function permCombCalc() {
    return {
        n: 6, r: 2, nPr: 30, nCr: 15,
        init() { this.calc(); },
        fact(num) {
            let res = 1;
            for (let i = 2; i <= num; i++) res *= i;
            return res;
        },
        calc() {
            if (this.n < 0 || this.r < 0 || this.r > this.n) {
                this.nPr = 'Invalid'; this.nCr = 'Invalid'; return;
            }
            this.nPr = this.fact(this.n) / this.fact(this.n - this.r);
            this.nCr = this.fact(this.n) / (this.fact(this.r) * this.fact(this.n - this.r));
        }
    };
}
</script>