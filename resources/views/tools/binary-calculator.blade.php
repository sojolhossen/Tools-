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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
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
        val1: '1010',
        val2: '1100',
        op: '+',
        base: 'bin',
        resBin: '',
        resDec: '',
        resHex: '',
        resOct: '',
        calculate() {
            let radix = this.base === 'bin' ? 2 : (this.base === 'hex' ? 16 : (this.base === 'oct' ? 8 : 10));
            let n1 = parseInt(this.val1, radix) || 0;
            let n2 = parseInt(this.val2, radix) || 0;
            let ans = 0;

            if (this.op === '+') ans = n1 + n2;
            else if (this.op === '-') ans = n1 - n2;
            else if (this.op === '*') ans = n1 * n2;
            else if (this.op === '/') ans = n2 !== 0 ? Math.floor(n1 / n2) : 0;

            this.resDec = ans.toString(10);
            this.resBin = (ans >= 0 ? ans.toString(2) : '-' + Math.abs(ans).toString(2));
            this.resHex = (ans >= 0 ? ans.toString(16).toUpperCase() : '-' + Math.abs(ans).toString(16).toUpperCase());
            this.resOct = (ans >= 0 ? ans.toString(8) : '-' + Math.abs(ans).toString(8));
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="flex justify-center mb-6">
            <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-700 rounded-xl">
                <button type="button" @click="base = 'bin'; calculate()" :class="base === 'bin' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">Binary (Base 2)</button>
                <button type="button" @click="base = 'dec'; calculate()" :class="base === 'dec' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">Decimal (Base 10)</button>
                <button type="button" @click="base = 'hex'; calculate()" :class="base === 'hex' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">Hex (Base 16)</button>
                <button type="button" @click="base = 'oct'; calculate()" :class="base === 'oct' ? 'bg-white dark:bg-slate-800 text-indigo-600 shadow-sm' : 'text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 text-xs font-bold rounded-lg transition-all">Octal (Base 8)</button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
            <input type="text" x-model="val1" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono text-center">
            <div class="flex justify-center">
                <select x-model="op" @change="calculate()" class="px-6 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-bold text-center">
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">×</option>
                    <option value="/">÷</option>
                </select>
            </div>
            <input type="text" x-model="val2" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono text-center">
        </div>

        <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-bold uppercase">Binary</span>
                <div class="text-lg font-mono font-black text-indigo-600 dark:text-indigo-400 mt-1 break-all" x-text="resBin"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-bold uppercase">Decimal</span>
                <div class="text-lg font-mono font-black text-emerald-600 dark:text-emerald-400 mt-1 break-all" x-text="resDec"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-bold uppercase">Hexadecimal</span>
                <div class="text-lg font-mono font-black text-purple-600 dark:text-purple-400 mt-1 break-all" x-text="resHex"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-bold uppercase">Octal</span>
                <div class="text-lg font-mono font-black text-amber-600 dark:text-amber-400 mt-1 break-all" x-text="resOct"></div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
