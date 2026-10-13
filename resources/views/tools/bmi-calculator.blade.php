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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
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
        unit: 'metric',
        weightKg: 70,
        heightCm: 175,
        weightLb: 154,
        heightFt: 5,
        heightIn: 9,
        bmi: null,
        status: '',
        statusColor: '',
        calculate() {
            let w = 0, h = 0;
            if (this.unit === 'metric') {
                w = parseFloat(this.weightKg) || 0;
                h = (parseFloat(this.heightCm) || 0) / 100;
            } else {
                w = (parseFloat(this.weightLb) || 0) * 0.453592;
                let totalIn = ((parseFloat(this.heightFt) || 0) * 12) + (parseFloat(this.heightIn) || 0);
                h = totalIn * 0.0254;
            }
            if (h > 0 && w > 0) {
                this.bmi = (w / (h * h)).toFixed(1);
                let val = parseFloat(this.bmi);
                if (val < 18.5) { this.status = 'Underweight'; this.statusColor = 'text-blue-500'; }
                else if (val < 25) { this.status = 'Normal weight (Healthy)'; this.statusColor = 'text-emerald-500'; }
                else if (val < 30) { this.status = 'Overweight'; this.statusColor = 'text-amber-500'; }
                else { this.status = 'Obese'; this.statusColor = 'text-rose-500'; }
            } else {
                this.bmi = null;
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-700 rounded-xl">
                <button type="button" @click="unit = 'metric'; calculate()" :class="unit === 'metric' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all">Metric (kg / cm)</button>
                <button type="button" @click="unit = 'imperial'; calculate()" :class="unit === 'imperial' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-sm font-semibold rounded-lg transition-all">Imperial (lbs / ft & in)</button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <template x-if="unit === 'metric'">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Weight (kg)</label>
                        <input type="number" x-model="weightKg" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Height (cm)</label>
                        <input type="number" x-model="heightCm" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </template>
            <template x-if="unit === 'imperial'">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Weight (lbs)</label>
                        <input type="number" x-model="weightLb" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Feet</label>
                            <input type="number" x-model="heightFt" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Inches</label>
                            <input type="number" x-model="heightIn" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </template>

            <div class="flex flex-col justify-center items-center p-6 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-800 text-center">
                <span class="text-sm font-medium text-slate-500">Body Mass Index (BMI)</span>
                <span class="text-5xl font-black text-slate-900 dark:text-white my-2" x-text="bmi || '--'"></span>
                <span class="text-base font-bold" :class="statusColor" x-text="status || 'Enter valid values'"></span>
                <div class="mt-4 text-xs text-slate-400 space-y-1">
                    <div>&lt; 18.5 : Underweight</div>
                    <div>18.5 - 24.9 : Normal (Healthy)</div>
                    <div>25 - 29.9 : Overweight | 30+ : Obese</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
