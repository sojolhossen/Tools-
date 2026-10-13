<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sha512GeneratorApp()">
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Input String</label>
                    <button type="button" @click="input = 'Ultra Secure High Entropy Payload'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                </div>
                <textarea x-model="input" @input="compute()" rows="4" placeholder="Enter text to calculate 512-bit SHA-512 hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none transition-all"></textarea>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">SHA-512 Hex Digest (128 characters / 512 bits)</span>
                        <button type="button" @click="copy(hashHex)" :disabled="!hashHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashHex || '---'"></div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SHA-512 Base64 Output</span>
                        <button type="button" @click="copy(hashB64)" :disabled="!hashB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="hashB64 || '---'"></div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function sha512GeneratorApp() {
            return {
                input: 'Ultra Secure High Entropy Payload',
                hashHex: '',
                hashB64: '',

                init() {
                    this.compute();
                },

                async compute() {
                    if (!this.input) {
                        this.hashHex = '';
                        this.hashB64 = '';
                        return;
                    }
                    const data = new TextEncoder().encode(this.input);
                    const digest = await window.crypto.subtle.digest('SHA-512', data);
                    const bytes = new Uint8Array(digest);
                    this.hashHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                    let bin = '';
                    bytes.forEach(b => bin += String.fromCharCode(b));
                    this.hashB64 = btoa(bin);
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
