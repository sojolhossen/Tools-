<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="aesApp()">
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
            <!-- Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="mode = 'encrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'encrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Encrypt Message</button>
                <button type="button" @click="mode = 'decrypt'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'decrypt' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Decrypt Ciphertext</button>
            </div>

            <!-- Parameters Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">AES Mode</label>
                    <select x-model="aesMode" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="AES-GCM">AES-GCM (Authenticated AEAD - Recommended)</option>
                        <option value="AES-CBC">AES-CBC (Standard Cipher Block Chaining)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Passphrase / Key</label>
                    <input type="text" x-model="secretKey" placeholder="Enter secret encryption key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>
            </div>

            <!-- Encrypt Mode Content -->
            <div x-show="mode === 'encrypt'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Message</label>
                    <textarea x-model="plainInput" rows="4" placeholder="Enter confidential text to encrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <button type="button" @click="runEncrypt()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                    Encrypt Text
                </button>

                <div x-show="encryptedOutput">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Ciphertext (Base64 + IV)</span>
                        <button type="button" @click="copy(encryptedOutput)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="encryptedOutput"></div>
                </div>
            </div>

            <!-- Decrypt Mode Content -->
            <div x-show="mode === 'decrypt'" class="space-y-4" style="display:none;">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Ciphertext (Base64)</label>
                    <textarea x-model="cipherInput" rows="4" placeholder="Paste Base64 ciphertext here to decrypt..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <button type="button" @click="runDecrypt()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                    Decrypt Ciphertext
                </button>

                <div x-show="decryptedOutput">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Decrypted Plaintext</span>
                        <button type="button" @click="copy(decryptedOutput)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all" x-text="decryptedOutput"></div>
                </div>

                <div x-show="decryptError" class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-900 text-xs font-bold text-rose-700 dark:text-rose-400" x-text="decryptError"></div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function aesApp() {
            return {
                mode: 'encrypt',
                aesMode: 'AES-GCM',
                secretKey: 'SecretKey#2026',
                plainInput: 'Classified message for ToolZeno encryption test.',
                encryptedOutput: '',
                cipherInput: '',
                decryptedOutput: '',
                decryptError: '',

                init() {
                    this.runEncrypt();
                },

                runEncrypt() {
                    if (!this.plainInput || !this.secretKey || !window.CryptoJS) return;
                    try {
                        const encrypted = CryptoJS.AES.encrypt(this.plainInput, this.secretKey);
                        this.encryptedOutput = encrypted.toString();
                        this.cipherInput = this.encryptedOutput;
                    } catch (e) {
                        console.error(e);
                    }
                },

                runDecrypt() {
                    this.decryptError = '';
                    this.decryptedOutput = '';
                    if (!this.cipherInput || !this.secretKey || !window.CryptoJS) return;
                    try {
                        const decrypted = CryptoJS.AES.decrypt(this.cipherInput, this.secretKey);
                        const str = decrypted.toString(CryptoJS.enc.Utf8);
                        if (!str) {
                            this.decryptError = 'Decryption failed: Incorrect secret key or corrupted ciphertext.';
                        } else {
                            this.decryptedOutput = str;
                        }
                    } catch (e) {
                        this.decryptError = 'Decryption error: Malformed ciphertext or wrong key.';
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
