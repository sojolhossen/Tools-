<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="desApp()">
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Algorithm</label>
                    <select x-model="algo" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="3DES">Triple DES (3DES / TripleDES)</option>
                        <option value="DES">Single DES (Legacy 56-bit)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Key</label>
                    <input type="text" x-model="key" placeholder="Enter DES secret key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input Message</label>
                <textarea x-model="input" rows="4" placeholder="Enter plaintext to encrypt or ciphertext to decrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="runAction('encrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Encrypt</button>
                <button type="button" @click="runAction('decrypt')" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition-all">Decrypt</button>
            </div>

            <div x-show="output">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="lastAction.toUpperCase() + ' Result'"></span>
                    <button type="button" @click="copy(output)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="output"></div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script src="{{ asset('js/vendor/crypto-js.min.js') }}"></script>
    <script>
        if (typeof CryptoJS === 'undefined') {
            const s = document.createElement('script');
            s.src = 'https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js';
            document.head.appendChild(s);
        }
    </script>
    <script>
        function desApp() {
            return {
                algo: '3DES',
                key: '123456789012345678901234',
                input: 'Legacy banking transfer payload 1000 USD',
                output: '',
                lastAction: 'Encrypt',

                init() {
                    this.runAction('encrypt');
                },

                runAction(act) {
                    this.lastAction = act;
                    if (!this.input || !this.key || !window.CryptoJS) return;
                    try {
                        if (act === 'encrypt') {
                            if (this.algo === '3DES') {
                                this.output = CryptoJS.TripleDES.encrypt(this.input, this.key).toString();
                            } else {
                                this.output = CryptoJS.DES.encrypt(this.input, this.key).toString();
                            }
                        } else {
                            let decrypted;
                            if (this.algo === '3DES') {
                                decrypted = CryptoJS.TripleDES.decrypt(this.input, this.key);
                            } else {
                                decrypted = CryptoJS.DES.decrypt(this.input, this.key);
                            }
                            this.output = decrypted.toString(CryptoJS.enc.Utf8) || 'Decryption failed: check key.';
                        }
                    } catch (e) {
                        this.output = 'Error executing ' + act + ': ' + e.message;
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
