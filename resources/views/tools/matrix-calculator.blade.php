<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="matrixCalc()">
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
                <div class="grid grid-cols-2 gap-2 max-w-xs mx-auto">
                    <input type="number" x-model.number="m[0]" @input="calc()" class="text-center p-3 border rounded-xl font-bold bg-slate-50 dark:bg-slate-950">
                    <input type="number" x-model.number="m[1]" @input="calc()" class="text-center p-3 border rounded-xl font-bold bg-slate-50 dark:bg-slate-950">
                    <input type="number" x-model.number="m[2]" @input="calc()" class="text-center p-3 border rounded-xl font-bold bg-slate-50 dark:bg-slate-950">
                    <input type="number" x-model.number="m[3]" @input="calc()" class="text-center p-3 border rounded-xl font-bold bg-slate-50 dark:bg-slate-950">
                </div>

                <div class="grid grid-cols-2 gap-4 text-center">
                    <div class="p-5 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800">
                        <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Determinant |A|</span>
                        <span class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="det"></span>
                    </div>
                    <div class="p-5 bg-teal-50 dark:bg-teal-950/60 rounded-2xl border border-teal-100 dark:border-teal-800">
                        <span class="text-xs uppercase font-bold text-teal-800 dark:text-teal-300 block mb-1">Inverse A⁻¹</span>
                        <span class="text-xs font-mono font-bold block" x-text="inv"></span>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function matrixCalc() {
    return {
        m: [4, 7, 2, 6], det: 10, inv: '[0.6, -0.7; -0.2, 0.4]',
        init() { this.calc(); },
        calc() {
            const [a, b, c, d] = this.m;
            this.det = a * d - b * c;
            if (this.det === 0) {
                this.inv = 'Singular matrix (No Inverse)';
            } else {
                const invA = (d / this.det).toFixed(2);
                const invB = (-b / this.det).toFixed(2);
                const invC = (-c / this.det).toFixed(2);
                const invD = (a / this.det).toFixed(2);
                this.inv = `[${invA}, ${invB}; ${invC}, ${invD}]`;
            }
        }
    };
}
</script>