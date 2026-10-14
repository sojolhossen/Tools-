<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="spfDkimApp()">
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
                    <span>Server-Verified DNS</span>
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
            <!-- Search Form -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <input type="text" x-model="domain" @keydown.enter="auditEmailRecords()" placeholder="example.com" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <input type="text" x-model="dkimSelector" @keydown.enter="auditEmailRecords()" placeholder="DKIM Selector (optional)" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <button type="button" @click="auditEmailRecords()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                    <svg x-show="loading" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="loading ? 'Auditing Records...' : 'Audit Email Security'"></span>
                </button>
            </div>

            <!-- Quick Presets -->
            <div class="flex items-center flex-wrap gap-2 pt-1 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-medium">Quick Test:</span>
                <button type="button" @click="domain = 'google.com'; dkimSelector = '20230601'; auditEmailRecords()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">google.com</button>
                <button type="button" @click="domain = 'cloudflare.com'; dkimSelector = ''; auditEmailRecords()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">cloudflare.com</button>
                <button type="button" @click="domain = 'github.com'; dkimSelector = ''; auditEmailRecords()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">github.com</button>
                <button type="button" @click="domain = 'microsoft.com'; dkimSelector = ''; auditEmailRecords()" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 font-mono text-slate-700 dark:text-slate-300 transition-colors">microsoft.com</button>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" x-cloak class="p-4 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 text-sm">
                <span x-text="errorMessage"></span>
            </div>

            <!-- Result Section -->
            <div x-show="data" x-cloak class="space-y-6 pt-2">
                <!-- Overall Security Grade Banner -->
                <div class="p-5 rounded-2xl border transition-all"
                    :class="{
                        'bg-emerald-50/60 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-900/60 text-emerald-900 dark:text-emerald-100': data?.grade_color === 'emerald',
                        'bg-blue-50/60 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900/60 text-blue-900 dark:text-blue-100': data?.grade_color === 'blue',
                        'bg-amber-50/60 dark:bg-amber-950/30 border-amber-200 dark:border-amber-900/60 text-amber-900 dark:text-amber-100': data?.grade_color === 'amber',
                        'bg-rose-50/60 dark:bg-rose-950/30 border-rose-200 dark:border-rose-900/60 text-rose-900 dark:text-rose-100': data?.grade_color === 'rose'
                    }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-extrabold text-2xl shadow-sm"
                                :class="{
                                    'bg-emerald-600 text-white': data?.grade_color === 'emerald',
                                    'bg-blue-600 text-white': data?.grade_color === 'blue',
                                    'bg-amber-500 text-white': data?.grade_color === 'amber',
                                    'bg-rose-600 text-white': data?.grade_color === 'rose'
                                }"
                                x-text="data?.score || '?'"></div>
                            <div>
                                <h3 class="font-bold text-base sm:text-lg" x-text="data?.grade_text || 'Email Security Audit Completed'"></h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                                    Audited Domain: <span class="font-mono font-semibold" x-text="data?.domain"></span>
                                </p>
                            </div>
                        </div>

                        <!-- Mini Badges -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold"
                                :class="data?.spf?.found ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300'">
                                SPF: <span x-text="data?.spf?.found ? 'Active' : 'Missing'"></span>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold"
                                :class="data?.dmarc?.found ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-900/60 text-rose-800 dark:text-rose-300'">
                                DMARC: <span x-text="data?.dmarc?.found ? data?.dmarc?.policy?.toUpperCase() : 'Missing'"></span>
                            </span>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold"
                                :class="data?.dkim?.found ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300'">
                                DKIM: <span x-text="data?.dkim?.found ? 'Verified' : 'Not Found'"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- 1. SPF Record Card -->
                <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" :class="data?.spf?.found ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                SPF Record (Sender Policy Framework)
                            </h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"
                            :class="data?.spf?.status === 'valid' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : (data?.spf?.status === 'warning' ? 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400')"
                            x-text="data?.spf?.status_text"></span>
                    </div>

                    <div class="relative group">
                        <div class="font-mono text-xs font-semibold text-slate-800 dark:text-slate-200 break-all p-3.5 pr-20 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 leading-relaxed shadow-xs"
                            x-text="data?.spf?.record || 'No v=spf1 TXT record found on apex domain.'"></div>
                        <button type="button" x-show="data?.spf?.record" @click="copyText(data?.spf?.record, 'spfCopied')"
                            class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            <span x-text="copiedState === 'spfCopied' ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>

                    <div x-show="data?.spf?.found" class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Enforcement Policy:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="data?.spf?.qualifier_label || data?.spf?.qualifier || 'Default'"></span>
                        </div>
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Authorized Includes:</span>
                            <div class="font-mono text-slate-800 dark:text-slate-200 flex flex-wrap gap-1">
                                <template x-for="inc in data?.spf?.includes || []" :key="inc">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-[11px]" x-text="inc"></span>
                                </template>
                                <span x-show="!data?.spf?.includes?.length" class="text-slate-400">None</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. DMARC Policy Card -->
                <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" :class="data?.dmarc?.status === 'protected' ? 'bg-emerald-500' : (data?.dmarc?.status === 'monitoring' ? 'bg-blue-500' : 'bg-rose-500')"></span>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                                DMARC Policy (_dmarc.domain)
                            </h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"
                            :class="data?.dmarc?.status === 'protected' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : (data?.dmarc?.status === 'monitoring' ? 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400')"
                            x-text="data?.dmarc?.status_text"></span>
                    </div>

                    <div class="relative group">
                        <div class="font-mono text-xs font-semibold text-slate-800 dark:text-slate-200 break-all p-3.5 pr-20 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 leading-relaxed shadow-xs"
                            x-text="data?.dmarc?.record || 'No v=DMARC1 TXT record found at _dmarc host.'"></div>
                        <button type="button" x-show="data?.dmarc?.record" @click="copyText(data?.dmarc?.record, 'dmarcCopied')"
                            class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            <span x-text="copiedState === 'dmarcCopied' ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>

                    <div x-show="data?.dmarc?.found" class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1 text-xs">
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">DMARC Policy:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="data?.dmarc?.policy_label"></span>
                        </div>
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Enforcement:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="(data?.dmarc?.percentage || 100) + '% of emails'"></span>
                        </div>
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Reporting Mail (rua):</span>
                            <span class="font-mono text-slate-800 dark:text-slate-200 truncate block" x-text="data?.dmarc?.rua || 'Not specified'"></span>
                        </div>
                    </div>
                </div>

                <!-- 3. DKIM Record Card -->
                <div class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" :class="data?.dkim?.found ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white"
                                x-text="'DKIM Key (' + (data?.dkim?.selector || 'default') + '._domainkey)'">
                            </h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5"
                            :class="data?.dkim?.found ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300'"
                            x-text="data?.dkim?.status_text"></span>
                    </div>

                    <div class="relative group">
                        <div class="font-mono text-xs font-semibold text-slate-800 dark:text-slate-200 break-all p-3.5 pr-20 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 leading-relaxed shadow-xs"
                            x-text="data?.dkim?.record || 'No public key found for this selector.'"></div>
                        <button type="button" x-show="data?.dkim?.record" @click="copyText(data?.dkim?.record, 'dkimCopied')"
                            class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            <span x-text="copiedState === 'dkimCopied' ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>

                    <div x-show="data?.dkim?.found" class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-xs">
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Key Algorithm:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200 uppercase" x-text="data?.dkim?.key_type || 'RSA'"></span>
                        </div>
                        <div class="p-3 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800/80">
                            <span class="text-slate-500 dark:text-slate-400 block font-medium mb-1">Active Selector:</span>
                            <span class="font-mono font-semibold text-slate-800 dark:text-slate-200" x-text="data?.dkim?.selector"></span>
                        </div>
                    </div>
                </div>

                <!-- 4. MX Records Card -->
                <div x-show="data?.mx && data?.mx?.length" class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-white">
                            Configured Mail Servers (MX Records)
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <template x-for="mx in data?.mx || []" :key="mx.host">
                            <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
                                <span class="font-mono text-slate-800 dark:text-slate-200 truncate" x-text="mx.host"></span>
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-mono text-[11px]" x-text="'Pri ' + mx.priority"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function spfDkimApp() {
            return {
                domain: 'google.com',
                dkimSelector: '20230601',
                loading: false,
                errorMessage: '',
                data: null,
                copiedState: null,
                init() {
                    this.auditEmailRecords();
                },
                async auditEmailRecords() {
                    const d = this.domain.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();
                    if (!d) return;

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const sel = this.dkimSelector.trim();
                        const url = '/api/tools/spf-dkim-dmarc?domain=' + encodeURIComponent(d) + (sel ? '&selector=' + encodeURIComponent(sel) : '');
                        const res = await fetch(url);
                        const json = await res.json();

                        if (!res.ok || !json.success) {
                            this.errorMessage = json.message || 'Unable to audit email security records. Please check the domain.';
                            this.data = null;
                        } else {
                            this.data = json;
                        }
                    } catch (e) {
                        this.errorMessage = 'Network error contacting audit service. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                },
                copyText(text, key) {
                    if (!text) return;
                    navigator.clipboard.writeText(text).then(() => {
                        this.copiedState = key;
                        setTimeout(() => {
                            if (this.copiedState === key) this.copiedState = null;
                        }, 2000);
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
