<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="passwordGeneratorApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 mb-10">
            <!-- Password Display Box -->
            <div class="relative flex items-center bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5">
                <div class="flex-1 font-mono text-lg sm:text-2xl font-bold tracking-wider text-slate-900 dark:text-slate-100 break-all select-all pr-24" x-text="password"></div>
                <div class="absolute right-4 flex items-center gap-2">
                    <button type="button" @click="generate()" title="Generate New" class="p-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button type="button" @click="copy(password)" class="px-4 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            <!-- Strength Meter Bar -->
            <div>
                <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                    <span class="text-slate-600 dark:text-slate-400">Password Strength: <strong class="uppercase" :class="strengthColorText" x-text="strengthLabel"></strong></span>
                    <span class="text-slate-500 font-mono" x-text="entropy + ' bits entropy'"></span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-300" :class="strengthColorBg" :style="'width: ' + strengthPercent + '%'"></div>
                </div>
            </div>

            <!-- Configuration Controls -->
            <div class="space-y-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <!-- Length Slider -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Password Length</label>
                        <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50 rounded-lg font-mono font-bold text-sm" x-text="length"></span>
                    </div>
                    <input type="range" min="6" max="64" x-model.number="length" @input="generate()" class="w-full accent-rose-600 cursor-pointer">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>6</span>
                        <span>16 (Recommended)</span>
                        <span>32</span>
                        <span>64</span>
                    </div>
                </div>

                <!-- Checkbox Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <input type="checkbox" x-model="useUpper" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">Uppercase Letters (A-Z)</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono">ABCDEF...</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <input type="checkbox" x-model="useLower" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">Lowercase Letters (a-z)</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono">abcdef...</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <input type="checkbox" x-model="useNumbers" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">Numbers (0-9)</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono">0123456789</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <input type="checkbox" x-model="useSymbols" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">Special Symbols</span>
                            <span class="text-slate-500 dark:text-slate-400 font-mono">!@#$%^&*()_+-=</span>
                        </div>
                    </label>

                    <label class="sm:col-span-2 flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <input type="checkbox" x-model="excludeAmbiguous" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                        <div class="text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">Exclude Ambiguous Characters</span>
                            <span class="text-slate-500 dark:text-slate-400">Avoid confusing characters like l, 1, I, O, 0</span>
                        </div>
                    </label>
                </div>

                <!-- Bulk Generator Section -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Bulk Passwords (Batch)</span>
                        <div class="flex gap-2">
                            <button type="button" @click="generateBulk(5)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">Generate 5</button>
                            <button type="button" @click="generateBulk(10)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">Generate 10</button>
                        </div>
                    </div>
                    <template x-if="bulkList.length > 0">
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            <template x-for="(p, index) in bulkList" :key="index">
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 font-mono text-xs">
                                    <span class="text-slate-900 dark:text-slate-100 truncate pr-2" x-text="p"></span>
                                    <button type="button" @click="copy(p)" class="text-rose-600 dark:text-rose-400 hover:underline shrink-0">Copy</button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function passwordGeneratorApp() {
            return {
                password: '',
                length: 16,
                useUpper: true,
                useLower: true,
                useNumbers: true,
                useSymbols: true,
                excludeAmbiguous: false,
                copied: false,
                bulkList: [],
                entropy: 0,
                strengthLabel: 'Strong',
                strengthColorText: 'text-emerald-600 dark:text-emerald-400',
                strengthColorBg: 'bg-emerald-500',
                strengthPercent: 80,

                init() {
                    this.generate();
                },

                generate() {
                    let chars = '';
                    if (this.useUpper) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    if (this.useLower) chars += 'abcdefghijklmnopqrstuvwxyz';
                    if (this.useNumbers) chars += '0123456789';
                    if (this.useSymbols) chars += '!@#$%^&*()_+-=[]{}|;:,.<>?';

                    if (this.excludeAmbiguous) {
                        chars = chars.replace(/[l1IO0]/g, '');
                    }

                    if (!chars) {
                        chars = 'abcdefghijklmnopqrstuvwxyz';
                        this.useLower = true;
                    }

                    this.password = this.createRandomString(chars, this.length);
                    this.calculateEntropy(chars.length);
                },

                createRandomString(charset, len) {
                    const values = new Uint32Array(len);
                    window.crypto.getRandomValues(values);
                    let res = '';
                    for (let i = 0; i < len; i++) {
                        res += charset[values[i] % charset.length];
                    }
                    return res;
                },

                generateBulk(count) {
                    let chars = '';
                    if (this.useUpper) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    if (this.useLower) chars += 'abcdefghijklmnopqrstuvwxyz';
                    if (this.useNumbers) chars += '0123456789';
                    if (this.useSymbols) chars += '!@#$%^&*()_+-=[]{}|;:,.<>?';
                    if (this.excludeAmbiguous) chars = chars.replace(/[l1IO0]/g, '');
                    if (!chars) chars = 'abcdefghijklmnopqrstuvwxyz';

                    this.bulkList = [];
                    for (let i = 0; i < count; i++) {
                        this.bulkList.push(this.createRandomString(chars, this.length));
                    }
                },

                calculateEntropy(poolSize) {
                    this.entropy = Math.round(this.length * Math.log2(poolSize));
                    if (this.entropy < 40) {
                        this.strengthLabel = 'Very Weak';
                        this.strengthColorText = 'text-rose-600 dark:text-rose-400';
                        this.strengthColorBg = 'bg-rose-500';
                        this.strengthPercent = 25;
                    } else if (this.entropy < 60) {
                        this.strengthLabel = 'Fair';
                        this.strengthColorText = 'text-amber-600 dark:text-amber-400';
                        this.strengthColorBg = 'bg-amber-500';
                        this.strengthPercent = 50;
                    } else if (this.entropy < 80) {
                        this.strengthLabel = 'Strong';
                        this.strengthColorText = 'text-blue-600 dark:text-blue-400';
                        this.strengthColorBg = 'bg-blue-500';
                        this.strengthPercent = 75;
                    } else {
                        this.strengthLabel = 'Very Strong';
                        this.strengthColorText = 'text-emerald-600 dark:text-emerald-400';
                        this.strengthColorBg = 'bg-emerald-500';
                        this.strengthPercent = 100;
                    }
                },

                copy(text) {
                    if (!text) return;
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
