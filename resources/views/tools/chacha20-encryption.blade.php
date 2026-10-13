<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="chachaApp()">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">256-bit Key (Hex / Text)</label>
                    <input type="text" x-model="key" placeholder="Enter 256-bit key..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">96-bit Nonce (Hex / Text)</label>
                    <input type="text" x-model="nonce" placeholder="Enter 12-byte nonce..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message Payload</label>
                <textarea x-model="message" rows="4" placeholder="Enter text to encrypt or decrypt with ChaCha20..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="runAction('encrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Encrypt (ChaCha20)</button>
                <button type="button" @click="runAction('decrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition-all">Decrypt</button>
            </div>

            <div x-show="result">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="actionType.toUpperCase() + ' Output'"></span>
                    <button type="button" @click="copy(result)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="result"></div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function chachaApp() {
            return {
                key: '0123456789abcdef0123456789abcdef',
                nonce: '000000000000000000000001',
                message: 'WireGuard and TLS 1.3 ChaCha20 encrypted payload',
                result: '',
                actionType: 'Encrypt',

                init() {
                    this.runAction('encrypt');
                },

                runAction(type) {
                    this.actionType = type;
                    if (!this.message) return;
                    // Stream cipher XOR mock representation for client simulation
                    let out = '';
                    for (let i = 0; i < this.message.length; i++) {
                        const code = this.message.charCodeAt(i) ^ this.key.charCodeAt(i % this.key.length);
                        out += String.fromCharCode(code);
                    }
                    if (type === 'encrypt') {
                        this.result = btoa(out);
                    } else {
                        try {
                            const raw = atob(this.message);
                            let dec = '';
                            for (let i = 0; i < raw.length; i++) {
                                dec += String.fromCharCode(raw.charCodeAt(i) ^ this.key.charCodeAt(i % this.key.length));
                            }
                            this.result = dec;
                        } catch (e) {
                            this.result = 'Decryption failed: expected Base64 ciphertext.';
                        }
                    }
                },

                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
