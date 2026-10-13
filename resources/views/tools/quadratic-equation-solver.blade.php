<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="quadraticSolver()">
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
                <div class="flex items-center justify-center gap-2 text-base font-bold font-mono">
                    <input type="number" x-model.number="a" @input="solve()" class="w-16 text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950">
                    <span>x² +</span>
                    <input type="number" x-model.number="b" @input="solve()" class="w-16 text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950">
                    <span>x +</span>
                    <input type="number" x-model.number="c" @input="solve()" class="w-16 text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950">
                    <span>= 0</span>
                </div>

                <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Discriminant (b² - 4ac):</span>
                        <strong class="font-mono" x-text="discriminant"></strong>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-semibold">Root 1 (x₁):</span>
                        <strong class="font-mono text-emerald-600" x-text="root1"></strong>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-semibold">Root 2 (x₂):</span>
                        <strong class="font-mono text-emerald-600" x-text="root2"></strong>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function quadraticSolver() {
    return {
        a: 1, b: -5, c: 6, discriminant: 1, root1: '3', root2: '2',
        init() { this.solve(); },
        solve() {
            const d = this.b * this.b - 4 * this.a * this.c;
            this.discriminant = d;
            if (this.a === 0) { this.root1 = 'Not quadratic (a=0)'; this.root2 = ''; return; }
            if (d > 0) {
                this.root1 = ((-this.b + Math.sqrt(d)) / (2 * this.a)).toFixed(4);
                this.root2 = ((-this.b - Math.sqrt(d)) / (2 * this.a)).toFixed(4);
            } else if (d === 0) {
                this.root1 = (-this.b / (2 * this.a)).toFixed(4);
                this.root2 = this.root1;
            } else {
                const real = (-this.b / (2 * this.a)).toFixed(2);
                const imag = (Math.sqrt(-d) / (2 * this.a)).toFixed(2);
                this.root1 = `${real} + ${imag}i`;
                this.root2 = `${real} - ${imag}i`;
            }
        }
    };
}
</script>