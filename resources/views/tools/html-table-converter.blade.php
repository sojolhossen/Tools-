<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="htmlTableConverter()">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button" @click="format = 'json'; convert()" :class="format === 'json' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        Convert to JSON
                    </button>
                    <button type="button" @click="format = 'csv'; convert()" :class="format === 'csv' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        Convert to CSV
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        Sample
                    </button>
                    <button type="button" @click="clearAll()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        Clear
                    </button>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input HTML Table Code</label>
                    <textarea x-model="htmlInput" @input="convert()" placeholder="<table>&#10;  <tr><th>Name</th><th>Email</th></tr>&#10;  <tr><td>John</td><td>john@example.com</td></tr>&#10;</table>" rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            <span x-text="format === 'json' ? 'Extracted JSON Array' : 'Extracted CSV Spreadsheet'"></span>
                        </label>
                        <button type="button" @click="copyOutput()" :disabled="!output" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <textarea x-model="output" readonly rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="Output will appear here..."></textarea>
                </div>
            </div>

            <template x-if="error">
                <div class="px-6 py-3 border-t border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 text-xs font-mono flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span x-text="error"></span>
                </div>
            </template>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="markdown-table-generator" />
    </div>

    @push('scripts')
    <script>
        function htmlTableConverter() {
            return {
                format: 'json',
                htmlInput: '',
                output: '',
                error: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                convert() {
                    this.error = '';
                    if (!this.htmlInput.trim()) {
                        this.output = '';
                        return;
                    }
                    try {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(this.htmlInput, 'text/html');
                        const table = doc.querySelector('table');
                        if (!table) {
                            throw new Error('No <table> element found in provided markup.');
                        }

                        const headers = [];
                        const ths = table.querySelectorAll('thead th, tr:first-child th, tr:first-child td');
                        ths.forEach((th, idx) => headers.push(th.textContent.trim() || `column_${idx + 1}`));

                        const dataRows = [];
                        const rows = table.querySelectorAll('tbody tr, tr');
                        const startIdx = table.querySelector('thead') ? 0 : 1;

                        for (let i = startIdx; i < rows.length; i++) {
                            const cells = rows[i].querySelectorAll('td');
                            if (cells.length > 0) {
                                const rowObj = {};
                                const rowArr = [];
                                cells.forEach((c, cIdx) => {
                                    const val = c.textContent.trim();
                                    const key = headers[cIdx] || `col_${cIdx + 1}`;
                                    rowObj[key] = val;
                                    rowArr.push(`"${val.replace(/"/g, '""')}"`);
                                });
                                dataRows.push({ obj: rowObj, csv: rowArr.join(',') });
                            }
                        }

                        if (this.format === 'json') {
                            this.output = JSON.stringify(dataRows.map(r => r.obj), null, 2);
                        } else {
                            const headerCsv = headers.map(h => `"${h.replace(/"/g, '""')}"`).join(',');
                            this.output = [headerCsv, ...dataRows.map(r => r.csv)].join('\n');
                        }
                    } catch (e) {
                        this.error = e.message;
                    }
                },
                loadSample() {
                    this.htmlInput = `<table class="table">\n  <thead>\n    <tr>\n      <th>User ID</th>\n      <th>Name</th>\n      <th>Role</th>\n      <th>Country</th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr>\n      <td>101</td>\n      <td>Sarah Connor</td>\n      <td>Admin</td>\n      <td>USA</td>\n    </tr>\n    <tr>\n      <td>102</td>\n      <td>John Doe</td>\n      <td>Editor</td>\n      <td>UK</td>\n    </tr>\n  </tbody>\n</table>`;
                    this.convert();
                },
                clearAll() {
                    this.htmlInput = '';
                    this.output = '';
                    this.error = '';
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
