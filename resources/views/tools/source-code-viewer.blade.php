<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sourceViewerApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50">
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


        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <!-- Input & Actions -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Target Website URL</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-sm">📄</span>
                        <input type="text" x-model="url" @keydown.enter="fetchSource()" placeholder="https://example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                    </div>
                    <button type="button" @click="fetchSource()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-show="!loading">Fetch Source Code</span>
                        <span x-show="loading">Fetching HTML...</span>
                    </button>
                </div>
                <!-- Presets -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-slate-500">Quick Test:</span>
                    <button type="button" @click="setPreset('https://shebafind.site')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">shebafind.site</button>
                    <button type="button" @click="setPreset('https://example.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">example.com</button>
                    <button type="button" @click="setPreset('https://github.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">github.com</button>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-sm flex items-start gap-3">
                <span class="text-lg">⚠️</span>
                <div>
                    <strong class="font-semibold block">Fetch Failed</strong>
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="animate-pulse space-y-4">
                <div class="h-10 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                <div class="h-64 bg-slate-100 dark:bg-slate-800 rounded-2xl"></div>
            </div>

            <!-- Code Viewer Card -->
            <div x-show="!loading && audited" x-cloak class="space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-mono">
                    <div class="flex items-center gap-4 text-slate-600 dark:text-slate-400">
                        <span x-text="'Lines: ' + lines.toLocaleString()"></span>
                        <span x-text="'Characters: ' + chars.toLocaleString()"></span>
                        <span x-show="statusCode" x-text="'Status: HTTP ' + statusCode"></span>
                        <span x-show="truncated" class="text-amber-600 dark:text-amber-400 font-bold">(Truncated at 600 KB)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="copyCode()" class="px-3 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-medium transition-colors">
                            <span x-text="copied ? 'Copied!' : 'Copy Source Code'"></span>
                        </button>
                        <button type="button" @click="downloadHtml()" class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-300 font-medium hover:bg-blue-100 dark:hover:bg-blue-900/60 transition-colors">
                            Download .HTML
                        </button>
                    </div>
                </div>

                <div class="relative">
                    <textarea x-model="source" readonly rows="18" class="w-full p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-950 text-slate-200 font-mono text-xs focus:outline-none selection:bg-blue-600 selection:text-white leading-relaxed"></textarea>
                </div>
            </div>
        </div>

        <script>
        function sourceViewerApp() {
            return {
                url: 'https://shebafind.site',
                loading: false,
                audited: false,
                errorMessage: '',
                source: '',
                chars: 0,
                lines: 0,
                statusCode: null,
                truncated: false,
                copied: false,

                init() {
                    this.fetchSource();
                },

                setPreset(target) {
                    this.url = target;
                    this.fetchSource();
                },

                async fetchSource() {
                    let raw = (this.url || '').trim();
                    if (!raw) {
                        this.errorMessage = 'Please enter a URL to inspect.';
                        return;
                    }

                    raw = raw.replace(/\.+$/, '');
                    if (!raw.startsWith('http://') && !raw.startsWith('https://')) {
                        raw = 'https://' + raw;
                    }
                    this.url = raw;

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const res = await fetch('/api/tools/source-code-view?url=' + encodeURIComponent(raw));
                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Failed to fetch source code.');
                        }

                        this.source = data.source || '';
                        this.chars = data.characters || 0;
                        this.lines = data.lines || 0;
                        this.statusCode = data.status;
                        this.truncated = data.truncated || false;
                        this.audited = true;
                    } catch (err) {
                        this.errorMessage = err.message || 'Unable to fetch source code. Ensure the website is reachable.';
                    } finally {
                        this.loading = false;
                    }
                },

                copyCode() {
                    if (!this.source) return;
                    navigator.clipboard.writeText(this.source);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                },

                downloadHtml() {
                    if (!this.source) return;
                    const blob = new Blob([this.source], { type: 'text/html;charset=utf-8' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'source_code_' + (new URL(this.url).hostname || 'page') + '.html';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }
            };
        }
        </script>


        <!-- Informational / SEO Section -->
        <div class="mt-12 space-y-8 border-t border-slate-200 dark:border-slate-800 pt-8">
            @if(!empty(__('tools.' . $tool->slug . '.how_to_guide.steps')))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('tools.' . $tool->slug . '.how_to_guide.title') }}
                </h2>
                <ol class="space-y-3 list-decimal list-inside text-sm text-slate-600 dark:text-slate-300">
                    @foreach(__('tools.' . $tool->slug . '.how_to_guide.steps') as $step)
                        <li class="leading-relaxed">{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
            @endif

            @if(!empty(__('tools.' . $tool->slug . '.technical_specs.items')))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('tools.' . $tool->slug . '.technical_specs.title') }}
                </h2>
                <ul class="space-y-2 list-disc list-inside text-sm text-slate-600 dark:text-slate-300">
                    @foreach(__('tools.' . $tool->slug . '.technical_specs.items') as $item)
                        <li class="leading-relaxed">{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty(__('tools.' . $tool->slug . '.faqs')))
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    {{ __('common.faq.title') ?? 'Frequently Asked Questions' }}
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    @foreach(__('tools.' . $tool->slug . '.faqs') as $faq)
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-2">{{ $faq['question'] }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
