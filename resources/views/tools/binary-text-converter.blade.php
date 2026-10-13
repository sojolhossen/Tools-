<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="binaryApp()">
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
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="mode = 't2b'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 't2b' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Text to Binary</button>
                <button type="button" @click="mode = 'b2t'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="mode === 'b2t' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Binary to Text</button>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2" x-text="mode === 't2b' ? 'Text Input' : 'Binary Input (0s and 1s)'"></label>
                <textarea x-model="input" @input="convert()" rows="4" placeholder="Enter input here..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Converted Output</span>
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
        function binaryApp() {
            return {
                mode: 't2b',
                input: 'Security',
                output: '',

                init() {
                    this.convert();
                },

                convert() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    if (this.mode === 't2b') {
                        const bytes = new TextEncoder().encode(this.input);
                        this.output = Array.from(bytes).map(b => b.toString(2).padStart(8, '0')).join(' ');
                    } else {
                        try {
                            const cleaned = this.input.trim().replace(/[^01]/g, ' ');
                            const bits = cleaned.split(/\s+/).filter(Boolean);
                            const bytes = new Uint8Array(bits.map(b => parseInt(b, 2)));
                            this.output = new TextDecoder().decode(bytes);
                        } catch (e) {
                            this.output = 'Error decoding binary.';
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
