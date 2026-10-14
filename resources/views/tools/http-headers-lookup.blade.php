<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="httpHeadersApp()">
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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    <span>Server-Verified Inspection</span>
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
            <!-- Search Input Form -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <input type="text" x-model="url" @keydown.enter="inspectHeaders()" placeholder="https://example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <button type="button" @click="inspectHeaders()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                    <svg x-show="loading" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="loading ? 'Inspecting...' : 'Fetch HTTP Headers'"></span>
                </button>
            </div>

            <!-- Quick Presets -->
            <div class="flex items-center flex-wrap gap-2 pt-1 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-medium">Quick Test:</span>
                <button type="button" @click="url = 'https://shebafind.site'; inspectHeaders()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">shebafind.site</button>
                <button type="button" @click="url = 'https://cloudflare.com'; inspectHeaders()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">cloudflare.com</button>
                <button type="button" @click="url = 'https://google.com'; inspectHeaders()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">google.com</button>
                <button type="button" @click="url = 'https://github.com'; inspectHeaders()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">github.com</button>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span x-text="errorMessage"></span>
            </div>

            <!-- Status & Summary Banner -->
            <div x-show="status && !errorMessage" x-cloak class="p-4 rounded-xl border bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="px-3 py-1 rounded-lg font-mono text-xs font-black shadow-xs"
                            :class="{
                                'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400': status >= 200 && status < 300,
                                'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400': status >= 300 && status < 400,
                                'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400': status >= 400 && status < 500,
                                'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400': status >= 500
                            }"
                            x-text="'HTTP ' + status + ' ' + (statusText || '')"></span>

                        <span x-show="serverInfo" class="px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-xs font-semibold"
                            x-text="'Server: ' + serverInfo"></span>

                        <span x-show="contentTypeInfo" class="px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-mono text-xs"
                            x-text="contentTypeInfo"></span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" @click="viewMode = viewMode === 'table' ? 'raw' : 'table'"
                            class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors"
                            x-text="viewMode === 'table' ? 'View Raw Headers' : 'View Table'">
                        </button>
                        <button type="button" @click="copyAllHeaders()"
                            class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900 transition-colors">
                            <span x-text="copied ? 'Copied!' : 'Copy Headers'"></span>
                        </button>
                    </div>
                </div>

                <div x-show="locationTarget" class="text-xs text-blue-600 dark:text-blue-400 font-mono flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <span>Redirects to: <strong x-text="locationTarget"></strong></span>
                </div>
            </div>

            <!-- Table View -->
            <div x-show="viewMode === 'table' && headers.length > 0 && !errorMessage" x-cloak class="space-y-4">
                <!-- Header Filter Tabs & Search -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-1">
                    <div class="flex items-center gap-1 overflow-x-auto w-full sm:w-auto text-xs font-semibold">
                        <button type="button" @click="activeCategory = 'all'"
                            class="px-3 py-1.5 rounded-lg transition-colors cursor-pointer"
                            :class="activeCategory === 'all' ? 'bg-blue-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                            All (<span x-text="headers.length"></span>)
                        </button>
                        <button type="button" @click="activeCategory = 'security'"
                            class="px-3 py-1.5 rounded-lg transition-colors cursor-pointer"
                            :class="activeCategory === 'security' ? 'bg-emerald-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                            Security
                        </button>
                        <button type="button" @click="activeCategory = 'caching'"
                            class="px-3 py-1.5 rounded-lg transition-colors cursor-pointer"
                            :class="activeCategory === 'caching' ? 'bg-blue-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                            Caching
                        </button>
                        <button type="button" @click="activeCategory = 'server'"
                            class="px-3 py-1.5 rounded-lg transition-colors cursor-pointer"
                            :class="activeCategory === 'server' ? 'bg-indigo-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'">
                            Server
                        </button>
                    </div>

                    <div class="relative w-full sm:w-56">
                        <input type="text" x-model="searchFilter" placeholder="Filter headers..."
                            class="w-full px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 text-xs font-mono focus:ring-1 focus:ring-blue-600 focus:outline-none">
                    </div>
                </div>

                <!-- Headers Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs font-mono">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-950 text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 w-2/5">Header Name</th>
                                <th class="py-3 px-4">Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
                            <template x-for="(h, idx) in filteredHeaders" :key="idx">
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-950/60 transition-colors">
                                    <td class="py-3 px-4 align-top">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="h.original_name || h.name"></span>
                                            <span x-show="h.category === 'security'" class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400">Security</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-800 dark:text-slate-200 break-all leading-relaxed select-all" x-text="h.val"></td>
                                </tr>
                            </template>
                            <tr x-show="filteredHeaders.length === 0">
                                <td colspan="2" class="py-6 text-center text-slate-400">No matching headers found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Raw Output View -->
            <div x-show="viewMode === 'raw' && !errorMessage" x-cloak class="space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>Raw Response Headers:</span>
                </div>
                <pre class="p-4 rounded-xl bg-slate-950 text-emerald-400 font-mono text-xs overflow-x-auto leading-relaxed border border-slate-800 select-all" x-text="rawHeaders"></pre>
            </div>
        </div>

        <script>
        function httpHeadersApp() {
            return {
                url: 'https://shebafind.site',
                loading: false,
                errorMessage: '',
                status: null,
                statusText: '',
                serverInfo: '',
                contentTypeInfo: '',
                locationTarget: '',
                rawHeaders: '',
                headers: [],
                activeCategory: 'all',
                searchFilter: '',
                viewMode: 'table',
                copied: false,

                init() {
                    this.inspectHeaders();
                },

                get filteredHeaders() {
                    return this.headers.filter(h => {
                        const matchesCategory = this.activeCategory === 'all' || h.category === this.activeCategory;
                        const q = this.searchFilter.toLowerCase().trim();
                        const matchesSearch = !q || h.name.toLowerCase().includes(q) || h.val.toLowerCase().includes(q);
                        return matchesCategory && matchesSearch;
                    });
                },

                async inspectHeaders() {
                    const cleanUrl = this.url.trim();
                    if (!cleanUrl) return;

                    this.loading = true;
                    this.errorMessage = '';
                    this.headers = [];
                    this.status = null;
                    this.statusText = '';
                    this.serverInfo = '';
                    this.contentTypeInfo = '';
                    this.locationTarget = '';
                    this.rawHeaders = '';

                    try {
                        const targetUrl = cleanUrl.startsWith('http') ? cleanUrl : 'https://' + cleanUrl;
                        const response = await fetch('/api/tools/http-headers?url=' + encodeURIComponent(targetUrl));
                        const res = await response.json();

                        if (!response.ok || !res.success) {
                            throw new Error(res.message || 'Unable to inspect HTTP headers. Check domain availability.');
                        }

                        this.status = res.status;
                        this.statusText = res.statusText || 'OK';
                        this.headers = res.headers || [];
                        this.serverInfo = res.server || '';
                        this.contentTypeInfo = res.contentType || '';
                        this.locationTarget = res.location || '';
                        this.rawHeaders = res.raw || '';
                    } catch (e) {
                        this.errorMessage = e.message || 'Inspection failed. Please check the URL and try again.';
                        this.status = null;
                        this.headers = [];
                    } finally {
                        this.loading = false;
                    }
                },

                copyAllHeaders() {
                    if (!this.rawHeaders) return;
                    navigator.clipboard.writeText(this.rawHeaders).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    }).catch(() => {});
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
