<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="avgCalc()">
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
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-2">Enter Numbers (comma or space separated)</label>
                    <textarea x-model="inputVal" @input="calc()" rows="3" class="w-full text-sm font-mono p-3 border rounded-xl bg-slate-50 dark:bg-slate-950"></textarea>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Mean (গড়)</span>
                        <span class="text-xl font-extrabold text-emerald-600 font-mono mt-1 block" x-text="mean"></span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Median (মধ্যমা)</span>
                        <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 font-mono mt-1 block" x-text="median"></span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Mode (প্রচুরক)</span>
                        <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 font-mono mt-1 block" x-text="mode"></span>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Sum (মোট)</span>
                        <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 font-mono mt-1 block" x-text="sum"></span>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function avgCalc() {
    return {
        inputVal: '10, 15, 20, 25, 30', mean: 20, median: 20, mode: 'None', sum: 100,
        init() { this.calc(); },
        calc() {
            const arr = this.inputVal.split(/[,\s]+/).map(Number).filter(n => !isNaN(n));
            if (!arr.length) return;
            this.sum = arr.reduce((a, b) => a + b, 0);
            this.mean = (this.sum / arr.length).toFixed(2);
            const sorted = [...arr].sort((a, b) => a - b);
            const mid = Math.floor(sorted.length / 2);
            this.median = sorted.length % 2 !== 0 ? sorted[mid] : ((sorted[mid - 1] + sorted[mid]) / 2);

            const counts = {};
            arr.forEach(n => counts[n] = (counts[n] || 0) + 1);
            const maxF = Math.max(...Object.values(counts));
            if (maxF > 1) {
                this.mode = Object.keys(counts).filter(k => counts[k] === maxF).join(', ');
            } else {
                this.mode = 'None';
            }
        }
    };
}
</script>