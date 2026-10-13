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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
        height: 175,
        neck: 38,
        waist: 85,
        hip: 95,
        bodyFat: null,
        category: '',
        calculate() {
            let h = parseFloat(this.height) || 0;
            let n = parseFloat(this.neck) || 0;
            let w = parseFloat(this.waist) || 0;
            let hip = parseFloat(this.hip) || 0;

            if (h > 0 && n > 0 && w > 0) {
                // US Navy Formula
                let bf = 0;
                if (this.gender === 'male') {
                    if (w > n) {
                        bf = 495 / (1.0324 - 0.19077 * Math.log10(w - n) + 0.15456 * Math.log10(h)) - 450;
                    }
                } else {
                    if (w + hip > n) {
                        bf = 495 / (1.29579 - 0.35004 * Math.log10(w + hip - n) + 0.22100 * Math.log10(h)) - 450;
                    }
                }

                if (bf > 0 && bf < 60) {
                    this.bodyFat = bf.toFixed(1);
                    let val = parseFloat(this.bodyFat);
                    if (this.gender === 'male') {
                        if (val < 6) this.category = 'Essential fat';
                        else if (val < 14) this.category = 'Athletes';
                        else if (val < 18) this.category = 'Fitness';
                        else if (val < 25) this.category = 'Average';
                        else this.category = 'Obese';
                    } else {
                        if (val < 14) this.category = 'Essential fat';
                        else if (val < 21) this.category = 'Athletes';
                        else if (val < 25) this.category = 'Fitness';
                        else if (val < 32) this.category = 'Average';
                        else this.category = 'Obese';
                    }
                } else {
                    this.bodyFat = null;
                }
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="gender = 'male'; calculate()" :class="gender === 'male' ? 'bg-indigo-600 text-white font-semibold' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="py-2.5 rounded-xl transition">Male</button>
                    <button type="button" @click="gender = 'female'; calculate()" :class="gender === 'female' ? 'bg-pink-600 text-white font-semibold' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300'" class="py-2.5 rounded-xl transition">Female</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Height (cm)</label>
                    <input type="number" x-model="height" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Neck Circumference (cm)</label>
                    <input type="number" x-model="neck" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Waist Circumference (cm)</label>
                    <input type="number" x-model="waist" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div x-show="gender === 'female'">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Hip Circumference (cm)</label>
                    <input type="number" x-model="hip" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col justify-center items-center p-6 rounded-2xl bg-teal-50 dark:bg-teal-950/20 border border-teal-200 dark:border-teal-900/50 text-center">
                <span class="text-xs uppercase tracking-wider font-semibold text-teal-700 dark:text-teal-400">US Navy Body Fat Estimate</span>
                <span class="text-6xl font-black text-slate-900 dark:text-white my-3" x-text="bodyFat ? bodyFat + '%' : '--'"></span>
                <span class="text-lg font-bold text-teal-700 dark:text-teal-300" x-text="category || 'Enter your measurements'"></span>
                <p class="mt-4 text-xs text-slate-500 max-w-xs">Uses tape measurement circumference method standardized by the US Department of Defense.</p>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
