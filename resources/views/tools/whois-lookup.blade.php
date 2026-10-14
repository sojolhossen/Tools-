<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="whoisLookupApp()">
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
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="domain" @keydown.enter="lookup()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-show="!loading">Query WHOIS / RDAP</span>
                    <span x-show="loading">Querying Registry...</span>
                </button>
            </div>

            <!-- Error -->
            <div x-show="error" x-cloak class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="error"></span>
            </div>

            <!-- Results -->
            <div x-show="data" x-cloak class="space-y-6">
                <!-- Header with Domain & Quick Status -->
                <div class="flex flex-wrap items-center justify-between gap-3 pb-4 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <span class="text-xs text-slate-500 uppercase tracking-wider block font-semibold">Domain Queried</span>
                        <h2 class="text-xl font-bold font-mono text-slate-900 dark:text-white" x-text="data?.domain || domain"></h2>
                    </div>
                    <div class="flex flex-wrap gap-1.5" x-show="status && status.length">
                        <template x-for="(st, idx) in status.slice(0, 3)" :key="idx">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50" x-text="st"></span>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Registration Date</span>
                        <span class="font-mono text-sm font-bold text-slate-900 dark:text-slate-100" x-text="formatDate(created)"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Expiration Date</span>
                        <span class="font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="formatDate(expires)"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Registrar</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate block" x-text="registrar || '-'"></span>
                    </div>
                </div>

                <!-- Nameservers -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase tracking-wider block mb-2 font-semibold">Authoritative Nameservers</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(ns, i) in nameservers" :key="i">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-200/70 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono text-xs border border-slate-300/60 dark:border-slate-700/60" x-text="ns"></span>
                        </template>
                        <span x-show="!nameservers || !nameservers.length" class="text-xs text-slate-500 italic">No nameserver records found</span>
                    </div>
                </div>

                <!-- Status Codes (if available) -->
                <div x-show="status && status.length > 0" class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase tracking-wider block mb-2 font-semibold">Registry Status Codes</span>
                    <div class="flex flex-wrap gap-1.5">
                        <template x-for="(st, i) in status" :key="i">
                            <span class="px-2 py-0.5 rounded text-xs font-mono bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-900/40" x-text="st"></span>
                        </template>
                    </div>
                </div>

                <!-- Raw RDAP / WHOIS Toggle -->
                <div class="pt-2">
                    <button type="button" @click="showRaw = !showRaw" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1 font-medium">
                        <span x-text="showRaw ? 'Hide Raw RDAP / WHOIS Data' : 'View Raw RDAP / WHOIS Data'"></span>
                        <svg class="w-3.5 h-3.5 transform transition-transform" :class="showRaw ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showRaw" x-cloak class="mt-3">
                        <pre class="p-4 rounded-xl bg-slate-950 text-slate-200 text-xs font-mono overflow-x-auto max-h-80 border border-slate-800" x-text="JSON.stringify(data?.raw || data, null, 2)"></pre>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function whoisLookupApp() {
            return {
                domain: 'github.com',
                loading: false,
                data: null,
                created: '',
                expires: '',
                updated: '',
                registrar: '',
                nameservers: [],
                status: [],
                showRaw: false,
                error: null,
                init() {
                    this.lookup();
                },
                formatDate(isoStr) {
                    if (!isoStr) return '-';
                    try {
                        const d = new Date(isoStr);
                        if (isNaN(d.getTime())) return isoStr;
                        return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
                    } catch (e) {
                        return isoStr;
                    }
                },
                async lookup() {
                    if (!this.domain || !this.domain.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.data = null;
                    this.showRaw = false;

                    const clean = this.domain.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();

                    try {
                        const response = await fetch('/api/tools/whois?domain=' + encodeURIComponent(clean));
                        const res = await response.json();

                        if (response.ok && res.success) {
                            this.data = res;
                            this.created = res.created || '';
                            this.expires = res.expires || '';
                            this.updated = res.updated || '';
                            this.registrar = res.registrar || '';
                            this.nameservers = res.nameservers || [];
                            this.status = res.status || [];
                        } else {
                            this.error = res.message || 'Unable to retrieve WHOIS/RDAP details for this domain.';
                        }
                    } catch (e) {
                        this.error = 'WHOIS service query failed. Please check network connection.';
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
