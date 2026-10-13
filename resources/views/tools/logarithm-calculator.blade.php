<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="logCalc()">
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
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Number (x)</label>
                    <input type="number" x-model.number="x" @input="calc()" min="0.0001" step="any" class="w-full text-sm font-mono p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                </div>

                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">log₁₀ (Common)</span>
                        <span class="text-lg font-bold text-emerald-600 font-mono mt-1 block" x-text="log10"></span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">ln (Natural)</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-slate-200 font-mono mt-1 block" x-text="ln"></span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">log₂ (Binary)</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-slate-200 font-mono mt-1 block" x-text="log2"></span>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function logCalc() {
    return {
        x: 100, log10: '2', ln: '4.6052', log2: '6.6439',
        init() { this.calc(); },
        calc() {
            if (this.x <= 0) return;
            this.log10 = Math.log10(this.x).toFixed(4);
            this.ln = Math.log(this.x).toFixed(4);
            this.log2 = Math.log2(this.x).toFixed(4);
        }
    };
}
</script>