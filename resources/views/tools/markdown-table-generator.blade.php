<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="markdownTableGenerator()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
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
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="space-y-6 mb-10">
            <!-- Visual Table Editor -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Visual Grid Editor</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addRow()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold transition-all">
                            + Add Row
                        </button>
                        <button type="button" @click="addCol()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold transition-all">
                            + Add Column
                        </button>
                        <button type="button" @click="clearTable()" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg text-xs font-semibold transition-all">
                            Reset
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
                        <thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
                            <tr>
                                <template x-for="(col, cIdx) in headers" :key="'h-' + cIdx">
                                    <th class="p-2 border-r border-slate-200 dark:border-slate-800 min-w-[120px]">
                                        <input type="text" x-model="headers[cIdx]" @input="generate()" class="w-full font-bold bg-transparent border-0 p-1 text-slate-900 dark:text-slate-100 focus:ring-1 focus:ring-primary-500 rounded">
                                    </th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-for="(row, rIdx) in rows" :key="'r-' + rIdx">
                                <tr>
                                    <template x-for="(cell, cIdx) in row" :key="'c-' + rIdx + '-' + cIdx">
                                        <td class="p-2 border-r border-slate-100 dark:border-slate-800 min-w-[120px]">
                                            <input type="text" x-model="rows[rIdx][cIdx]" @input="generate()" class="w-full bg-transparent border-0 p-1 text-slate-800 dark:text-slate-200 focus:ring-1 focus:ring-primary-500 rounded">
                                        </td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Markdown Output Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Generated Markdown</span>
                    <button type="button" @click="copyMarkdown()" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                        <i class="fa-regular fa-copy"></i>
                        <span x-text="copied ? 'Copied!' : 'Copy Markdown'"></span>
                    </button>
                </div>
                <div class="p-5">
                    <textarea x-model="markdownOutput" readonly rows="8" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-slate-900 dark:text-slate-100 focus:outline-none resize-y"></textarea>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-table-converter" />
    </div>

    @push('scripts')
    <script>
        function markdownTableGenerator() {
            return {
                headers: ['Feature', 'Plan Free', 'Plan Pro'],
                rows: [
                    ['Cloud Storage', '5 GB', '100 GB'],
                    ['Support', 'Community', '24/7 Priority'],
                    ['API Access', 'No', 'Unlimited']
                ],
                markdownOutput: '',
                copied: false,
                init() {
                    this.generate();
                },
                generate() {
                    const hLine = '| ' + this.headers.join(' | ') + ' |';
                    const sepLine = '| ' + this.headers.map(() => '---').join(' | ') + ' |';
                    const rLines = this.rows.map(r => '| ' + r.join(' | ') + ' |');
                    this.markdownOutput = [hLine, sepLine, ...rLines].join('\n');
                },
                addRow() {
                    this.rows.push(new Array(this.headers.length).fill(''));
                    this.generate();
                },
                addCol() {
                    this.headers.push('Header ' + (this.headers.length + 1));
                    this.rows.forEach(r => r.push(''));
                    this.generate();
                },
                clearTable() {
                    this.headers = ['Header 1', 'Header 2', 'Header 3'];
                    this.rows = [['', '', ''], ['', '', '']];
                    this.generate();
                },
                copyMarkdown() {
                    navigator.clipboard.writeText(this.markdownOutput).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
