<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jsMinifier()">
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
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button" @click="minify()" class="px-4 py-2 rounded-lg text-xs font-bold bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-bolt"></i> Minify JavaScript
                    </button>
                    <button type="button" @click="loadSample()" class="px-3 py-2 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        Sample
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="clearAll()" class="px-3 py-2 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1"></i> Clear
                    </button>
                </div>
            </div>

            <template x-if="origSize > 0">
                <div class="px-5 py-3 border-b border-slate-200 dark:border-slate-800 bg-emerald-50/50 dark:bg-emerald-950/20 flex flex-wrap items-center gap-4 text-xs font-medium text-slate-700 dark:text-slate-300">
                    <div>Original: <span class="font-bold font-mono" x-text="origSize + ' B'"></span></div>
                    <div>Minified: <span class="font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="miniSize + ' B'"></span></div>
                    <div>Saved: <span class="font-bold font-mono text-emerald-600 dark:text-emerald-400" x-text="savedPercent + '%'"></span></div>
                </div>
            </template>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Original JavaScript</label>
                    <textarea x-model="input" @input="origSize = input.length" placeholder="// Paste JavaScript code here&#10;function calculateTotal(price, tax) {&#10;    const total = price + (price * tax);&#10;    return total.toFixed(2);&#10;}" rows="15" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Minified JavaScript</label>
                        <button type="button" @click="copyOutput()" :disabled="!output" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <textarea x-model="output" readonly rows="15" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="Minified JS will appear here..."></textarea>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-css-js-beautifier" />
    </div>

    @push('scripts')
    <script>
        function jsMinifier() {
            return {
                input: '',
                output: '',
                copied: false,
                origSize: 0,
                miniSize: 0,
                get savedPercent() {
                    if (this.origSize === 0) return 0;
                    return Math.max(0, Math.round(((this.origSize - this.miniSize) / this.origSize) * 100));
                },
                minify() {
                    if (!this.input.trim()) {
                        this.output = '';
                        this.origSize = 0;
                        this.miniSize = 0;
                        return;
                    }
                    this.origSize = new Blob([this.input]).size;
                    let js = this.input;
                    // Remove multiline comments
                    js = js.replace(/\/\*[\s\S]*?\*\//g, '');
                    // Remove singleline comments (safely checking line start or spaces)
                    js = js.split('\n').map(line => {
                        let trimmed = line.trim();
                        if (trimmed.startsWith('//')) return '';
                        // Simple strip if // is not within quotes
                        const idx = line.indexOf('//');
                        if (idx > -1 && !line.includes('"//') && !line.includes("'//") && !line.includes('`//')) {
                            return line.substring(0, idx);
                        }
                        return line;
                    }).filter(Boolean).join('\n');

                    // Compress whitespace safely
                    js = js.replace(/\s+/g, ' ');
                    js = js.replace(/\s*([\{\}\(\)\[\],;:=+\-*\/%&|<>!?:])\s*/g, '$1');
                    js = js.trim();
                    this.output = js;
                    this.miniSize = new Blob([this.output]).size;
                },
                loadSample() {
                    this.input = `// Calculate discount and tax\nfunction processOrder(items, discountRate) {\n    let subtotal = 0;\n    for (let i = 0; i < items.length; i++) {\n        subtotal += items[i].price * items[i].quantity;\n    }\n    const discount = subtotal * (discountRate || 0);\n    const finalTotal = subtotal - discount;\n    console.log("Calculated total:", finalTotal);\n    return finalTotal;\n}`;
                    this.minify();
                },
                clearAll() {
                    this.input = '';
                    this.output = '';
                    this.origSize = 0;
                    this.miniSize = 0;
                },
                copyOutput() {
                    if (!this.output) return;
                    navigator.clipboard.writeText(this.output).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
