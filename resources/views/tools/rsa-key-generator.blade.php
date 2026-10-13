<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="rsaKeyApp()">
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
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Key Modulus Size:</label>
                    <select x-model.number="keySize" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                        <option value="2048">2048-bit (Standard / Fast)</option>
                        <option value="3072">3072-bit (NIST Recommended)</option>
                        <option value="4096">4096-bit (Ultra High Security)</option>
                    </select>
                </div>

                <button type="button" @click="generateKeys()" :disabled="loading" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all disabled:opacity-50 flex items-center gap-2">
                    <span x-text="loading ? 'Generating RSA Prime Numbers...' : 'Generate RSA Key Pair'"></span>
                </button>
            </div>

            <!-- Keys Output -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <!-- Public Key -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Public Key (SPKI PEM)</span>
                        <div class="flex gap-2">
                            <button type="button" @click="download(publicKey, 'public_key.pem')" :disabled="!publicKey" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Download .pem</button>
                            <button type="button" @click="copy(publicKey)" :disabled="!publicKey" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                        </div>
                    </div>
                    <textarea readonly rows="12" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="publicKey || 'Click Generate to create keypair...'"></textarea>
                </div>

                <!-- Private Key -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Private Key (PKCS#8 PEM)</span>
                        <div class="flex gap-2">
                            <button type="button" @click="download(privateKey, 'private_key.pem')" :disabled="!privateKey" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Download .pem</button>
                            <button type="button" @click="copy(privateKey)" :disabled="!privateKey" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                        </div>
                    </div>
                    <textarea readonly rows="12" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="privateKey || 'Click Generate to create keypair...'"></textarea>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function rsaKeyApp() {
            return {
                keySize: 2048,
                publicKey: '',
                privateKey: '',
                loading: false,

                init() {
                    this.generateKeys();
                },

                async generateKeys() {
                    this.loading = true;
                    try {
                        const keyPair = await window.crypto.subtle.generateKey(
                            {
                                name: 'RSA-OAEP',
                                modulusLength: this.keySize,
                                publicExponent: new Uint8Array([1, 0, 1]),
                                hash: 'SHA-256'
                            },
                            true,
                            ['encrypt', 'decrypt']
                        );

                        // Export Public Key (SPKI)
                        const spki = await window.crypto.subtle.exportKey('spki', keyPair.publicKey);
                        this.publicKey = this.toPem(spki, 'PUBLIC KEY');

                        // Export Private Key (PKCS8)
                        const pkcs8 = await window.crypto.subtle.exportKey('pkcs8', keyPair.privateKey);
                        this.privateKey = this.toPem(pkcs8, 'PRIVATE KEY');
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },

                toPem(buffer, label) {
                    const bytes = new Uint8Array(buffer);
                    let binary = '';
                    for (let i = 0; i < bytes.byteLength; i++) {
                        binary += String.fromCharCode(bytes[i]);
                    }
                    const b64 = btoa(binary);
                    const formatted = b64.match(/.{1,64}/g).join('\n');
                    return `-----BEGIN ${label}-----\n${formatted}\n-----END ${label}-----`;
                },

                download(content, filename) {
                    if (!content) return;
                    const blob = new Blob([content], { type: 'application/x-pem-file' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    a.click();
                    URL.revokeObjectURL(url);
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
