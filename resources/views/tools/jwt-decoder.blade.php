<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jwtDecoder()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getShortDescription() }}
            </p>
        </div>

        <div class="space-y-6 mb-10">
            <!-- Input Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Encoded JWT Token</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                            Sample Token
                        </button>
                        <button type="button" @click="token = ''; decode();" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                            Clear
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <textarea x-model="token" @input="decode()" placeholder="Paste eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." rows="4" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all break-all"></textarea>
                </div>
                <template x-if="error">
                    <div class="px-6 py-3 border-t border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 text-xs font-mono flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span x-text="error"></span>
                    </div>
                </template>
            </div>

            <!-- Decoded Output Cards -->
            <template x-if="headerJson">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Header -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-rose-200 dark:border-rose-900/50 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-rose-100 dark:border-rose-900/40 bg-rose-50/50 dark:bg-rose-950/20 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400">Header: Algorithm & Type</span>
                            <span class="text-xs font-mono text-slate-500" x-text="'alg: ' + (headerObj.alg || 'none')"></span>
                        </div>
                        <div class="p-4">
                            <pre class="font-mono text-xs text-rose-600 dark:text-rose-400 overflow-x-auto whitespace-pre-wrap" x-text="headerJson"></pre>
                        </div>
                    </div>

                    <!-- Payload -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-indigo-200 dark:border-indigo-900/50 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-indigo-100 dark:border-indigo-900/40 bg-indigo-50/50 dark:bg-indigo-950/20 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-700 dark:text-indigo-400">Payload: Data / Claims</span>
                            <template x-if="expStatus">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold" :class="expStatus.expired ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300'" x-text="expStatus.label"></span>
                            </template>
                        </div>
                        <div class="p-4">
                            <pre class="font-mono text-xs text-indigo-600 dark:text-indigo-300 overflow-x-auto whitespace-pre-wrap" x-text="payloadJson"></pre>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="base64-encoder-decoder" />
    </div>

    @push('scripts')
    <script>
        function jwtDecoder() {
            return {
                token: '',
                headerJson: '',
                payloadJson: '',
                headerObj: {},
                payloadObj: {},
                expStatus: null,
                error: '',
                decode() {
                    this.error = '';
                    this.headerJson = '';
                    this.payloadJson = '';
                    this.headerObj = {};
                    this.payloadObj = {};
                    this.expStatus = null;

                    if (!this.token.trim()) return;

                    const parts = this.token.trim().split('.');
                    if (parts.length < 2) {
                        this.error = 'Invalid JWT format: A valid JWT consists of header, payload, and signature separated by dots.';
                        return;
                    }

                    try {
                        const decodePart = (b64Url) => {
                            let base64 = b64Url.replace(/-/g, '+').replace(/_/g, '/');
                            while (base64.length % 4) base64 += '=';
                            const binStr = atob(base64);
                            const bytes = Uint8Array.from(binStr, m => m.charCodeAt(0));
                            return JSON.parse(new TextDecoder().decode(bytes));
                        };

                        this.headerObj = decodePart(parts[0]);
                        this.headerJson = JSON.stringify(this.headerObj, null, 2);

                        this.payloadObj = decodePart(parts[1]);
                        this.payloadJson = JSON.stringify(this.payloadObj, null, 2);

                        if (this.payloadObj.exp) {
                            const expDate = new Date(this.payloadObj.exp * 1000);
                            const now = new Date();
                            if (expDate < now) {
                                this.expStatus = { expired: true, label: 'Expired: ' + expDate.toLocaleString() };
                            } else {
                                this.expStatus = { expired: false, label: 'Valid until: ' + expDate.toLocaleString() };
                            }
                        }
                    } catch (e) {
                        this.error = 'Failed to parse JWT JSON content: ' + e.message;
                    }
                },
                loadSample() {
                    this.token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMiwiZXhwIjoyNTI0NjA4MDAwfQ.4S1i0k-h8v9jW7...';
                    this.decode();
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
