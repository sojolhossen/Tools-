<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="reverseDnsApp()">
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
                <input type="text" x-model="ip" @keydown.enter="lookup()" placeholder="8.8.8.8 or 1.1.1.1" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-show="!loading">Lookup rDNS (PTR)</span>
                    <span x-show="loading">Resolving PTR...</span>
                </button>
            </div>

            <!-- Quick presets -->
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span>Quick Samples:</span>
                <button type="button" @click="ip = '8.8.8.8'; lookup()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">8.8.8.8 (Google)</button>
                <button type="button" @click="ip = '1.1.1.1'; lookup()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">1.1.1.1 (Cloudflare)</button>
                <button type="button" @click="ip = '9.9.9.9'; lookup()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">9.9.9.9 (Quad9)</button>
                <button type="button" @click="ip = '208.67.222.222'; lookup()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">208.67.222.222 (OpenDNS)</button>
            </div>

            <!-- Error -->
            <div x-show="error" x-cloak class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="error"></span>
            </div>

            <!-- Result Card -->
            <div x-show="hostname && !error" x-cloak class="p-6 rounded-2xl border bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Resolved Hostname (PTR Record)</span>
                    <button type="button" @click="copyHostname()" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline flex items-center gap-1">
                        <span x-text="copied ? 'Copied!' : 'Copy Hostname'"></span>
                    </button>
                </div>
                <div class="text-2xl sm:text-3xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all select-all" x-text="hostname"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 border-t border-slate-200 dark:border-slate-800/80 text-xs font-mono text-slate-500">
                    <div>
                        <span class="block text-slate-400 uppercase text-[10px] font-sans font-semibold">ARPA Pointer Domain</span>
                        <span class="text-slate-700 dark:text-slate-300 break-all" x-text="arpaQuery || '-'"></span>
                    </div>
                    <div>
                        <span class="block text-slate-400 uppercase text-[10px] font-sans font-semibold">Queried Target IP</span>
                        <span class="text-slate-700 dark:text-slate-300 font-bold" x-text="queriedIp || ip"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function reverseDnsApp() {
            return {
                ip: '8.8.8.8',
                queriedIp: '',
                hostname: '',
                arpaQuery: '',
                loading: false,
                error: null,
                copied: false,
                init() {
                    this.lookup();
                },
                copyHostname() {
                    if (!this.hostname) return;
                    navigator.clipboard.writeText(this.hostname);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                },
                async lookup() {
                    if (!this.ip || !this.ip.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.hostname = '';
                    this.queriedIp = this.ip.trim();

                    try {
                        const response = await fetch('/api/tools/rdns-lookup?ip=' + encodeURIComponent(this.queriedIp));
                        const res = await response.json();

                        if (response.ok && res.success) {
                            this.hostname = res.hostname || '';
                            this.arpaQuery = res.arpa || '';
                        } else {
                            this.error = res.message || 'No PTR (reverse DNS) record found for this IP address.';
                        }
                    } catch (e) {
                        this.error = 'Failed to connect to Reverse DNS service. Please check network connection.';
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
