<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ecdsaKeyApp()">
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
                    <label class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Elliptic Curve:</label>
                    <select x-model="curve" @change="generate()" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                        <option value="P-256">NIST P-256 (secp256r1 - TLS & WebAuthn)</option>
                        <option value="P-384">NIST P-384 (High Security)</option>
                        <option value="P-521">NIST P-521 (Maximum Bitlength)</option>
                    </select>
                </div>

                <button type="button" @click="generate()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                    Generate EC Key Pair
                </button>
            </div>

            <!-- Keys Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">EC Public Key (SPKI)</span>
                        <button type="button" @click="copy(pubPem)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <textarea readonly rows="8" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100" x-text="pubPem"></textarea>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">EC Private Key (PKCS#8)</span>
                        <button type="button" @click="copy(privPem)" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <textarea readonly rows="8" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100" x-text="privPem"></textarea>
                </div>
            </div>

            <!-- JWK Output -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">JSON Web Key (JWK) Representation</span>
                <pre class="font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 overflow-x-auto" x-text="jwkString"></pre>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function ecdsaKeyApp() {
            return {
                curve: 'P-256',
                pubPem: '',
                privPem: '',
                jwkString: '',

                init() {
                    this.generate();
                },

                async generate() {
                    try {
                        const keyPair = await window.crypto.subtle.generateKey(
                            { name: 'ECDSA', namedCurve: this.curve },
                            true,
                            ['sign', 'verify']
                        );
                        const spki = await window.crypto.subtle.exportKey('spki', keyPair.publicKey);
                        this.pubPem = this.toPem(spki, 'PUBLIC KEY');

                        const pkcs8 = await window.crypto.subtle.exportKey('pkcs8', keyPair.privateKey);
                        this.privPem = this.toPem(pkcs8, 'PRIVATE KEY');

                        const jwk = await window.crypto.subtle.exportKey('jwk', keyPair.publicKey);
                        this.jwkString = JSON.stringify(jwk, null, 2);
                    } catch (e) {
                        console.error(e);
                    }
                },

                toPem(buffer, label) {
                    const bytes = new Uint8Array(buffer);
                    let binary = '';
                    for (let i = 0; i < bytes.byteLength; i++) binary += String.fromCharCode(bytes[i]);
                    const b64 = btoa(binary);
                    return `-----BEGIN ${label}-----\n${b64.match(/.{1,64}/g).join('\n')}\n-----END ${label}-----`;
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
