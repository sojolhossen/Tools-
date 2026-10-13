<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="hmacApp()">
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Hash Algorithm</label>
                    <select x-model="algorithm" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-xs sm:text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="SHA-256">SHA-256 (API standard)</option>
                        <option value="SHA-512">SHA-512 (High Security)</option>
                        <option value="SHA-384">SHA-384</option>
                        <option value="SHA-1">SHA-1 (Legacy)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Secret Key</label>
                    <input type="text" x-model="secretKey" @input="generate()" placeholder="Enter API secret key or passphrase..." class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Message Payload</label>
                <textarea x-model="message" @input="generate()" rows="4" placeholder="Enter message payload to sign..." class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">HMAC Hex Digest</span>
                        <button type="button" @click="copy(hmacHex)" :disabled="!hmacHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[46px]" x-text="hmacHex || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">HMAC Base64 Digest</span>
                        <button type="button" @click="copy(hmacBase64)" :disabled="!hmacBase64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[46px]" x-text="hmacBase64 || '---'"></div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function hmacApp() {
            return {
                algorithm: 'SHA-256',
                secretKey: 'my-super-secret-key-123',
                message: '{"event": "payment.success", "amount": 1500}',
                hmacHex: '',
                hmacBase64: '',

                init() {
                    this.generate();
                },

                async generate() {
                    if (!this.message || !this.secretKey) {
                        this.hmacHex = '';
                        this.hmacBase64 = '';
                        return;
                    }
                    try {
                        const encoder = new TextEncoder();
                        const keyData = encoder.encode(this.secretKey);
                        const msgData = encoder.encode(this.message);

                        const cryptoKey = await window.crypto.subtle.importKey(
                            'raw',
                            keyData,
                            { name: 'HMAC', hash: { name: this.algorithm } },
                            false,
                            ['sign']
                        );

                        const signature = await window.crypto.subtle.sign('HMAC', cryptoKey, msgData);
                        const hashArray = Array.from(new Uint8Array(signature));

                        this.hmacHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
                        let bin = '';
                        hashArray.forEach(b => bin += String.fromCharCode(b));
                        this.hmacBase64 = btoa(bin);
                    } catch (err) {
                        console.error('HMAC error:', err);
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
