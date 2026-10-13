<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="digitalSigApp()">
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
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message to Sign / Verify</label>
                    <textarea x-model="message" rows="3" placeholder="Enter message payload..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" @click="testKeypairAndSign()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                        Generate ECDSA Keys & Sign
                    </button>
                    <button type="button" @click="verifyCurrentSignature()" :disabled="!signature" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-slate-800 hover:bg-slate-900 text-white shadow-sm transition-all disabled:opacity-50">
                        Verify Signature
                    </button>
                </div>

                <div x-show="signature" class="space-y-3 pt-3 border-t border-slate-200 dark:border-slate-800">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Cryptographic Signature (Base64)</span>
                            <button type="button" @click="copy(signature)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                        </div>
                        <div class="font-mono text-xs font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 break-all select-all" x-text="signature"></div>
                    </div>

                    <template x-if="verifyStatus !== null">
                        <div class="p-3.5 rounded-xl text-xs font-bold flex items-center gap-2" :class="verifyStatus ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-400'">
                            <span x-text="verifyStatus ? '✓ SIGNATURE VALID: The signature matches the public key and data integrity is intact.' : '✗ TAMPER DETECTED: The signature does not match the payload!'"></span>
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
        function digitalSigApp() {
            return {
                message: 'I hereby approve transaction #8942-AUTH for $5,000 USD.',
                signature: '',
                verifyStatus: null,
                cachedKeyPair: null,

                init() {
                    this.testKeypairAndSign();
                },

                async testKeypairAndSign() {
                    try {
                        this.cachedKeyPair = await window.crypto.subtle.generateKey(
                            { name: 'ECDSA', namedCurve: 'P-256' },
                            true,
                            ['sign', 'verify']
                        );
                        const data = new TextEncoder().encode(this.message);
                        const sig = await window.crypto.subtle.sign(
                            { name: 'ECDSA', hash: 'SHA-256' },
                            this.cachedKeyPair.privateKey,
                            data
                        );
                        const bArr = new Uint8Array(sig);
                        let bin = '';
                        bArr.forEach(b => bin += String.fromCharCode(b));
                        this.signature = btoa(bin);
                        this.verifyStatus = true;
                    } catch (e) {
                        console.error(e);
                    }
                },

                async verifyCurrentSignature() {
                    if (!this.cachedKeyPair || !this.signature) return;
                    try {
                        const raw = atob(this.signature);
                        const sigArr = new Uint8Array(raw.length);
                        for (let i = 0; i < raw.length; i++) sigArr[i] = raw.charCodeAt(i);

                        const data = new TextEncoder().encode(this.message);
                        this.verifyStatus = await window.crypto.subtle.verify(
                            { name: 'ECDSA', hash: 'SHA-256' },
                            this.cachedKeyPair.publicKey,
                            sigArr,
                            data
                        );
                    } catch (e) {
                        this.verifyStatus = false;
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
