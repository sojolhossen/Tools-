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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
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
        currentWeight: 80,
        targetWeight: 72,
        maintenanceCalories: 2400,
        weeklyLoss: 0.5,
        targetCalories: 1900,
        weeksNeeded: 16,
        calculate() {
            let curr = parseFloat(this.currentWeight) || 0;
            let target = parseFloat(this.targetWeight) || 0;
            let maint = parseFloat(this.maintenanceCalories) || 2000;
            let lossRate = parseFloat(this.weeklyLoss) || 0.5;

            // 1 kg fat ~= 7700 kcal
            let dailyDeficit = (lossRate * 7700) / 7;
            this.targetCalories = Math.max(1200, Math.round(maint - dailyDeficit));
            
            if (curr > target && lossRate > 0) {
                this.weeksNeeded = Math.ceil((curr - target) / lossRate);
            } else {
                this.weeksNeeded = 0;
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Maintenance Calories (TDEE)</label>
                    <input type="number" x-model="maintenanceCalories" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Current Weight (kg)</label>
                        <input type="number" x-model="currentWeight" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Target Weight (kg)</label>
                        <input type="number" x-model="targetWeight" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Desired Weekly Weight Loss</label>
                    <select x-model="weeklyLoss" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="0.25">Mild (0.25 kg / 0.55 lbs per week)</option>
                        <option value="0.5">Recommended (0.50 kg / 1.1 lbs per week)</option>
                        <option value="0.75">Fast (0.75 kg / 1.65 lbs per week)</option>
                        <option value="1.0">Aggressive (1.0 kg / 2.2 lbs per week)</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col justify-between p-6 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-amber-700 dark:text-amber-400">Target Daily Intake</span>
                    <div class="text-5xl font-black text-slate-900 dark:text-white my-3" x-text="targetCalories.toLocaleString() + ' kcal'"></div>
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400" x-text="'Deficit of ' + (maintenanceCalories - targetCalories) + ' kcal per day'"></span>
                </div>
                <div class="mt-6 pt-6 border-t border-amber-200 dark:border-amber-800 text-center">
                    <span class="text-xs text-slate-500">Estimated Duration to Goal</span>
                    <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1" x-text="weeksNeeded > 0 ? weeksNeeded + ' Weeks (~' + Math.round(weeksNeeded/4.3) + ' months)' : 'Goal reached!'"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
