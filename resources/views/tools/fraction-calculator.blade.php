<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="fractionCalc()">
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
                <!-- Fraction 1 & Fraction 2 inputs -->
                <div class="flex items-center justify-center gap-4">
                    <!-- F1 -->
                    <div class="flex flex-col items-center w-20">
                        <input type="number" x-model.number="n1" @input="calc()" class="w-full text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950 text-sm font-bold">
                        <div class="w-full h-0.5 bg-slate-800 dark:bg-slate-200 my-1"></div>
                        <input type="number" x-model.number="d1" @input="calc()" class="w-full text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950 text-sm font-bold">
                    </div>

                    <!-- Operator -->
                    <select x-model="op" @change="calc()" class="p-2 border rounded-xl bg-slate-50 dark:bg-slate-950 text-lg font-bold">
                        <option value="+">+</option>
                        <option value="-">−</option>
                        <option value="*">×</option>
                        <option value="/">÷</option>
                    </select>

                    <!-- F2 -->
                    <div class="flex flex-col items-center w-20">
                        <input type="number" x-model.number="n2" @input="calc()" class="w-full text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950 text-sm font-bold">
                        <div class="w-full h-0.5 bg-slate-800 dark:bg-slate-200 my-1"></div>
                        <input type="number" x-model.number="d2" @input="calc()" class="w-full text-center p-2 border rounded-lg bg-slate-50 dark:bg-slate-950 text-sm font-bold">
                    </div>
                </div>

                <!-- Result Box -->
                <div class="p-6 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl text-center border border-emerald-100 dark:border-emerald-800">
                    <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-2">Simplified Result</span>
                    <div class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 font-mono" x-text="resultStr"></div>
                    <span class="text-xs text-slate-500 mt-2 block" x-text="'Decimal: ' + decimalVal"></span>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function fractionCalc() {
    return {
        n1: 1, d1: 2, op: '+', n2: 1, d2: 4, resultStr: '3 / 4', decimalVal: '0.75',
        init() { this.calc(); },
        gcd(a, b) { return b === 0 ? a : this.gcd(b, a % b); },
        calc() {
            if (!this.d1 || !this.d2) return;
            let num = 0, den = 1;
            if (this.op === '+') { num = this.n1 * this.d2 + this.n2 * this.d1; den = this.d1 * this.d2; }
            else if (this.op === '-') { num = this.n1 * this.d2 - this.n2 * this.d1; den = this.d1 * this.d2; }
            else if (this.op === '*') { num = this.n1 * this.n2; den = this.d1 * this.d2; }
            else if (this.op === '/') { num = this.n1 * this.d2; den = this.d1 * this.n2; }

            const common = Math.abs(this.gcd(num, den));
            const simpNum = num / common;
            const simpDen = den / common;
            this.resultStr = simpDen === 1 ? String(simpNum) : `${simpNum} / ${simpDen}`;
            this.decimalVal = (num / den).toFixed(4);
        }
    };
}
</script>