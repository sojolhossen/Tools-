<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="leapYearCalc()">
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
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Enter Calendar Year</label>
                    <input type="number" x-model.number="year" @input="check()" class="w-full text-lg font-bold p-3 border rounded-xl bg-slate-50 dark:bg-slate-950 text-center font-mono">
                </div>

                <div :class="isLeap ? 'bg-emerald-50 dark:bg-emerald-950/60 border-emerald-200' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800'" class="p-6 rounded-2xl border text-center">
                    <div class="text-2xl font-extrabold" :class="isLeap ? 'text-emerald-700 dark:text-emerald-300' : 'text-slate-800 dark:text-slate-200'" x-text="resultText"></div>
                    <span class="text-xs text-slate-500 mt-2 block" x-text="explanation"></span>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="date-difference-calculator" related-slug2="percentage-calculator" />
    </div>
</x-layouts.app>
<script>
function leapYearCalc() {
    return {
        year: 2028, isLeap: true, resultText: '2028 is a Leap Year! (৩৬৬ দিন)', explanation: 'Divisible by 4 and not divisible by 100.',
        init() { this.check(); },
        check() {
            const y = this.year;
            this.isLeap = (y % 4 === 0 && y % 100 !== 0) || (y % 400 === 0);
            if (this.isLeap) {
                this.resultText = `${y} is a Leap Year! (366 Days)`;
                this.explanation = 'February has 29 days.';
            } else {
                this.resultText = `${y} is NOT a Leap Year. (365 Days)`;
                this.explanation = 'February has 28 days.';
            }
        }
    };
}
</script>