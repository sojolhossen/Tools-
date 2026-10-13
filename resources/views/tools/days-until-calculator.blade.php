<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="daysUntilCalc()">
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
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Target Event Date</label>
                    <input type="date" x-model="target" @change="calc()" class="w-full text-sm p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                </div>

                <div class="p-6 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800 text-center">
                    <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Time Remaining</span>
                    <div class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="daysRemaining + ' Days'"></div>
                    <span class="text-xs text-slate-500 mt-2 block" x-text="hoursRemaining"></span>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="date-difference-calculator" related-slug2="percentage-calculator" />
    </div>
</x-layouts.app>
<script>
function daysUntilCalc() {
    return {
        target: '2027-01-01', daysRemaining: 0, hoursRemaining: '',
        init() { this.calc(); },
        calc() {
            if (!this.target) return;
            const diff = new Date(this.target) - new Date();
            if (diff < 0) {
                this.daysRemaining = 0; this.hoursRemaining = 'Event has already passed!';
                return;
            }
            this.daysRemaining = Math.ceil(diff / (1000 * 60 * 60 * 24));
            const hours = Math.ceil(diff / (1000 * 60 * 60));
            this.hoursRemaining = `Approx. ${hours} Hours remaining`;
        }
    };
}
</script>