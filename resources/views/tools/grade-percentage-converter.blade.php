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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
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
        mode: 'pct_to_gpa',
        percentage: 85,
        gpaInput: 3.5,
        scale: '4.0',
        resultGpa: 0,
        resultPct: 0,
        letterGrade: '',
        calculate() {
            let sc = parseFloat(this.scale) || 4.0;
            if (this.mode === 'pct_to_gpa') {
                let p = parseFloat(this.percentage) || 0;
                let g = (p / 100) * sc;
                this.resultGpa = Math.min(sc, Math.max(0, g)).toFixed(2);
                
                if (p >= 90) this.letterGrade = 'A+ / A';
                else if (p >= 80) this.letterGrade = 'B+ / A-';
                else if (p >= 70) this.letterGrade = 'B / C+';
                else if (p >= 60) this.letterGrade = 'C / D';
                else this.letterGrade = 'F';
            } else {
                let g = parseFloat(this.gpaInput) || 0;
                let pct = (g / sc) * 100;
                this.resultPct = Math.min(100, Math.max(0, pct)).toFixed(1);
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-700 rounded-xl">
                <button type="button" @click="mode = 'pct_to_gpa'; calculate()" :class="mode === 'pct_to_gpa' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">Percentage to GPA</button>
                <button type="button" @click="mode = 'gpa_to_pct'; calculate()" :class="mode === 'gpa_to_pct' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-5 py-2 text-sm font-semibold rounded-lg transition-all">GPA to Percentage</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">GPA Scale</label>
                    <select x-model="scale" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="4.0">4.0 Scale (US / Most Universities)</option>
                        <option value="5.0">5.0 Scale (High School / Specific Boards)</option>
                        <option value="10.0">10.0 Scale (Indian Universities)</option>
                    </select>
                </div>
                <div x-show="mode === 'pct_to_gpa'">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Percentage (%)</label>
                    <input type="number" step="0.1" x-model="percentage" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div x-show="mode === 'gpa_to_pct'">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">GPA Score</label>
                    <input type="number" step="0.01" x-model="gpaInput" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col justify-center items-center p-6 rounded-2xl bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-900/50 text-center">
                <span class="text-xs uppercase tracking-wider font-semibold text-indigo-700 dark:text-indigo-400" x-text="mode === 'pct_to_gpa' ? 'Converted GPA' : 'Converted Percentage'"></span>
                <span class="text-6xl font-black text-indigo-600 dark:text-indigo-400 my-3" x-text="mode === 'pct_to_gpa' ? resultGpa : resultPct + '%'"></span>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300" x-show="mode === 'pct_to_gpa'" x-text="'Approx. Letter Grade: ' + letterGrade"></span>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
