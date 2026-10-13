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
        gender: 'male',
        heightCm: 175,
        devine: 0,
        robinson: 0,
        miller: 0,
        hamwi: 0,
        calculate() {
            let hCm = parseFloat(this.heightCm) || 0;
            let inchesOver5Ft = (hCm / 2.54) - 60;
            if (inchesOver5Ft >= 0) {
                if (this.gender === 'male') {
                    this.devine = Math.round(50.0 + (2.3 * inchesOver5Ft));
                    this.robinson = Math.round(52.0 + (1.9 * inchesOver5Ft));
                    this.miller = Math.round(56.2 + (1.41 * inchesOver5Ft));
                    this.hamwi = Math.round(48.0 + (2.7 * inchesOver5Ft));
                } else {
                    this.devine = Math.round(45.5 + (2.3 * inchesOver5Ft));
                    this.robinson = Math.round(49.0 + (1.7 * inchesOver5Ft));
                    this.miller = Math.round(53.1 + (1.36 * inchesOver5Ft));
                    this.hamwi = Math.round(45.5 + (2.2 * inchesOver5Ft));
                }
            } else {
                this.devine = 0; this.robinson = 0; this.miller = 0; this.hamwi = 0;
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
                    <input type="number" x-model="heightCm" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <span class="text-xs text-slate-400 mt-1 block" x-text="heightCm ? (Math.floor((heightCm/2.54)/12) + ' ft ' + Math.round((heightCm/2.54)%12) + ' in') : ''"></span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-slate-200">Devine Formula</div>
                        <div class="text-xs text-slate-500">Standard medical guideline</div>
                    </div>
                    <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400" x-text="devine ? devine + ' kg' : '--'"></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-slate-200">Robinson Formula</div>
                        <div class="text-xs text-slate-500">Popular modern estimate</div>
                    </div>
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400" x-text="robinson ? robinson + ' kg' : '--'"></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-slate-200">Miller Formula</div>
                        <div class="text-xs text-slate-500">Conservative estimate</div>
                    </div>
                    <div class="text-2xl font-black text-purple-600 dark:text-purple-400" x-text="miller ? miller + ' kg' : '--'"></div>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 flex justify-between items-center border border-slate-200 dark:border-slate-700">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-slate-200">Hamwi Formula</div>
                        <div class="text-xs text-slate-500">Diabetic & clinical rule</div>
                    </div>
                    <div class="text-2xl font-black text-amber-600 dark:text-amber-400" x-text="hamwi ? hamwi + ' kg' : '--'"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
