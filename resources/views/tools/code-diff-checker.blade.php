<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="codeDiffChecker()">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-8">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button" @click="compare()" class="px-4 py-2 rounded-lg text-xs font-bold bg-primary-600 hover:bg-primary-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-code-compare"></i> Compare Code
                    </button>
                    <button type="button" @click="loadSample()" class="px-3 py-2 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        Sample
                    </button>
                </div>
                <button type="button" @click="clearAll()" class="px-3 py-2 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                    <i class="fa-solid fa-trash-can mr-1"></i> Clear
                </button>
            </div>

            <!-- Inputs Side by Side -->
            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-2">Original Code (Before)</label>
                    <textarea x-model="originalCode" @input="compare()" placeholder="Paste original code here..." rows="12" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-2">Modified Code (After)</label>
                    <textarea x-model="modifiedCode" @input="compare()" placeholder="Paste modified code here..." rows="12" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
            </div>
        </div>

        <!-- Diff Output Visualizer -->
        <template x-if="diffLines.length > 0">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between text-xs font-semibold">
                    <span class="uppercase tracking-wider text-slate-600 dark:text-slate-400">Diff Inspection Result</span>
                    <div class="flex items-center gap-3">
                        <span class="text-rose-600 font-mono" x-text="'- ' + removedCount + ' lines removed'"></span>
                        <span class="text-emerald-600 font-mono" x-text="'+ ' + addedCount + ' lines added'"></span>
                    </div>
                </div>
                <div class="divide-y divide-slate-100 dark:divide-slate-800 font-mono text-xs sm:text-sm max-h-[500px] overflow-y-auto">
                    <template x-for="(line, idx) in diffLines" :key="idx">
                        <div class="flex items-start px-4 py-1.5" :class="{
                            'bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-200': line.type === 'removed',
                            'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-200': line.type === 'added',
                            'text-slate-700 dark:text-slate-300': line.type === 'same'
                        }">
                            <span class="w-6 select-none font-bold" x-text="line.type === 'removed' ? '-' : (line.type === 'added' ? '+' : ' ')"></span>
                            <span class="flex-1 whitespace-pre-wrap break-all" x-text="line.text"></span>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-css-js-beautifier" />
    </div>

    @push('scripts')
    <script>
        function codeDiffChecker() {
            return {
                originalCode: '',
                modifiedCode: '',
                diffLines: [],
                removedCount: 0,
                addedCount: 0,
                init() {
                    this.loadSample();
                },
                compare() {
                    const orig = this.originalCode.split('\n');
                    const mod = this.modifiedCode.split('\n');
                    const lines = [];
                    let rCount = 0;
                    let aCount = 0;

                    const max = Math.max(orig.length, mod.length);
                    for (let i = 0; i < max; i++) {
                        const oLine = orig[i];
                        const mLine = mod[i];

                        if (oLine === mLine && oLine !== undefined) {
                            lines.push({ type: 'same', text: oLine });
                        } else {
                            if (oLine !== undefined) {
                                lines.push({ type: 'removed', text: oLine });
                                rCount++;
                            }
                            if (mLine !== undefined) {
                                lines.push({ type: 'added', text: mLine });
                                aCount++;
                            }
                        }
                    }

                    this.diffLines = lines;
                    this.removedCount = rCount;
                    this.addedCount = aCount;
                },
                loadSample() {
                    this.originalCode = `function calculateTotal(price) {\n    const tax = 0.05;\n    return price + (price * tax);\n}`;
                    this.modifiedCode = `function calculateTotal(price, discount = 0) {\n    const tax = 0.08;\n    const subtotal = price - discount;\n    return subtotal + (subtotal * tax);\n}`;
                    this.compare();
                },
                clearAll() {
                    this.originalCode = '';
                    this.modifiedCode = '';
                    this.diffLines = [];
                    this.removedCount = 0;
                    this.addedCount = 0;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
