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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
        lmpDate: new Date().toISOString().split('T')[0],
        cycleLength: 28,
        dueDate: '',
        currentWeek: 0,
        trimester: '',
        daysLeft: 0,
        calculate() {
            if (!this.lmpDate) return;
            let lmp = new Date(this.lmpDate);
            let cycle = parseInt(this.cycleLength) || 28;
            
            // Naegele's rule adjusted for cycle length: +280 days + (cycle - 28)
            let due = new Date(lmp.getTime() + (280 + (cycle - 28)) * 86400000);
            this.dueDate = due.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
            
            let today = new Date();
            let diffDays = Math.floor((today - lmp) / 86400000);
            this.currentWeek = Math.max(0, Math.floor(diffDays / 7));
            let daysRemaining = Math.floor((due - today) / 86400000);
            this.daysLeft = Math.max(0, daysRemaining);

            if (this.currentWeek <= 13) this.trimester = '1st Trimester (Weeks 1-13)';
            else if (this.currentWeek <= 26) this.trimester = '2nd Trimester (Weeks 14-26)';
            else this.trimester = '3rd Trimester (Weeks 27-40+)';
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">First Day of Last Menstrual Period (LMP)</label>
                    <input type="date" x-model="lmpDate" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Average Menstrual Cycle Length (Days)</label>
                    <input type="number" x-model="cycleLength" @input="calculate()" min="20" max="45" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    <span class="text-xs text-slate-400 mt-1 block">Default standard is 28 days</span>
                </div>
            </div>

            <div class="flex flex-col justify-between p-6 rounded-2xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-rose-700 dark:text-rose-400">Estimated Due Date (EDD)</span>
                    <div class="text-3xl sm:text-4xl font-black text-rose-600 dark:text-rose-400 my-3" x-text="dueDate || '--'"></div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300" x-text="trimester"></span>
                </div>
                <div class="mt-6 pt-6 border-t border-rose-200 dark:border-rose-800 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <span class="text-xs text-slate-500">Current Progress</span>
                        <div class="text-lg font-bold text-slate-800 dark:text-slate-200" x-text="'Week ' + currentWeek"></div>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500">Days Remaining</span>
                        <div class="text-lg font-bold text-rose-600 dark:text-rose-400" x-text="daysLeft + ' Days'"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
