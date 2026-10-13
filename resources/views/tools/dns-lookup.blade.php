<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dnsLookupApp()">
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
                <select x-model="recordType" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-sm font-semibold">
                    <option value="A">A (IPv4)</option>
                    <option value="AAAA">AAAA (IPv6)</option>
                    <option value="CNAME">CNAME</option>
                    <option value="MX">MX (Mail)</option>
                    <option value="TXT">TXT / SPF</option>
                    <option value="NS">NS (Name Server)</option>
                    <option value="SOA">SOA</option>
                    <option value="CAA">CAA</option>
                </select>
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Query DNS</span>
                    <span x-show="loading">Resolving...</span>
                </button>
            </div>

            <!-- Error -->
            <div x-show="error" x-text="error" class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm"></div>

            <!-- DNS Records Table -->
            <div x-show="records.length > 0" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 uppercase">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">TTL</th>
                            <th class="py-3 px-4">Data / Target Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        <template x-for="(r, idx) in records" :key="idx">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-100" x-text="r.name"></td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold" x-text="recordType"></span></td>
                                <td class="py-3 px-4 text-slate-500" x-text="r.TTL + 's'"></td>
                                <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200 break-all" x-text="r.data"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="!loading && searched && records.length === 0 && !error" class="text-center py-8 text-slate-500 text-sm">
                No DNS records found for this domain and type.
            </div>
        </div>

        <script>
        function dnsLookupApp() {
            return {
                domain: 'google.com',
                recordType: 'A',
                loading: false,
                searched: false,
                records: [],
                error: null,
                init() {
                    this.lookup();
                },
                async lookup() {
                    if (!this.domain.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.records = [];
                    this.searched = true;
                    try {
                        const cleanDomain = this.domain.replace(/^https?:\/\//, '').replace(/\/.*$/, '').trim();
                        const url = 'https://dns.google/resolve?name=' + encodeURIComponent(cleanDomain) + '&type=' + this.recordType;
                        const res = await fetch(url).then(r => r.json());
                        if (res.Answer && Array.isArray(res.Answer)) {
                            this.records = res.Answer;
                        } else if (res.Status === 3) {
                            this.error = 'NXDOMAIN: Domain does not exist.';
                        } else {
                            this.records = [];
                        }
                    } catch (e) {
                        this.error = 'Failed to resolve DNS over HTTPS query.';
                    }
                    this.loading = false;
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
