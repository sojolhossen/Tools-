<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="redirectCheckerApp()">
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
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Target URL to Trace</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-sm">🔀</span>
                        <input type="text" x-model="url" @keydown.enter="traceRedirects()" placeholder="http://example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                    </div>
                    <button type="button" @click="traceRedirects()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-show="!loading">Trace Redirects</span>
                        <span x-show="loading">Tracing Chain...</span>
                    </button>
                </div>
                <!-- Presets -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-slate-500">Quick Test:</span>
                    <button type="button" @click="setPreset('http://github.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">http://github.com</button>
                    <button type="button" @click="setPreset('http://google.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">http://google.com</button>
                    <button type="button" @click="setPreset('http://shebafind.site')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">http://shebafind.site</button>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-sm flex items-start gap-3">
                <span class="text-lg">⚠️</span>
                <div>
                    <strong class="font-semibold block">Trace Failed</strong>
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="animate-pulse space-y-4">
                <div class="h-20 bg-slate-100 dark:bg-slate-800 rounded-2xl"></div>
                <div class="h-16 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                <div class="h-16 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
            </div>

            <!-- Results Section -->
            <div x-show="!loading && audited" x-cloak class="space-y-6">
                <!-- Summary Card -->
                <div class="p-6 rounded-2xl border bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Redirect Summary</span>
                        <div class="text-xl font-bold text-slate-900 dark:text-white mt-1">
                            <span x-text="hopCount > 1 ? hopCount + ' Hops in Redirect Chain' : 'Direct Response (No Redirects)'"></span>
                        </div>
                        <div class="text-xs font-mono text-slate-500 mt-1 flex items-center gap-1.5 break-all">
                            <span>Final Destination:</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400" x-text="finalUrl"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 rounded-xl font-mono text-xs font-bold"
                            :class="hopCount > 1 ? 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 border border-blue-300 dark:border-blue-800' : 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800'"
                            x-text="hopCount > 1 ? (hopCount - 1) + ' Redirect(s)' : 'Direct 200'">
                        </span>
                    </div>
                </div>

                <!-- Redirect Chain Stepper -->
                <div class="space-y-3 pt-1">
                    <template x-for="(hop, idx) in chain" :key="idx">
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 transition-all">
                            <div class="flex items-start gap-4">
                                <div class="flex flex-col items-center">
                                    <span class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center shrink-0"
                                        :class="hop.status >= 200 && hop.status < 300 ? 'bg-emerald-600' : (hop.status >= 300 && hop.status < 400 ? 'bg-blue-600' : 'bg-rose-600')"
                                        x-text="idx + 1">
                                    </span>
                                </div>
                                <div class="flex-1 space-y-1.5 overflow-hidden">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded text-xs font-mono font-bold"
                                            :class="hop.status >= 200 && hop.status < 300 ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : (hop.status >= 300 && hop.status < 400 ? 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800')"
                                            x-text="'HTTP ' + hop.status">
                                        </span>
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300" x-text="hop.statusText"></span>
                                        <span x-show="hop.isFinal" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">Final Landing Page</span>
                                        <span x-show="hop.server" class="text-[11px] font-mono text-slate-500" x-text="'Server: ' + hop.server"></span>
                                    </div>
                                    <div class="font-mono text-xs font-bold text-slate-900 dark:text-slate-100 break-all select-all" x-text="hop.url"></div>
                                    <div x-show="hop.location" class="text-xs text-slate-500 flex items-center gap-1.5 font-mono pt-1 break-all">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">↳ Location:</span>
                                        <span x-text="hop.location"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <script>
        function redirectCheckerApp() {
            return {
                url: 'http://github.com',
                loading: false,
                audited: false,
                errorMessage: '',
                finalUrl: '',
                hopCount: 0,
                chain: [],

                init() {
                    this.traceRedirects();
                },

                setPreset(target) {
                    this.url = target;
                    this.traceRedirects();
                },

                async traceRedirects() {
                    let raw = (this.url || '').trim();
                    if (!raw) {
                        this.errorMessage = 'Please enter a URL to trace.';
                        return;
                    }

                    if (!raw.startsWith('http://') && !raw.startsWith('https://')) {
                        raw = 'http://' + raw;
                    }
                    this.url = raw;

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const res = await fetch('/api/tools/redirect-trace?url=' + encodeURIComponent(raw));
                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Failed to trace redirects.');
                        }

                        this.finalUrl = data.finalUrl;
                        this.hopCount = data.hopCount;
                        this.chain = data.chain || [];
                        this.audited = true;
                    } catch (err) {
                        this.errorMessage = err.message || 'Unable to complete redirect trace. Check if URL is valid and accessible.';
                    } finally {
                        this.loading = false;
                    }
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
