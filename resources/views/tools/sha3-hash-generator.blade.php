<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sha3GeneratorApp()">
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Algorithm</label>
                    <select x-model="algorithm" @change="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="keccak256">Keccak-256 (Ethereum standard)</option>
                        <option value="sha3_256">SHA3-256 (NIST standard)</option>
                        <option value="sha3_512">SHA3-512 (High Security)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Input Text or Hex Payload</label>
                        <button type="button" @click="input = 'transfer(address,uint256)'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (Web3 method)</button>
                    </div>
                    <input type="text" x-model="input" @input="compute()" placeholder="Type message or contract signature to hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400" x-text="algorithm.toUpperCase() + ' Hash Output'"></span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-sm sm:text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div x-show="algorithm === 'keccak256' && hashHex">
                    <span class="text-xs font-semibold text-slate-500">Ethereum 4-byte Method Selector:</span>
                    <span class="font-mono text-xs font-bold text-rose-600 dark:text-rose-400 ml-2" x-text="'0x' + hashHex.substring(0, 8)"></span>
                </div>
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
        function sha3GeneratorApp() {
            return {
                algorithm: 'keccak256',
                input: 'transfer(address,uint256)',
                hashHex: '',

                init() {
                    this.compute();
                },

                compute() {
                    if (!this.input || !window.CryptoJS) {
                        this.hashHex = '';
                        return;
                    }
                    if (this.algorithm === 'sha3_512') {
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 512 }).toString();
                    } else if (this.algorithm === 'sha3_256') {
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 256 }).toString();
                    } else {
                        // Keccak 256
                        this.hashHex = CryptoJS.SHA3(this.input, { outputLength: 256, keccak: true }).toString();
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
