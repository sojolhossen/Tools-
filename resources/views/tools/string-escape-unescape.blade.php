<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="stringEscapeTool()">
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
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" @click="mode = 'escape'; process()" :class="mode === 'escape' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Escape
                    </button>
                    <button type="button" @click="mode = 'unescape'; process()" :class="mode === 'unescape' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Unescape
                    </button>
                    <div class="h-4 w-px bg-slate-300 dark:bg-slate-700 mx-1"></div>
                    <select x-model="flavor" @change="process()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs px-2.5 py-1.5 text-slate-900 dark:text-slate-100 font-semibold">
                        <option value="json">JSON / JavaScript</option>
                        <option value="sql">SQL String</option>
                        <option value="html">HTML Entities</option>
                        <option value="slashes">Add / Strip Slashes</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        Sample
                    </button>
                    <button type="button" @click="clearAll()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1"></i> Clear
                    </button>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input String</label>
                    <textarea x-model="input" @input="process()" placeholder="Type string here..." rows="14" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Processed Result</label>
                        <button type="button" @click="copyOutput()" :disabled="!output" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <textarea x-model="output" readonly rows="14" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="Output will appear here..."></textarea>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-css-js-beautifier" />
    </div>

    @push('scripts')
    <script>
        function stringEscapeTool() {
            return {
                mode: 'escape',
                flavor: 'json',
                input: '',
                output: '',
                copied: false,
                process() {
                    if (!this.input) {
                        this.output = '';
                        return;
                    }

                    if (this.flavor === 'json') {
                        if (this.mode === 'escape') {
                            this.output = JSON.stringify(this.input).slice(1, -1);
                        } else {
                            try {
                                this.output = JSON.parse(`"${this.input}"`);
                            } catch (e) {
                                this.output = this.input.replace(/\\n/g, '\n').replace(/\\t/g, '\t').replace(/\\"/g, '"').replace(/\\\\/g, '\\');
                            }
                        }
                    } else if (this.flavor === 'sql') {
                        if (this.mode === 'escape') {
                            this.output = this.input.replace(/'/g, "''").replace(/\\/g, '\\\\');
                        } else {
                            this.output = this.input.replace(/''/g, "'").replace(/\\\\/g, '\\');
                        }
                    } else if (this.flavor === 'html') {
                        if (this.mode === 'escape') {
                            this.output = this.input.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
                        } else {
                            const txt = document.createElement("textarea");
                            txt.innerHTML = this.input;
                            this.output = txt.value;
                        }
                    } else if (this.flavor === 'slashes') {
                        if (this.mode === 'escape') {
                            this.output = this.input.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
                        } else {
                            this.output = this.input.replace(/\\([\\"'])/g, '$1').replace(/\\0/g, '\u0000');
                        }
                    }
                },
                loadSample() {
                    this.input = `Hello! "This is a quote" and \\a backslash\\\nWith a new line and 'single quotes'.`;
                    this.process();
                },
                clearAll() {
                    this.input = '';
                    this.output = '';
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
