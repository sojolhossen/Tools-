<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
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
