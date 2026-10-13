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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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
        valA: 25,
        valB: 14,
        resAnd: 0,
        resOr: 0,
        resXor: 0,
        resNotA: 0,
        resLShift: 0,
        resRShift: 0,
        toBin(num) {
            return (num >>> 0).toString(2).padStart(8, '0');
        },
        calculate() {
            let a = parseInt(this.valA) || 0;
            let b = parseInt(this.valB) || 0;
            this.resAnd = a & b;
            this.resOr = a | b;
            this.resXor = a ^ b;
            this.resNotA = ~a;
            this.resLShift = a << 1;
            this.resRShift = a >> 1;
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Operand A (Integer)</label>
                <input type="number" x-model="valA" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono">
                <span class="text-xs text-slate-500 font-mono mt-1 block" x-text="'Binary: ' + toBin(valA)"></span>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Operand B (Integer)</label>
                <input type="number" x-model="valB" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono">
                <span class="text-xs text-slate-500 font-mono mt-1 block" x-text="'Binary: ' + toBin(valB)"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-mono text-sm">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">A AND B (&)</span>
                <div class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1" x-text="resAnd"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resAnd)"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">A OR B (|)</span>
                <div class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1" x-text="resOr"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resOr)"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">A XOR B (^)</span>
                <div class="text-xl font-bold text-purple-600 dark:text-purple-400 mt-1" x-text="resXor"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resXor)"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">NOT A (~)</span>
                <div class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-1" x-text="resNotA"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resNotA)"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">A Left Shift (A &lt;&lt; 1)</span>
                <div class="text-xl font-bold text-cyan-600 dark:text-cyan-400 mt-1" x-text="resLShift"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resLShift)"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">A Right Shift (A &gt;&gt; 1)</span>
                <div class="text-xl font-bold text-rose-600 dark:text-rose-400 mt-1" x-text="resRShift"></div>
                <div class="text-xs text-slate-400 mt-0.5" x-text="toBin(resRShift)"></div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
