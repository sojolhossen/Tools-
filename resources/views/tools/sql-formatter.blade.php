<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
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
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div x-data="sqlFormatterApp()" class="space-y-6">
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <button type="button" @click="format()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs sm:text-sm font-bold hover:bg-indigo-700 transition shadow-2xs cursor-pointer">
                        Format SQL
                    </button>
                    <label class="flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="uppercaseKeywords" @change="format()" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                        <span>Uppercase Keywords</span>
                    </label>
                    <button type="button" @click="loadSample()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 rounded-xl text-xs font-semibold transition cursor-pointer">Sample</button>
                    <button type="button" @click="clear()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-rose-600 rounded-xl text-xs font-semibold hover:bg-rose-50 transition cursor-pointer">Clear</button>
                </div>
                <button type="button" @click="copy()" :disabled="!output" class="px-4 py-2 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-xs font-bold hover:bg-slate-800 transition shadow-2xs cursor-pointer disabled:opacity-40">
                    <span x-text="copied ? 'Copied!' : 'Copy SQL'"></span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Unformatted SQL Query</span>
                    <textarea x-model="input" @input="format()" rows="16" placeholder="Paste your messy SQL query here (SELECT * FROM ...)" class="w-full flex-1 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-950 text-emerald-400 font-mono text-xs leading-relaxed focus:outline-none focus:ring-2 focus:ring-indigo-600 resize-y" spellcheck="false"></textarea>
                </div>
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Formatted & Indented Query</span>
                    <textarea x-model="output" readonly rows="16" placeholder="Beautified SQL will appear here..." class="w-full flex-1 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-950 text-sky-300 font-mono text-xs leading-relaxed focus:outline-none resize-y" spellcheck="false"></textarea>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide tool-key="sql_formatter" related-slug="json-formatter" />

        @if($relatedTools->count() > 0)
            <div class="mt-12 pt-10 border-t border-slate-200/80 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
                    {{ $locale === 'bn' ? 'সম্পর্কিত অন্যান্য টুলস' : 'Related Utilities' }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedTools as $rel)
                        <x-tool-card :tool="$rel" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
    function sqlFormatterApp() {
        return {
            input: '',
            output: '',
            uppercaseKeywords: true,
            indentSize: 2,
            copied: false,
            format() {
                if (!this.input.trim()) { this.output = ''; return; }
                const keywords = [
                    'SELECT', 'FROM', 'WHERE', 'AND', 'OR', 'LEFT JOIN', 'RIGHT JOIN', 
                    'INNER JOIN', 'OUTER JOIN', 'JOIN', 'ON', 'GROUP BY', 'HAVING', 
                    'ORDER BY', 'LIMIT', 'OFFSET', 'UNION ALL', 'UNION', 'INSERT INTO', 
                    'VALUES', 'UPDATE', 'SET', 'DELETE FROM', 'CREATE TABLE', 'DROP TABLE', 
                    'ALTER TABLE', 'AS', 'IN', 'BETWEEN', 'LIKE', 'IS NULL', 'IS NOT NULL',
                    'COUNT', 'SUM', 'AVG', 'MIN', 'MAX', 'CASE', 'WHEN', 'THEN', 'ELSE', 'END'
                ];

                let sql = this.input.replace(/\s+/g, ' ').trim();
                const lineBreakers = ['SELECT', 'FROM', 'WHERE', 'LEFT JOIN', 'RIGHT JOIN', 'INNER JOIN', 'JOIN', 'GROUP BY', 'HAVING', 'ORDER BY', 'LIMIT', 'SET', 'VALUES'];
                
                lineBreakers.forEach(kw => {
                    const regex = new RegExp('\\b' + kw + '\\b', 'gi');
                    sql = sql.replace(regex, '\n' + kw.toUpperCase() + ' ');
                });

                sql = sql.replace(/\bAND\b/gi, '\n  AND ')
                         .replace(/\bOR\b/gi, '\n  OR ')
                         .replace(/,\s*/g, ',\n  ');

                if (this.uppercaseKeywords) {
                    keywords.forEach(kw => {
                        const regex = new RegExp('\\b' + kw + '\\b', 'gi');
                        sql = sql.replace(regex, kw.toUpperCase());
                    });
                }

                this.output = sql.split('\n').map(l => l.trimEnd()).filter(l => l.length > 0).join('\n').trim();
            },
            loadSample() {
                this.input = "select u.id, u.name, u.email, count(o.id) as total_orders, sum(o.amount) as total_spent\nfrom users u\nleft join orders o on u.id = o.user_id\nwhere u.status = 1 and u.created_at >= '2026-01-01'\ngroup by u.id, u.name, u.email\nhaving count(o.id) > 5\norder by total_spent desc limit 20;";
                this.format();
            },
            copy() {
                if (!this.output) return;
                navigator.clipboard.writeText(this.output).then(() => {
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                });
            },
            clear() {
                this.input = '';
                this.output = '';
            }
        };
    }
    </script>
</x-layouts.app>
