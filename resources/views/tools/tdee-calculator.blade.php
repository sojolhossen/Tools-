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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
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
        gender: 'male',
        age: 26,
        weight: 72,
        height: 176,
        activity: '1.375',
        tdee: 0,
        bmr: 0,
        calculate() {
            let w = parseFloat(this.weight) || 0;
            let h = parseFloat(this.height) || 0;
            let a = parseFloat(this.age) || 0;
            let act = parseFloat(this.activity) || 1.2;
            if (w > 0 && h > 0 && a > 0) {
                let b = (10 * w) + (6.25 * h) - (5 * a) + (this.gender === 'male' ? 5 : -161);
                this.bmr = Math.round(b);
                this.tdee = Math.round(b * act);
            } else {
                this.bmr = 0;
                this.tdee = 0;
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="gender = 'male'; calculate()" :class="gender === 'male' ? 'bg-indigo-600 text-white font-semibold' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="py-2.5 rounded-xl transition">Male</button>
                    <button type="button" @click="gender = 'female'; calculate()" :class="gender === 'female' ? 'bg-pink-600 text-white font-semibold' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="py-2.5 rounded-xl transition">Female</button>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Age</label>
                        <input type="number" x-model="age" @input="calculate()" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Weight (kg)</label>
                        <input type="number" x-model="weight" @input="calculate()" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Height (cm)</label>
                        <input type="number" x-model="height" @input="calculate()" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Activity Level</label>
                    <select x-model="activity" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="1.2">Sedentary (desk job, little or no exercise)</option>
                        <option value="1.375">Lightly Active (light exercise 1-3 days/week)</option>
                        <option value="1.55">Moderately Active (moderate exercise 3-5 days/week)</option>
                        <option value="1.725">Very Active (hard exercise 6-7 days/week)</option>
                        <option value="1.9">Extremely Active (hard daily training / physical job)</option>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div class="p-6 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-center shadow-lg">
                    <span class="text-xs uppercase tracking-widest text-indigo-100 font-semibold">Total Daily Energy Expenditure</span>
                    <div class="text-5xl font-black my-2" x-text="tdee ? tdee.toLocaleString() : '--'"></div>
                    <span class="text-sm font-medium text-indigo-100">Calories / Day to Maintain Weight</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                        <span class="text-xs text-slate-500">Mild Weight Loss (-0.25kg/wk)</span>
                        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1" x-text="tdee ? (tdee - 250).toLocaleString() + ' kcal' : '--'"></div>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                        <span class="text-xs text-slate-500">Weight Loss (-0.5kg/wk)</span>
                        <div class="text-lg font-bold text-amber-600 dark:text-amber-400 mt-1" x-text="tdee ? (tdee - 500).toLocaleString() + ' kcal' : '--'"></div>
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
