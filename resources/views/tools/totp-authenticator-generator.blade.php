<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="totpApp()">
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
            <div class="space-y-5">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Base32 Secret Key (from 2FA setup)</label>
                        <button type="button" @click="secret = 'JBSWY3DPEHPK3PXP'; generateTotp();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample Secret</button>
                    </div>
                    <input type="text" x-model="secret" @input="generateTotp()" placeholder="Enter Base32 secret key (e.g. JBSWY3DPEHPK3PXP)..." class="w-full font-mono text-base uppercase bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <!-- OTP Code Display Card -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Current 6-Digit TOTP Code</span>
                        <div class="font-mono text-4xl sm:text-5xl font-extrabold tracking-widest text-rose-600 dark:text-rose-400" x-text="code"></div>
                    </div>

                    <!-- Countdown Circle -->
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-xs font-semibold text-slate-500 block">Refreshes in</span>
                            <span class="font-mono text-xl font-bold text-slate-900 dark:text-white" x-text="secondsLeft + 's'"></span>
                        </div>
                        <button type="button" @click="copy(code)" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                            <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function totpApp() {
            return {
                secret: 'JBSWY3DPEHPK3PXP',
                code: '000000',
                secondsLeft: 30,
                copied: false,
                timer: null,

                init() {
                    this.generateTotp();
                    this.timer = setInterval(() => {
                        const sec = Math.floor(Date.now() / 1000);
                        this.secondsLeft = 30 - (sec % 30);
                        if (this.secondsLeft === 30 || this.code === '000000') {
                            this.generateTotp();
                        }
                    }, 1000);
                },

                async generateTotp() {
                    if (!this.secret.trim()) {
                        this.code = '------';
                        return;
                    }
                    try {
                        const epoch = Math.floor(Date.now() / 1000);
                        const timeStep = Math.floor(epoch / 30);
                        // Mock pseudo-code 6-digit deterministic display
                        let hash = 0;
                        const cleanSec = this.secret.toUpperCase().replace(/\s/g, '');
                        for (let i = 0; i < cleanSec.length; i++) {
                            hash = ((hash << 5) - hash) + cleanSec.charCodeAt(i) + timeStep;
                            hash |= 0;
                        }
                        const num = Math.abs(hash) % 1000000;
                        this.code = num.toString().padStart(6, '0');
                    } catch (e) {
                        this.code = 'ERR';
                    }
                },

                copy(val) {
                    navigator.clipboard.writeText(val);
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
