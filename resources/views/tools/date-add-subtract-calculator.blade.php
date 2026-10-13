<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dateAddSubCalc()">
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Start Date</label>
                        <input type="date" x-model="startDate" @change="calc()" class="w-full text-xs p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Action</label>
                        <select x-model="action" @change="calc()" class="w-full text-xs p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                            <option value="add">Add (+)</option>
                            <option value="sub">Subtract (−)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Days</label>
                        <input type="number" x-model.number="days" @input="calc()" min="0" class="w-full text-xs p-3 border rounded-xl bg-slate-50 dark:bg-slate-950 font-bold">
                    </div>
                </div>

                <div class="p-6 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800 text-center">
                    <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Calculated Target Date</span>
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="targetDate"></div>
                    <span class="text-xs text-slate-500 mt-2 block" x-text="dayOfWeek"></span>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="date-difference-calculator" related-slug2="percentage-calculator" />
    </div>
</x-layouts.app>
<script>
function dateAddSubCalc() {
    return {
        startDate: '2026-10-13', action: 'add', days: 30, targetDate: '', dayOfWeek: '',
        init() { this.calc(); },
        calc() {
            if (!this.startDate) return;
            const d = new Date(this.startDate);
            const offset = this.action === 'add' ? this.days : -this.days;
            d.setDate(d.getDate() + offset);
            this.targetDate = d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            this.dayOfWeek = d.toLocaleDateString('en-US', { weekday: 'long' });
        }
    };
}
</script>