<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dateDiffCalc()">
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Start Date</label>
                        <input type="date" x-model="start" @change="calc()" class="w-full text-sm p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">End Date</label>
                        <input type="date" x-model="end" @change="calc()" class="w-full text-sm p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                </div>

                <div class="p-6 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800 text-center">
                    <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Total Difference</span>
                    <div class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="days + ' Days'"></div>
                    <span class="text-xs text-slate-500 mt-2 block" x-text="summary"></span>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="age-calculator" related-slug2="percentage-calculator" />
    </div>
</x-layouts.app>
<script>
function dateDiffCalc() {
    return {
        start: '2026-01-01', end: '2026-12-31', days: 364, summary: '52 Weeks and 0 Days',
        init() { this.calc(); },
        calc() {
            if (!this.start || !this.end) return;
            const d1 = new Date(this.start), d2 = new Date(this.end);
            const diffMs = Math.abs(d2 - d1);
            this.days = Math.round(diffMs / (1000 * 60 * 60 * 24));
            const weeks = Math.floor(this.days / 7);
            const remDays = this.days % 7;
            this.summary = `${weeks} Weeks and ${remDays} Days`;
        }
    };
}
</script>