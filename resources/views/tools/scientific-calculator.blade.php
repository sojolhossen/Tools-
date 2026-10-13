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
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 space-y-4" @keydown.window="handleKey($event)">
                <!-- Screen with DEG/RAD toggle & History -->
                <div class="bg-slate-950 text-right p-4 rounded-2xl border border-slate-800 shadow-inner">
                    <div class="flex items-center justify-between text-xs text-slate-400 font-mono mb-1">
                        <button
                            type="button"
                            @click="toggleAngleMode()"
                            class="px-2.5 py-1 text-xs font-bold rounded-lg transition-all focus:outline-none"
                            :class="angleMode === 'deg' ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'"
                        >
                            <span x-text="angleMode === 'deg' ? 'DEG (Degrees)' : 'RAD (Radians)'">DEG (Degrees)</span>
                        </button>
                        <div class="text-xs text-slate-400 font-mono h-5 truncate ml-2" x-text="expression"></div>
                    </div>
                    <div class="text-3xl sm:text-4xl font-extrabold font-mono text-white tracking-wider mt-1 truncate" x-text="display">0</div>
                </div>

                <!-- Advanced & Standard Grid -->
                <div class="grid grid-cols-5 gap-2 text-xs font-bold">
                    <!-- Row 1 -->
                    <button type="button" @click="func('sin')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">sin</button>
                    <button type="button" @click="func('cos')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">cos</button>
                    <button type="button" @click="func('tan')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">tan</button>
                    <button type="button" @click="clear()" class="p-3 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400 dark:hover:bg-rose-900/60 transition-colors font-extrabold">C</button>
                    <button type="button" @click="backspace()" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">⌫</button>

                    <!-- Row 2 -->
                    <button type="button" @click="func('ln')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">ln</button>
                    <button type="button" @click="func('log')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">log</button>
                    <button type="button" @click="func('sqrt')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">√</button>
                    <button type="button" @click="input('(')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">(</button>
                    <button type="button" @click="input(')')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">)</button>

                    <!-- Row 3 -->
                    <button type="button" @click="func('pow2')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">x²</button>
                    <button type="button" @click="input('7')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">7</button>
                    <button type="button" @click="input('8')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">8</button>
                    <button type="button" @click="input('9')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">9</button>
                    <button type="button" @click="input('÷')" class="p-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors text-sm font-black">÷</button>

                    <!-- Row 4 -->
                    <button type="button" @click="input('π')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">π</button>
                    <button type="button" @click="input('4')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">4</button>
                    <button type="button" @click="input('5')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">5</button>
                    <button type="button" @click="input('6')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">6</button>
                    <button type="button" @click="input('×')" class="p-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors text-sm font-black">×</button>

                    <!-- Row 5 -->
                    <button type="button" @click="input('e')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">e</button>
                    <button type="button" @click="input('1')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">1</button>
                    <button type="button" @click="input('2')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">2</button>
                    <button type="button" @click="input('3')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">3</button>
                    <button type="button" @click="input('−')" class="p-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors text-sm font-black">−</button>

                    <!-- Row 6 -->
                    <button type="button" @click="func('fact')" class="p-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 transition-colors">n!</button>
                    <button type="button" @click="input('0')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">0</button>
                    <button type="button" @click="input('.')" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 dark:text-white transition-colors text-sm">.</button>
                    <button type="button" @click="equals()" class="p-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-colors text-base font-black">=</button>
                    <button type="button" @click="input('+')" class="p-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white transition-colors text-sm font-black">+</button>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</x-layouts.app>
<script>
function sciCalc() {
    return {
        display: '0',
        expression: '',
        angleMode: 'deg', // 'deg' or 'rad'
        resetNext: false,

        toggleAngleMode() {
            this.angleMode = this.angleMode === 'deg' ? 'rad' : 'deg';
        },

        input(c) {
            if (this.resetNext && !['+', '−', '×', '÷', '^'].includes(c)) {
                this.display = '0';
                this.resetNext = false;
            } else if (this.resetNext) {
                this.resetNext = false;
            }

            if (this.display === '0' && c !== '.' && !['+', '−', '×', '÷', '^', ')'].includes(c)) {
                this.display = c;
            } else {
                this.display += c;
            }
        },

        clear() {
            this.display = '0';
            this.expression = '';
            this.resetNext = false;
        },

        backspace() {
            if (this.resetNext) {
                this.clear();
                return;
            }
            if (this.display.length > 1) {
                this.display = this.display.slice(0, -1);
            } else {
                this.display = '0';
            }
        },

        func(f) {
            // If display is '0' or resetNext is true, start fresh with function call
            if (this.display === '0' || this.resetNext) {
                this.resetNext = false;
                if (f === 'sin') this.display = 'sin(';
                else if (f === 'cos') this.display = 'cos(';
                else if (f === 'tan') this.display = 'tan(';
                else if (f === 'ln') this.display = 'ln(';
                else if (f === 'log') this.display = 'log(';
                else if (f === 'sqrt') this.display = '√(';
                else if (f === 'pow2') this.display = '0^2';
                else if (f === 'fact') this.display = '0!';
                return;
            }

            // If user already entered a standalone number (e.g. 30) and clicked sin:
            // We wrap it as sin(30) so they immediately see the intent!
            if (/^-?\d+(\.\d+)?$/.test(this.display)) {
                if (f === 'sin') this.display = 'sin(' + this.display + ')';
                else if (f === 'cos') this.display = 'cos(' + this.display + ')';
                else if (f === 'tan') this.display = 'tan(' + this.display + ')';
                else if (f === 'ln') this.display = 'ln(' + this.display + ')';
                else if (f === 'log') this.display = 'log(' + this.display + ')';
                else if (f === 'sqrt') this.display = '√(' + this.display + ')';
                else if (f === 'pow2') this.display = this.display + '^2';
                else if (f === 'fact') this.display = this.display + '!';
                return;
            }

            // Otherwise append function
            if (f === 'sin') this.display += 'sin(';
            else if (f === 'cos') this.display += 'cos(';
            else if (f === 'tan') this.display += 'tan(';
            else if (f === 'ln') this.display += 'ln(';
            else if (f === 'log') this.display += 'log(';
            else if (f === 'sqrt') this.display += '√(';
            else if (f === 'pow2') this.display += '^2';
            else if (f === 'fact') this.display += '!';
        },

        equals() {
            try {
                const rawExpr = this.display;
                const result = this.evaluate(rawExpr);
                this.expression = rawExpr + ' =';
                this.display = String(result);
                this.resetNext = true;
            } catch (err) {
                this.expression = this.display;
                this.display = 'Error';
                this.resetNext = true;
            }
        },

        evaluate(expr) {
            let s = expr
                .replace(/×/g, '*')
                .replace(/÷/g, '/')
                .replace(/−/g, '-')
                .replace(/π/g, 'Math.PI')
                .replace(/\be\b/g, 'Math.E');

            // Handle Factorials: e.g. 5! -> factorial(5)
            s = s.replace(/(\d+)!/g, 'factorial($1)');

            // Handle Powers: e.g. 2^3 -> 2**3
            s = s.replace(/\^/g, '**');

            // Handle Roots: √(x) -> Math.sqrt(x)
            s = s.replace(/√\(/g, 'Math.sqrt(');

            // Handle Logarithms:
            s = s.replace(/log\(/g, 'Math.log10(');
            s = s.replace(/ln\(/g, 'Math.log(');

            // Handle Trigonometry:
            s = s.replace(/sin\(/g, '_sin(');
            s = s.replace(/cos\(/g, '_cos(');
            s = s.replace(/tan\(/g, '_tan(');

            // Auto-close open parentheses if missing
            let openParens = (s.match(/\(/g) || []).length;
            let closeParens = (s.match(/\)/g) || []).length;
            while (openParens > closeParens) {
                s += ')';
                closeParens++;
            }

            const angleMode = this.angleMode;
            const toRad = (x) => angleMode === 'deg' ? (x * Math.PI / 180) : x;

            const _sin = (x) => {
                if (angleMode === 'deg') {
                    const norm = ((x % 360) + 360) % 360;
                    if (norm === 0 || norm === 180) return 0;
                    if (norm === 90) return 1;
                    if (norm === 270) return -1;
                    if (norm === 30 || norm === 150) return 0.5;
                    if (norm === 210 || norm === 330) return -0.5;
                }
                return Math.sin(toRad(x));
            };

            const _cos = (x) => {
                if (angleMode === 'deg') {
                    const norm = ((x % 360) + 360) % 360;
                    if (norm === 90 || norm === 270) return 0;
                    if (norm === 0) return 1;
                    if (norm === 180) return -1;
                    if (norm === 60 || norm === 300) return 0.5;
                    if (norm === 120 || norm === 240) return -0.5;
                }
                return Math.cos(toRad(x));
            };

            const _tan = (x) => {
                if (angleMode === 'deg') {
                    const norm = ((x % 360) + 360) % 360;
                    if (norm === 90 || norm === 270) throw new Error('Undefined');
                    if (norm === 0 || norm === 180) return 0;
                    if (norm === 45 || norm === 225) return 1;
                    if (norm === 135 || norm === 315) return -1;
                }
                return Math.tan(toRad(x));
            };

            const factorial = (n) => {
                if (n < 0) throw new Error('Invalid factorial');
                if (n === 0 || n === 1) return 1;
                let res = 1;
                for (let i = 2; i <= Math.min(n, 170); i++) res *= i;
                return res;
            };

            const fn = new Function('_sin', '_cos', '_tan', 'factorial', 'Math', '"use strict"; return (' + s + ');');
            const val = fn(_sin, _cos, _tan, factorial, Math);

            if (typeof val !== 'number' || isNaN(val)) {
                throw new Error('NaN');
            }

            if (Math.abs(val) < 1e-12) return 0;
            return Number(val.toPrecision(12)) / 1;
        },

        handleKey(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            const k = e.key;
            if (k >= '0' && k <= '9') this.input(k);
            else if (k === '.') this.input('.');
            else if (k === '+') this.input('+');
            else if (k === '-') this.input('−');
            else if (k === '*') this.input('×');
            else if (k === '/') { e.preventDefault(); this.input('÷'); }
            else if (k === '(' || k === ')') this.input(k);
            else if (k === 'Enter' || k === '=') { e.preventDefault(); this.equals(); }
            else if (k === 'Backspace') { e.preventDefault(); this.backspace(); }
            else if (k === 'Escape') { e.preventDefault(); this.clear(); }
        }
    };
}
</script>