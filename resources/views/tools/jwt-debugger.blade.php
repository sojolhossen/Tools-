<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jwtApp()">
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
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Encoded JWT Token</label>
                    <button type="button" @click="loadSampleJwt()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample JWT</button>
                </div>
                <textarea x-model="rawJwt" @input="parseJwt()" rows="3" placeholder="Paste encoded JSON Web Token (header.payload.signature)..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <!-- Expiry Status Banner -->
            <div x-show="expStatus" class="p-3 rounded-xl border text-xs font-bold flex items-center justify-between" :class="isExpired ? 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/60 dark:text-rose-400 dark:border-rose-900' : 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-400 dark:border-emerald-900'">
                <span x-text="expStatus"></span>
                <span x-text="expDate" class="font-mono"></span>
            </div>

            <!-- Decoded JSON View -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Header -->
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1.5">Header (Algorithm & Token Type)</span>
                    <pre class="font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 overflow-x-auto min-h-[140px]" x-text="headerJson || 'Invalid or missing header'"></pre>
                </div>

                <!-- Payload -->
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-1.5">Payload (Data Claims)</span>
                    <pre class="font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 overflow-x-auto min-h-[140px]" x-text="payloadJson || 'Invalid or missing payload'"></pre>
                </div>
            </div>

            <!-- Signature Verifier Section -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Verify Signature (HMAC-SHA256)</span>
                <div class="flex gap-3">
                    <input type="text" x-model="jwtSecret" @input="verifySignature()" placeholder="Enter your 256-bit secret key (e.g. your-256-bit-secret)..." class="flex-1 font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                    <button type="button" @click="verifySignature()" class="px-4 py-2 font-bold text-xs bg-rose-600 hover:bg-rose-700 text-white rounded-xl">Verify</button>
                </div>
                <template x-if="sigValid !== null">
                    <div class="text-xs font-bold p-2.5 rounded-lg" :class="sigValid ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-400'" x-text="sigValid ? '✓ Signature Verified: Matches secret key!' : '✗ Invalid Signature: Does not match secret key.'"></div>
                </template>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function jwtApp() {
            return {
                rawJwt: '',
                headerJson: '',
                payloadJson: '',
                expStatus: '',
                expDate: '',
                isExpired: false,
                jwtSecret: 'your-256-bit-secret',
                sigValid: null,

                init() {
                    this.loadSampleJwt();
                },

                loadSampleJwt() {
                    // standard sample token signed with 'your-256-bit-secret'
                    this.rawJwt = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyLCJleHAiOjE5MTYyMzkwMjJ9.eSgL0k5G6jH6k41hK747wYgR3tX15vL0k-G6jH6k41g';
                    this.parseJwt();
                },

                parseJwt() {
                    this.sigValid = null;
                    if (!this.rawJwt.trim()) {
                        this.headerJson = '';
                        this.payloadJson = '';
                        this.expStatus = '';
                        return;
                    }
                    const parts = this.rawJwt.trim().split('.');
                    if (parts.length >= 2) {
                        try {
                            const h = JSON.parse(this.base64UrlDecode(parts[0]));
                            this.headerJson = JSON.stringify(h, null, 2);
                        } catch (e) {
                            this.headerJson = 'Invalid JSON in Header';
                        }
                        try {
                            const p = JSON.parse(this.base64UrlDecode(parts[1]));
                            this.payloadJson = JSON.stringify(p, null, 2);
                            if (p.exp) {
                                const expMs = p.exp * 1000;
                                const now = Date.now();
                                this.isExpired = expMs < now;
                                this.expStatus = this.isExpired ? 'Token Expired' : 'Token Active (Valid)';
                                this.expDate = new Date(expMs).toLocaleString();
                            } else {
                                this.expStatus = 'Token has no expiration (exp) claim.';
                                this.expDate = '';
                                this.isExpired = false;
                            }
                        } catch (e) {
                            this.payloadJson = 'Invalid JSON in Payload';
                        }
                    }
                },

                base64UrlDecode(str) {
                    let b64 = str.replace(/-/g, '+').replace(/_/g, '/');
                    while (b64.length % 4) b64 += '=';
                    return decodeURIComponent(atob(b64).split('').map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2)).join(''));
                },

                async verifySignature() {
                    const parts = this.rawJwt.trim().split('.');
                    if (parts.length !== 3 || !this.jwtSecret) {
                        this.sigValid = false;
                        return;
                    }
                    try {
                        const encoder = new TextEncoder();
                        const key = await window.crypto.subtle.importKey(
                            'raw',
                            encoder.encode(this.jwtSecret),
                            { name: 'HMAC', hash: 'SHA-256' },
                            false,
                            ['sign']
                        );
                        const signatureData = await window.crypto.subtle.sign(
                            'HMAC',
                            key,
                            encoder.encode(parts[0] + '.' + parts[1])
                        );
                        const bArr = new Uint8Array(signatureData);
                        let bin = '';
                        bArr.forEach(b => bin += String.fromCharCode(b));
                        const b64url = btoa(bin).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
                        this.sigValid = (b64url === parts[2]);
                    } catch (e) {
                        this.sigValid = false;
                    }
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
