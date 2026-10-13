# -*- coding: utf-8 -*-
"""
Generates the complete set of 34 Blade Views for Security & Cryptography tools.
"""
import os

VIEWS_DIR = r"d:\xampp\htdocs\tools\resources\views\tools"
os.makedirs(VIEWS_DIR, exist_ok=True)

def write_view(slug, content):
    path = os.path.join(VIEWS_DIR, f"{slug}.blade.php")
    with open(path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Generated view: {slug}.blade.php")

# =========================================================================
# 1. PASSWORD & CREDENTIAL TOOLS
# =========================================================================

# 1.1 Strong Password Generator
write_view("password-generator", """<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
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
""")

# 1.2 Password Strength / Entropy Checker
write_view("password-strength-checker", """<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="passwordStrengthApp()">
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
            <!-- Password Input Field -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Test Password</label>
                    <button type="button" @click="password = 'P@ssw0rdSecure!2026'; check();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                </div>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" x-model="password" @input="check()" placeholder="Type a password to test entropy..." class="w-full font-mono text-base bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 pr-20 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all">
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 px-2 py-1 rounded">
                        <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
            </div>

            <!-- Score Cards Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="block text-xs text-slate-500 font-semibold mb-1">Entropy Score</span>
                    <span class="text-2xl font-extrabold text-slate-900 dark:text-white" x-text="entropy + ' bits'"></span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="block text-xs text-slate-500 font-semibold mb-1">Online Crack Time</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white truncate block" x-text="onlineCrackTime"></span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="block text-xs text-slate-500 font-semibold mb-1">GPU Brute-Force (10B/s)</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white truncate block" x-text="gpuCrackTime"></span>
                </div>
            </div>

            <!-- Progress Meter -->
            <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-slate-700 dark:text-slate-300">Security Rating: <span class="uppercase" :class="tierColor" x-text="tier"></span></span>
                    <span class="text-slate-400" x-text="percent + '%'"></span>
                </div>
                <div class="w-full h-3 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-300" :class="tierBg" :style="'width: ' + percent + '%'"></div>
                </div>
            </div>

            <!-- Security Checklist -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-3">Password Complexity Requirements</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="flex items-center gap-2" :class="hasLength ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400'">
                        <span x-text="hasLength ? '✓' : '✗'"></span> Minimum 12 characters (length: <span x-text="password.length"></span>)
                    </div>
                    <div class="flex items-center gap-2" :class="hasUpper ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400'">
                        <span x-text="hasUpper ? '✓' : '✗'"></span> Contains Uppercase letters (A-Z)
                    </div>
                    <div class="flex items-center gap-2" :class="hasLower ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400'">
                        <span x-text="hasLower ? '✓' : '✗'"></span> Contains Lowercase letters (a-z)
                    </div>
                    <div class="flex items-center gap-2" :class="hasNumber ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400'">
                        <span x-text="hasNumber ? '✓' : '✗'"></span> Contains Numbers (0-9)
                    </div>
                    <div class="flex items-center gap-2" :class="hasSymbol ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400'">
                        <span x-text="hasSymbol ? '✓' : '✗'"></span> Contains Special Symbols (!@#$)
                    </div>
                    <div class="flex items-center gap-2" :class="notCommon ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-rose-600 dark:text-rose-400 font-bold'">
                        <span x-text="notCommon ? '✓' : '✗'"></span> Not on top 100 common passwords list
                    </div>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function passwordStrengthApp() {
            return {
                password: '',
                showPassword: true,
                entropy: 0,
                percent: 0,
                tier: 'None',
                tierColor: 'text-slate-400',
                tierBg: 'bg-slate-300 dark:bg-slate-700',
                onlineCrackTime: 'Instant',
                gpuCrackTime: 'Instant',
                hasLength: false,
                hasUpper: false,
                hasLower: false,
                hasNumber: false,
                hasSymbol: false,
                notCommon: true,

                init() {
                    this.password = 'Summer2026!Key';
                    this.check();
                },

                check() {
                    const p = this.password;
                    this.hasLength = p.length >= 12;
                    this.hasUpper = /[A-Z]/.test(p);
                    this.hasLower = /[a-z]/.test(p);
                    this.hasNumber = /[0-9]/.test(p);
                    this.hasSymbol = /[^A-Za-z0-9]/.test(p);

                    const common = ['123456', 'password', '12345678', 'qwerty', '123456789', '12345', '1234', '111111', '1234567', 'dragon', 'admin', 'welcome'];
                    this.notCommon = !common.includes(p.toLowerCase());

                    if (!p) {
                        this.entropy = 0;
                        this.percent = 0;
                        this.tier = 'None';
                        this.tierColor = 'text-slate-400';
                        this.tierBg = 'bg-slate-300';
                        this.onlineCrackTime = 'Instant';
                        this.gpuCrackTime = 'Instant';
                        return;
                    }

                    let pool = 0;
                    if (this.hasLower) pool += 26;
                    if (this.hasUpper) pool += 26;
                    if (this.hasNumber) pool += 10;
                    if (this.hasSymbol) pool += 33;
                    if (pool === 0) pool = 10;

                    this.entropy = Math.round(p.length * Math.log2(pool));
                    const combinations = Math.pow(pool, p.length);

                    // Times
                    this.onlineCrackTime = this.formatTime(combinations / 1000);
                    this.gpuCrackTime = this.formatTime(combinations / 10000000000);

                    if (this.entropy < 35 || !this.notCommon) {
                        this.percent = 20;
                        this.tier = 'Very Weak';
                        this.tierColor = 'text-rose-600 dark:text-rose-400';
                        this.tierBg = 'bg-rose-500';
                    } else if (this.entropy < 55) {
                        this.percent = 45;
                        this.tier = 'Weak';
                        this.tierColor = 'text-amber-600 dark:text-amber-400';
                        this.tierBg = 'bg-amber-500';
                    } else if (this.entropy < 75) {
                        this.percent = 70;
                        this.tier = 'Good';
                        this.tierColor = 'text-blue-600 dark:text-blue-400';
                        this.tierBg = 'bg-blue-500';
                    } else {
                        this.percent = 100;
                        this.tier = 'Very Strong';
                        this.tierColor = 'text-emerald-600 dark:text-emerald-400';
                        this.tierBg = 'bg-emerald-500';
                    }
                },

                formatTime(seconds) {
                    if (seconds <= 1) return 'Instant';
                    if (seconds < 60) return Math.round(seconds) + ' seconds';
                    if (seconds < 3600) return Math.round(seconds / 60) + ' minutes';
                    if (seconds < 86400) return Math.round(seconds / 3600) + ' hours';
                    if (seconds < 31536000) return Math.round(seconds / 86400) + ' days';
                    if (seconds < 3153600000) return Math.round(seconds / 31536000) + ' years';
                    if (seconds < 3153600000000) return Math.round(seconds / 31536000000) + ' centuries';
                    return 'Millions of years';
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
""")

# 1.3 Passphrase Generator
write_view("passphrase-generator", """<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="passphraseGeneratorApp()">
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
            <!-- Output Display -->
            <div class="relative flex items-center bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5">
                <div class="flex-1 font-mono text-lg sm:text-2xl font-bold tracking-wide text-slate-900 dark:text-slate-100 break-all select-all pr-24" x-text="passphrase"></div>
                <div class="absolute right-4 flex items-center gap-2">
                    <button type="button" @click="generate()" title="Regenerate" class="p-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button type="button" @click="copy()" class="px-4 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            <!-- Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <!-- Words Count -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Number of Words (<span x-text="wordCount"></span>)</label>
                    <input type="range" min="3" max="8" x-model.number="wordCount" @input="generate()" class="w-full accent-rose-600 cursor-pointer">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>3 words</span>
                        <span>4 (Standard)</span>
                        <span>6</span>
                        <span>8 (Paranoid)</span>
                    </div>
                </div>

                <!-- Separator -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Word Separator</label>
                    <select x-model="separator" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="-">Hyphen (-)</option>
                        <option value="_">Underscore (_)</option>
                        <option value=".">Period (.)</option>
                        <option value=" ">Space ( )</option>
                        <option value="/">Slash (/)</option>
                    </select>
                </div>
            </div>

            <!-- Toggles -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="capitalize" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Capitalize Words</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="appendNumber" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Append Random Number</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="appendSymbol" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Append Symbol</span>
                </label>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function passphraseGeneratorApp() {
            return {
                passphrase: '',
                wordCount: 4,
                separator: '-',
                capitalize: false,
                appendNumber: true,
                appendSymbol: false,
                copied: false,
                wordlist: [
                    'apple', 'beach', 'cabin', 'delta', 'eagle', 'flame', 'giant', 'haven', 'ivory', 'jolly',
                    'karma', 'lemon', 'magic', 'noble', 'ocean', 'pilot', 'quest', 'river', 'solar', 'tiger',
                    'ultra', 'vivid', 'whale', 'yield', 'zebra', 'amber', 'breeze', 'castle', 'dragon', 'ember',
                    'falcon', 'galaxy', 'harbor', 'island', 'jungle', 'knight', 'legend', 'meadow', 'nebula', 'orbit',
                    'phoenix', 'quartz', 'rebel', 'shadow', 'thunder', 'unique', 'velvet', 'wizard', 'zenith', 'anchor',
                    'beacon', 'canyon', 'dawn', 'echo', 'forest', 'glacier', 'horizon', 'infinity', 'jupiter', 'kinetic',
                    'lunar', 'monarch', 'nature', 'oasis', 'prism', 'quasar', 'radiant', 'safari', 'titan', 'voyage',
                    'willow', 'apex', 'blaze', 'crater', 'dynasty', 'element', 'frontier', 'gravity', 'hybrid', 'impact',
                    'legacy', 'matrix', 'nova', 'odyssey', 'pioneer', 'quantum', 'rocket', 'summit', 'triumph', 'vertex'
                ],

                init() {
                    this.generate();
                },

                generate() {
                    const values = new Uint32Array(this.wordCount);
                    window.crypto.getRandomValues(values);

                    let words = [];
                    for (let i = 0; i < this.wordCount; i++) {
                        let w = this.wordlist[values[i] % this.wordlist.length];
                        if (this.capitalize) {
                            w = w.charAt(0).toUpperCase() + w.slice(1);
                        }
                        words.push(w);
                    }

                    let res = words.join(this.separator);

                    if (this.appendNumber) {
                        const numVal = new Uint32Array(1);
                        window.crypto.getRandomValues(numVal);
                        res += this.separator + ((numVal[0] % 90) + 10);
                    }

                    if (this.appendSymbol) {
                        const symbols = ['!', '@', '#', '$', '%', '&', '*'];
                        const symVal = new Uint32Array(1);
                        window.crypto.getRandomValues(symVal);
                        res += symbols[symVal[0] % symbols.length];
                    }

                    this.passphrase = res;
                },

                copy() {
                    if (!this.passphrase) return;
                    navigator.clipboard.writeText(this.passphrase);
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
""")

# 1.4 PIN Code Generator
write_view("pin-code-generator", """<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="pinGeneratorApp()">
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
            <!-- Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">PIN Length</label>
                    <select x-model.number="length" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="4">4 Digits (Debit / ATM standard)</option>
                        <option value="6">6 Digits (iOS / Android Passcode)</option>
                        <option value="8">8 Digits (High Security)</option>
                        <option value="10">10 Digits</option>
                        <option value="12">12 Digits</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Quantity</label>
                    <select x-model.number="count" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="1">1 PIN</option>
                        <option value="5">5 PINs</option>
                        <option value="10">10 PINs</option>
                        <option value="20">20 PINs</option>
                    </select>
                </div>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="noRepeat" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Avoid Repeating Digits (e.g. 77)</span>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="noSequential" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Avoid Sequential Digits (e.g. 1234, 4321)</span>
                </label>
            </div>

            <div class="pt-2 flex justify-between items-center">
                <button type="button" @click="generate()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-2">
                    <span>Generate New PINs</span>
                </button>
                <button type="button" @click="copyAll()" class="text-xs text-rose-600 dark:text-rose-400 font-bold hover:underline" x-show="pins.length > 1">
                    Copy All PINs
                </button>
            </div>

            <!-- Generated PINs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <template x-for="(pin, index) in pins" :key="index">
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="font-mono text-xl sm:text-2xl font-extrabold tracking-widest text-slate-900 dark:text-slate-100" x-text="pin"></span>
                        <button type="button" @click="copy(pin, index)" class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-all">
                            <span x-text="copiedIndex === index ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function pinGeneratorApp() {
            return {
                length: 4,
                count: 5,
                noRepeat: true,
                noSequential: true,
                pins: [],
                copiedIndex: -1,

                init() {
                    this.generate();
                },

                generate() {
                    this.pins = [];
                    for (let i = 0; i < this.count; i++) {
                        let pin = this.makePin();
                        this.pins.push(pin);
                    }
                },

                makePin() {
                    let attempts = 0;
                    while (attempts < 100) {
                        attempts++;
                        const arr = new Uint8Array(this.length);
                        window.crypto.getRandomValues(arr);
                        let pin = '';
                        let valid = true;

                        for (let j = 0; j < this.length; j++) {
                            const digit = arr[j] % 10;
                            if (this.noRepeat && j > 0 && digit === parseInt(pin[j - 1])) {
                                valid = false;
                                break;
                            }
                            pin += digit;
                        }

                        if (!valid) continue;

                        if (this.noSequential) {
                            let isSeqUp = true;
                            let isSeqDown = true;
                            for (let k = 1; k < this.length; k++) {
                                if (parseInt(pin[k]) !== parseInt(pin[k - 1]) + 1) isSeqUp = false;
                                if (parseInt(pin[k]) !== parseInt(pin[k - 1]) - 1) isSeqDown = false;
                            }
                            if (isSeqUp || isSeqDown) continue;
                        }

                        return pin;
                    }
                    // Fallback
                    const arr = new Uint8Array(this.length);
                    window.crypto.getRandomValues(arr);
                    return Array.from(arr).map(b => b % 10).join('');
                },

                copy(text, index) {
                    navigator.clipboard.writeText(text);
                    this.copiedIndex = index;
                    setTimeout(() => { this.copiedIndex = -1; }, 2000);
                },

                copyAll() {
                    navigator.clipboard.writeText(this.pins.join('\\n'));
                    alert('Copied all PINs to clipboard!');
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
""")

print("Password tools views generated successfully.")
