<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="securityHeadersApp()">
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
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-sm">🌐</span>
                        <input type="text" x-model="url" @keydown.enter="audit()" placeholder="https://example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                    </div>
                    <button type="button" @click="audit()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-show="!loading">Audit Security</span>
                        <span x-show="loading">Auditing Live Headers...</span>
                    </button>
                </div>
                <!-- Presets -->
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span class="text-slate-500">Quick Test:</span>
                    <button type="button" @click="setPreset('https://shebafind.site')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">shebafind.site</button>
                    <button type="button" @click="setPreset('https://cloudflare.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">cloudflare.com</button>
                    <button type="button" @click="setPreset('https://google.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">google.com</button>
                    <button type="button" @click="setPreset('https://github.com')" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-950/60 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors font-mono">github.com</button>
                </div>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 text-sm flex items-start gap-3">
                <span class="text-lg">⚠️</span>
                <div>
                    <strong class="font-semibold block">Audit Failed</strong>
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <!-- Loading Skeleton -->
            <div x-show="loading" class="animate-pulse space-y-4">
                <div class="h-28 bg-slate-100 dark:bg-slate-800 rounded-2xl"></div>
                <div class="space-y-3">
                    <div class="h-16 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                    <div class="h-16 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                    <div class="h-16 bg-slate-100 dark:bg-slate-800 rounded-xl"></div>
                </div>
            </div>

            <!-- Results Section -->
            <div x-show="!loading && audited" x-cloak class="space-y-6">
                <!-- Grade Summary Card -->
                <div class="p-6 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all"
                    :class="{
                        'bg-emerald-50/70 dark:bg-emerald-950/30 border-emerald-300 dark:border-emerald-800 text-emerald-900 dark:text-emerald-100': grade === 'A+' || grade === 'A',
                        'bg-amber-50/70 dark:bg-amber-950/30 border-amber-300 dark:border-amber-800 text-amber-900 dark:text-amber-100': grade === 'B' || grade === 'C',
                        'bg-rose-50/70 dark:bg-rose-950/30 border-rose-300 dark:border-rose-800 text-rose-900 dark:text-rose-100': grade === 'D' || grade === 'F'
                    }">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded-full"
                                :class="{
                                    'bg-emerald-200/60 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300': grade === 'A+' || grade === 'A',
                                    'bg-amber-200/60 dark:bg-amber-900/60 text-amber-800 dark:text-amber-300': grade === 'B' || grade === 'C',
                                    'bg-rose-200/60 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300': grade === 'D' || grade === 'F'
                                }">
                                Real-Time Live Audit
                            </span>
                            <span class="text-xs font-mono opacity-80" x-text="targetUrl"></span>
                        </div>
                        <div class="text-2xl font-black tracking-tight" x-text="'Security Rating: ' + grade"></div>
                        <p class="text-xs sm:text-sm opacity-90 max-w-xl" x-text="gradeTitle"></p>
                        <div class="flex items-center gap-4 pt-1 text-xs opacity-80">
                            <span x-text="passedCount + ' of ' + totalCount + ' Core Headers Configured'"></span>
                            <span x-show="serverName" x-text="'Server: ' + serverName"></span>
                            <span x-show="statusCode" x-text="'Status: HTTP ' + statusCode"></span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center justify-center px-6 py-4 rounded-xl bg-white/60 dark:bg-slate-900/60 border border-current/20 min-w-[120px]">
                        <span class="text-xs font-bold uppercase tracking-wider opacity-70">Grade</span>
                        <div class="text-5xl font-black font-mono tracking-tight"
                            :class="{
                                'text-emerald-600 dark:text-emerald-400': grade === 'A+' || grade === 'A',
                                'text-amber-600 dark:text-amber-400': grade === 'B' || grade === 'C',
                                'text-rose-600 dark:text-rose-400': grade === 'D' || grade === 'F'
                            }"
                            x-text="grade"></div>
                    </div>
                </div>

                <!-- Detailed Headers Checklist -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Core HTTP Security Headers
                        </h3>
                        <span class="text-xs text-slate-500 font-mono" x-text="passedCount + '/' + totalCount + ' Protected'"></span>
                    </div>

                    <div class="space-y-3">
                        <template x-for="h in checklist" :key="h.key">
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 transition-all hover:border-slate-300 dark:hover:border-slate-700">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-mono text-sm font-bold text-slate-900 dark:text-slate-100" x-text="h.name"></span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"
                                                :class="h.present ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400 border border-rose-300 dark:border-rose-800'"
                                                x-text="h.present ? 'Configured' : 'Missing'">
                                            </span>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-200/80 dark:bg-slate-800 text-slate-600 dark:text-slate-400"
                                                x-text="'Severity: ' + h.severity">
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed" x-text="h.desc"></p>
                                    </div>
                                    <div class="text-right sm:max-w-xs w-full">
                                        <div class="text-[11px] font-mono px-2.5 py-1.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 break-all select-all inline-block"
                                            :class="!h.present && 'italic text-rose-600 dark:text-rose-400'"
                                            x-text="h.value">
                                        </div>
                                    </div>
                                </div>

                                <!-- Recommendation if missing -->
                                <div x-show="!h.present && h.advice" class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-800/60 text-xs text-amber-700 dark:text-amber-400 flex items-start gap-1.5 bg-amber-50/50 dark:bg-amber-950/20 p-2.5 rounded-lg">
                                    <span class="shrink-0">💡</span>
                                    <div>
                                        <strong class="font-semibold">Recommendation: </strong>
                                        <span x-text="h.advice"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Extra Information & Actions -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 text-xs">
                    <div class="text-slate-500">
                        <span x-show="xxss" class="font-mono" x-text="'Legacy X-XSS-Protection: ' + xxss"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="copyJson()" class="px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium transition-colors flex items-center gap-1.5">
                            <span x-text="copied ? 'Copied!' : 'Copy Audit JSON'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function securityHeadersApp() {
            return {
                url: 'https://shebafind.site',
                loading: false,
                audited: false,
                errorMessage: '',
                targetUrl: '',
                statusCode: null,
                serverName: '',
                grade: 'A',
                gradeTitle: '',
                passedCount: 0,
                totalCount: 6,
                checklist: [],
                xxss: null,
                copied: false,

                init() {
                    this.audit();
                },

                setPreset(target) {
                    this.url = target;
                    this.audit();
                },

                async audit() {
                    let raw = (this.url || '').trim();
                    if (!raw) {
                        this.errorMessage = 'Please enter a website URL or domain name.';
                        return;
                    }

                    // Clean input if user accidentally ends domain with a trailing dot
                    raw = raw.replace(/\.+$/, '');
                    if (!raw.startsWith('http://') && !raw.startsWith('https://')) {
                        raw = 'https://' + raw;
                    }
                    this.url = raw;

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const res = await fetch('/api/tools/security-headers?url=' + encodeURIComponent(raw));
                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || 'Failed to inspect security headers.');
                        }

                        this.targetUrl = data.url;
                        this.statusCode = data.status;
                        this.serverName = data.server || '';
                        this.grade = data.grade || 'C';
                        this.gradeTitle = data.gradeTitle || '';
                        this.passedCount = data.passedCount || 0;
                        this.totalCount = data.totalCount || 6;
                        this.checklist = data.checklist || [];
                        this.xxss = data.xxss || null;
                        this.audited = true;
                    } catch (err) {
                        this.errorMessage = err.message || 'Unable to connect to website. Please ensure domain is online and correct.';
                    } finally {
                        this.loading = false;
                    }
                },

                copyJson() {
                    const payload = {
                        url: this.targetUrl,
                        status: this.statusCode,
                        server: this.serverName,
                        grade: this.grade,
                        passed: this.passedCount + '/' + this.totalCount,
                        headers: this.checklist
                    };
                    navigator.clipboard.writeText(JSON.stringify(payload, null, 2));
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
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
