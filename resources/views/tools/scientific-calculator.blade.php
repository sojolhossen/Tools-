<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sciCalc()">
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
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 space-y-4">
                <!-- Screen -->
                <div class="bg-slate-900 text-right p-4 rounded-2xl">
                    <div class="text-xs text-slate-400 font-mono h-5" x-text="expression"></div>
                    <div class="text-3xl font-extrabold font-mono text-white tracking-wider mt-1 truncate" x-text="display"></div>
                </div>

                <!-- Advanced & Standard Grid -->
                <div class="grid grid-cols-5 gap-2 text-xs font-bold">
                    <button type="button" @click="func('sin')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">sin</button>
                    <button type="button" @click="func('cos')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">cos</button>
                    <button type="button" @click="func('tan')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">tan</button>
                    <button type="button" @click="clear()" class="p-3 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100">C</button>
                    <button type="button" @click="backspace()" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">⌫</button>

                    <button type="button" @click="func('ln')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">ln</button>
                    <button type="button" @click="func('log')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">log</button>
                    <button type="button" @click="func('sqrt')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">√</button>
                    <button type="button" @click="input('(')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">(</button>
                    <button type="button" @click="input(')')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">)</button>

                    <button type="button" @click="func('pow2')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">x²</button>
                    <button type="button" @click="input('7')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">7</button>
                    <button type="button" @click="input('8')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">8</button>
                    <button type="button" @click="input('9')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">9</button>
                    <button type="button" @click="input('/')" class="p-3 rounded-xl bg-emerald-500 text-white">÷</button>

                    <button type="button" @click="func('pi')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">π</button>
                    <button type="button" @click="input('4')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">4</button>
                    <button type="button" @click="input('5')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">5</button>
                    <button type="button" @click="input('6')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">6</button>
                    <button type="button" @click="input('*')" class="p-3 rounded-xl bg-emerald-500 text-white">×</button>

                    <button type="button" @click="func('e')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">e</button>
                    <button type="button" @click="input('1')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">1</button>
                    <button type="button" @click="input('2')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">2</button>
                    <button type="button" @click="input('3')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">3</button>
                    <button type="button" @click="input('-')" class="p-3 rounded-xl bg-emerald-500 text-white">−</button>

                    <button type="button" @click="func('fact')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:bg-slate-800">n!</button>
                    <button type="button" @click="input('0')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">0</button>
                    <button type="button" @click="input('.')" class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">.</button>
                    <button type="button" @click="equals()" class="p-3 rounded-xl bg-emerald-600 text-white shadow-md">=</button>
                    <button type="button" @click="input('+')" class="p-3 rounded-xl bg-emerald-500 text-white">+</button>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function sciCalc() {
    return {
        display: '0', expression: '', resetNext: false,
        input(c) {
            if (this.resetNext) { this.display = '0'; this.resetNext = false; }
            if (this.display === '0' && c !== '.') this.display = c;
            else this.display += c;
        },
        clear() { this.display = '0'; this.expression = ''; },
        backspace() {
            if (this.display.length > 1) this.display = this.display.slice(0, -1);
            else this.display = '0';
        },
        func(f) {
            const v = parseFloat(this.display) || 0;
            if (f === 'sin') this.display = String(Math.sin(v * Math.PI / 180).toFixed(6));
            else if (f === 'cos') this.display = String(Math.cos(v * Math.PI / 180).toFixed(6));
            else if (f === 'tan') this.display = String(Math.tan(v * Math.PI / 180).toFixed(6));
            else if (f === 'sqrt') this.display = String(Math.sqrt(v).toFixed(6));
            else if (f === 'pow2') this.display = String(Math.pow(v, 2));
            else if (f === 'ln') this.display = String(Math.log(v).toFixed(6));
            else if (f === 'log') this.display = String(Math.log10(v).toFixed(6));
            else if (f === 'pi') this.display = String(Math.PI);
            else if (f === 'e') this.display = String(Math.E);
            else if (f === 'fact') {
                let n = Math.floor(v), r = 1;
                for (let i = 2; i <= n; i++) r *= i;
                this.display = String(r);
            }
            this.resetNext = true;
        },
        equals() {
            try {
                const expr = this.display.replace(/×/g, '*').replace(/÷/g, '/').replace(/−/g, '-');
                const res = Function('"use strict";return (' + expr + ')')();
                this.expression = this.display + ' =';
                this.display = String(Number(res.toFixed(8)));
                this.resetNext = true;
            } catch (e) {
                this.display = 'Error';
                this.resetNext = true;
            }
        }
    };
}
</script>