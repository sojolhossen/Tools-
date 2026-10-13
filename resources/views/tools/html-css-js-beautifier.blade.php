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

        <div 
            x-data="{
                mode: 'html',
                indent: 2,
                input: '',
                output: '',
                copied: false,
                beautify() {
                    if (!this.input.trim()) { this.output = ''; return; }
                    const spaces = ' '.repeat(Number(this.indent));
                    if (this.mode === 'html') {
                        let formatted = '';
                        let pad = 0;
                        const tokens = this.input.replace(/>\s*</g, '><').match(/<[^>]+>|[^<]+/g) || [];
                        tokens.forEach(tok => {
                            if (tok.match(/^<\/\w/)) pad = Math.max(0, pad - 1);
                            formatted += spaces.repeat(pad) + tok.trim() + '\n';
                            if (tok.match(/^<\w[^>]*[^\/]>$/) && !tok.match(/^<(area|base|br|col|embed|hr|img|input|link|meta|param|source|track|wbr)/i)) {
                                pad++;
                            }
                        });
                        this.output = formatted.trim();
                    } else if (this.mode === 'css') {
                        let formatted = this.input
                            .replace(/\s*{\s*/g, ' {\n')
                            .replace(/;\s*/g, ';\n')
                            .replace(/\s*}\s*/g, '\n}\n')
                            .replace(/\n+/g, '\n');
                        let lines = formatted.split('\n');
                        let pad = 0;
                        this.output = lines.map(line => {
                            let trimmed = line.trim();
                            if (trimmed === '}') pad = Math.max(0, pad - 1);
                            let res = spaces.repeat(pad) + trimmed;
                            if (trimmed.endsWith('{')) pad++;
                            return res;
                        }).join('\n').trim();
                    } else {
                        // JS / Generic
                        try {
                            // If valid JSON, pretty print
                            this.output = JSON.stringify(JSON.parse(this.input), null, Number(this.indent));
                        } catch {
                            let formatted = this.input
                                .replace(/\s*{\s*/g, ' {\n')
                                .replace(/;\s*/g, ';\n')
                                .replace(/\s*}\s*/g, '\n}\n');
                            let lines = formatted.split('\n');
                            let pad = 0;
                            this.output = lines.map(l => {
                                let t = l.trim();
                                if (t === '}' || t === '};') pad = Math.max(0, pad - 1);
                                let res = spaces.repeat(pad) + t;
                                if (t.endsWith('{')) pad++;
                                return res;
                            }).filter(l => l.trim().length > 0).join('\n');
                        }
                    }
                },
                loadSample() {
                    if (this.mode === 'html') {
                        this.input = '<div class=\"container\"><header><h1>Hello World</h1><p>ToolZeno HTML Beautifier</p></header><main><section><p>Clean code fast.</p></section></main></div>';
                    } else if (this.mode === 'css') {
                        this.input = 'body{margin:0;padding:0;background:#fff}.card{border:1px solid #ddd;border-radius:8px;padding:16px}.btn{background:#4f46e5;color:#fff}';
                    } else {
                        this.input = 'function calculateTotal(items){let total=0;for(let i=0;i<items.length;i++){total+=items[i].price;}return total;}console.log(calculateTotal([{price:10},{price:25}]));';
                    }
                    this.beautify();
                },
                copy() {
                    if (!this.output) return;
                    navigator.clipboard.writeText(this.output).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                },
                clear() { this.input = ''; this.output = ''; }
            }"
            class="space-y-6"
        >
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="inline-flex p-1 rounded-xl bg-slate-100 dark:bg-slate-800 text-xs font-semibold">
                        <button type="button" @click="mode = 'html'; beautify()" :class="mode === 'html' ? 'bg-indigo-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer">HTML</button>
                        <button type="button" @click="mode = 'css'; beautify()" :class="mode === 'css' ? 'bg-indigo-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer">CSS</button>
                        <button type="button" @click="mode = 'js'; beautify()" :class="mode === 'js' ? 'bg-indigo-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900'" class="px-3 py-1.5 rounded-lg transition-all cursor-pointer">JavaScript</button>
                    </div>

                    <select x-model="indent" @change="beautify()" class="px-3 py-1.5 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <option value="2">2 Spaces</option>
                        <option value="4">4 Spaces</option>
                    </select>

                    <button type="button" @click="beautify()" class="px-4 py-1.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shadow-2xs cursor-pointer">Beautify</button>
                    <button type="button" @click="loadSample()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 rounded-xl text-xs font-semibold transition cursor-pointer">Sample</button>
                    <button type="button" @click="clear()" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-rose-600 rounded-xl text-xs font-semibold hover:bg-rose-50 transition cursor-pointer">Clear</button>
                </div>

                <button type="button" @click="copy()" :disabled="!output" class="px-4 py-1.5 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 rounded-xl text-xs font-bold hover:bg-slate-800 transition shadow-2xs cursor-pointer disabled:opacity-40">
                    <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Raw Input</span>
                    <textarea x-model="input" @input="beautify()" rows="16" placeholder="Paste your HTML, CSS, or JS code here..." class="w-full flex-1 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-950 text-emerald-400 font-mono text-xs leading-relaxed focus:outline-none focus:ring-2 focus:ring-indigo-600 resize-y" spellcheck="false"></textarea>
                </div>
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Beautified Output</span>
                    <textarea x-model="output" readonly rows="16" placeholder="Beautified output will appear here..." class="w-full flex-1 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-950 text-sky-300 font-mono text-xs leading-relaxed focus:outline-none resize-y" spellcheck="false"></textarea>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide tool-key="html_css_js_beautifier" related-slug="json-formatter" />

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
</x-layouts.app>
