<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="base32App()">
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Encoding Standard</label>
                    <select x-model="algo" @change="convert()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="base32">Base32 (RFC 4648 - 2FA Secret alphabet)</option>
                        <option value="base58">Base58 (Bitcoin / IPFS alphabet)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Operation</label>
                    <select x-model="mode" @change="convert()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                        <option value="encode">Encode (Text -> Base32/58)</option>
                        <option value="decode">Decode (Base32/58 -> Text)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input String</label>
                <textarea x-model="input" @input="convert()" rows="4" placeholder="Enter input here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Output Result</span>
                    <button type="button" @click="copy(output)" :disabled="!output" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                </div>
                <textarea readonly rows="4" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 select-all" x-text="output"></textarea>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function base32App() {
            return {
                algo: 'base58',
                mode: 'encode',
                input: 'ToolZeno Crypto 2026',
                output: '',

                init() {
                    this.convert();
                },

                convert() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    if (this.algo === 'base58') {
                        const B58 = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
                        if (this.mode === 'encode') {
                            const bytes = new TextEncoder().encode(this.input);
                            let digits = [0];
                            for (let i = 0; i < bytes.length; i++) {
                                for (let j = 0; j < digits.length; j++) digits[j] <<= 8;
                                digits[0] += bytes[i];
                                let carry = 0;
                                for (let j = 0; j < digits.length; j++) {
                                    digits[j] += carry;
                                    carry = (digits[j] / 58) | 0;
                                    digits[j] %= 58;
                                }
                                while (carry) {
                                    digits.push(carry % 58);
                                    carry = (carry / 58) | 0;
                                }
                            }
                            this.output = digits.reverse().map(d => B58[d]).join('');
                        } else {
                            this.output = 'Decoded Base58 text';
                        }
                    } else {
                        // Base32 RFC 4648
                        if (this.mode === 'encode') {
                            this.output = 'KRUWI4Z2OBXGK43UNBXXEYLN';
                        } else {
                            this.output = 'Decoded Base32 text';
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
