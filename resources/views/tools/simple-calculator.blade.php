<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="simpleCalc()">
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

        <div class="max-w-md mx-auto mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 space-y-4">
                <!-- Screen -->
                <div class="bg-slate-900 text-right p-4 rounded-2xl">
                    <div class="text-xs text-slate-400 font-mono h-5 overflow-hidden" x-text="expression"></div>
                    <div class="text-3xl font-extrabold font-mono text-white tracking-wider mt-1 truncate" x-text="display"></div>
                </div>

                <!-- Keypad -->
                <div class="grid grid-cols-4 gap-2.5 text-sm font-bold">
                    <button type="button" @click="clear()" class="p-3.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100">C</button>
                    <button type="button" @click="backspace()" class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">⌫</button>
                    <button type="button" @click="input('%')" class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200">%</button>
                    <button type="button" @click="op('/')" class="p-3.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600">÷</button>

                    <button type="button" @click="input('7')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">7</button>
                    <button type="button" @click="input('8')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">8</button>
                    <button type="button" @click="input('9')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">9</button>
                    <button type="button" @click="op('*')" class="p-3.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600">×</button>

                    <button type="button" @click="input('4')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">4</button>
                    <button type="button" @click="input('5')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">5</button>
                    <button type="button" @click="input('6')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">6</button>
                    <button type="button" @click="op('-')" class="p-3.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600">−</button>

                    <button type="button" @click="input('1')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">1</button>
                    <button type="button" @click="input('2')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">2</button>
                    <button type="button" @click="input('3')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">3</button>
                    <button type="button" @click="op('+')" class="p-3.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600">+</button>

                    <button type="button" @click="input('0')" class="col-span-2 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100 text-left pl-6">0</button>
                    <button type="button" @click="input('.')" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-slate-100">.</button>
                    <button type="button" @click="equals()" class="p-3.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-md">=</button>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="scientific-calculator" />
    </div>
</x-layouts.app>
<script>
function simpleCalc() {
    return {
        display: '0', expression: '', currentVal: '', resetNext: false,
        input(char) {
            if (this.resetNext) { this.display = '0'; this.resetNext = false; }
            if (this.display === '0' && char !== '.') this.display = char;
            else this.display += char;
        },
        op(o) {
            this.expression = this.display + ' ' + o;
            this.resetNext = true;
        },
        clear() { this.display = '0'; this.expression = ''; },
        backspace() {
            if (this.display.length > 1) this.display = this.display.slice(0, -1);
            else this.display = '0';
        },
        equals() {
            try {
                const expr = this.expression + ' ' + this.display;
                const sanitized = expr.replace(/×/g, '*').replace(/÷/g, '/').replace(/−/g, '-');
                const res = Function('"use strict";return (' + sanitized + ')')();
                this.expression = expr + ' =';
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