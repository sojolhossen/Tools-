<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="nanoidTool()">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <!-- Settings toolbar -->
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-2">Length:</label>
                        <input type="number" x-model.number="size" @input="generate()" min="5" max="64" class="w-16 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-2">Quantity:</label>
                        <select x-model.number="count" @change="generate()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="generate()" class="px-4 py-2 rounded-lg text-xs font-bold bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-arrows-rotate"></i> Regenerate
                    </button>
                    <button type="button" @click="copyAll()" :disabled="ids.length === 0" class="px-3 py-2 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors flex items-center gap-1">
                        <i class="fa-regular fa-copy"></i>
                        <span x-text="copied ? 'Copied!' : 'Copy All'"></span>
                    </button>
                </div>
            </div>

            <!-- Custom Alphabet option -->
            <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-950/20 flex flex-wrap items-center gap-2 text-xs">
                <span class="text-slate-600 dark:text-slate-400 font-semibold">Alphabet:</span>
                <input type="text" x-model="alphabet" @input="generate()" class="flex-1 font-mono text-xs bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1 text-slate-900 dark:text-slate-100">
                <button type="button" @click="resetAlphabet()" class="text-primary-600 hover:underline">Reset Default</button>
            </div>

            <!-- List Output -->
            <div class="p-5 sm:p-6 space-y-2">
                <template x-for="(id, idx) in ids" :key="idx">
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 font-mono text-xs sm:text-sm text-slate-900 dark:text-slate-100 hover:border-primary-400 transition-colors">
                        <span x-text="id"></span>
                        <button type="button" @click="copySingle(id)" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                            Copy
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="uuid-generator" />
    </div>

    @push('scripts')
    <script>
        function nanoidTool() {
            const defaultAlphabet = 'useandom-26T1983_40STAkjlhkfZoyrumpbgf598375';
            return {
                size: 21,
                count: 5,
                alphabet: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz-',
                ids: [],
                copied: false,
                init() {
                    this.generate();
                },
                generate() {
                    const list = [];
                    const alpha = this.alphabet || '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz-';
                    for (let i = 0; i < this.count; i++) {
                        let id = '';
                        const bytes = crypto.getRandomValues(new Uint8Array(this.size));
                        for (let j = 0; j < this.size; j++) {
                            id += alpha[bytes[j] % alpha.length];
                        }
                        list.push(id);
                    }
                    this.ids = list;
                },
                resetAlphabet() {
                    this.alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz-';
                    this.generate();
                },
                copySingle(id) {
                    navigator.clipboard.writeText(id);
                },
                copyAll() {
                    navigator.clipboard.writeText(this.ids.join('\n')).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
