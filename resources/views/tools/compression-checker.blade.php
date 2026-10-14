<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="compressionCheckApp()">
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
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Target Website URL or Domain</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-sm">⚡</span>
                        <input type="text" x-model="url" @keydown.enter="testCompression()" placeholder="https://example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                    </div>
                    <button type="button" @click="testCompression()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-show="!loading">Check Compression</span>
                        <span x-show="loading">Testing Live URL...</span>
                    </button>
                </div>
                <!-- Presets -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-slate-500">Quick Test:</span>
                    <button type="button" @click="setPreset('https://shebafind.site')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">shebafind.site</button>
                    <button type="button" @click="setPreset('https://cloudflare.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">cloudflare.com</button>
                    <button type="button" @click="setPreset('https://google.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">google.com</button>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-sm flex items-start gap-3">
                <span class="text-lg">⚠️</span>
                <div>
                    <strong class="font-semibold block">Test Failed</strong>
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="animate-pulse space-y-4">
                <div class="h-24 bg-slate-100 dark:bg-slate-800 rounded-2xl"></div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="h-20 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                    <div class="h-20 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                    <div class="h-20 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                </div>
            </div>

            <!-- Results Section -->
            <div x-show="!loading && audited" x-cloak class="space-y-6">
                <!-- Result Card -->
                <div class="p-6 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all"
                    :class="enabled ? 'bg-emerald-50/70 dark:bg-emerald-950/30 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-100' : 'bg-rose-50/70 dark:bg-rose-950/30 border-rose-300 dark:border-rose-800 text-rose-900 dark:text-rose-100'">
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                            :class="enabled ? 'bg-emerald-200/60 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300' : 'bg-rose-200/60 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300'">
                            HTTP Content-Encoding Status
                        </span>
                        <div class="text-2xl font-black tracking-tight" x-text="enabled ? encoding + ' Active' : 'No Compression Detected'"></div>
                        <div class="text-xs font-mono opacity-80" x-text="testedUrl + (serverName ? ' • ' + serverName : '')"></div>
                    </div>
                    <div class="flex flex-col items-center justify-center px-6 py-3 rounded-xl bg-white/60 dark:bg-slate-900/60 border border-current/20">
                        <span class="text-xs font-bold uppercase tracking-wider opacity-70">Saved</span>
                        <div class="text-3xl font-mono font-bold"
                            :class="enabled ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500'"
                            x-text="savedPercent"></div>
                    </div>
                </div>

                <!-- Metrics Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-mono">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Uncompressed Size</span>
                        <span class="text-base font-bold text-slate-900 dark:text-slate-100" x-text="origSize + ' KB'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Transfer Size (Over the Wire)</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400" x-text="compSize + ' KB'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Bandwidth Savings</span>
                        <span class="text-base font-bold text-emerald-600 dark:text-emerald-400" x-text="savedPercent + ' Reduction'"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function compressionCheckApp() {
            return {
                url: 'https://shebafind.site',
                testedUrl: '',
                loading: false,
                audited: false,
                errorMessage: '',
                enabled: true,
                encoding: '',
                origSize: '0',
                compSize: '0',
                savedPercent: '0%',
                serverName: '',

                init() {
                    this.testCompression();
                },

                setPreset(target) {
                    this.url = target;
                    this.testCompression();
                },

                async testCompression() {
                    let raw = (this.url || '').trim();
                    if (!raw) {
                        this.errorMessage = 'Please enter a URL to check.';
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
                        const res = await fetch('/api/tools/compression-check?url=' + encodeURIComponent(raw));
                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Failed to inspect compression.');
                        }

                        this.testedUrl = data.url;
                        this.enabled = data.enabled;
                        this.encoding = data.encoding;
                        this.origSize = data.uncompressedKB;
                        this.compSize = data.compressedKB;
                        this.savedPercent = data.savedPercent;
                        this.serverName = data.server || '';
                        this.audited = true;
                    } catch (err) {
                        this.errorMessage = err.message || 'Unable to check compression. Ensure the website is reachable.';
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
