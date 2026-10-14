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
                <select x-model="recordType" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-sm font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none cursor-pointer">
                    <option value="A">A (IPv4)</option>
                    <option value="AAAA">AAAA (IPv6)</option>
                    <option value="CNAME">CNAME</option>
                    <option value="MX">MX (Mail)</option>
                    <option value="TXT">TXT / SPF / Verification</option>
                    <option value="NS">NS (Name Server)</option>
                    <option value="SOA">SOA</option>
                    <option value="CAA">CAA</option>
                    <option value="PTR">PTR</option>
                    <option value="SRV">SRV</option>
                </select>
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-show="!loading">Query DNS</span>
                    <span x-show="loading">Resolving...</span>
                </button>
            </div>

            <!-- Error -->
            <div x-show="error" x-cloak class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 text-rose-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="error"></span>
            </div>

            <!-- DNS Records Table -->
            <div x-show="records.length > 0" x-cloak class="space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                    <span>Found <strong class="text-slate-900 dark:text-white" x-text="records.length"></strong> record(s)</span>
                    <button type="button" @click="copyAll()" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold flex items-center gap-1">
                        <span x-text="copiedAll ? 'Copied!' : 'Copy All Results'"></span>
                    </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs font-mono">
                        <thead class="bg-slate-50 dark:bg-slate-950">
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 uppercase tracking-wider text-[11px]">
                                <th class="py-3 px-4 font-semibold">Host / Name</th>
                                <th class="py-3 px-4 font-semibold">Type</th>
                                <th class="py-3 px-4 font-semibold">TTL</th>
                                <th class="py-3 px-4 font-semibold">Target / Data Value</th>
                                <th class="py-3 px-4 text-right font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
                            <template x-for="(r, idx) in records" :key="idx">
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-950/60 transition-colors">
                                    <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-100" x-text="r.name"></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300" x-text="r.type || recordType"></span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500" x-text="(r.TTL || 300) + 's'"></td>
                                    <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200 break-all" x-text="r.data"></td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button" @click="copyValue(r.data)" class="px-2.5 py-1 rounded-lg text-[11px] font-sans font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                            Copy
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="!loading && searched && records.length === 0 && !error" x-cloak class="text-center py-8 text-slate-500 text-sm">
                No <span class="font-bold text-slate-700 dark:text-slate-300" x-text="recordType"></span> records found for this domain.
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
                copiedAll: false,
                init() {
                    this.lookup();
                },
                copyValue(val) {
                    navigator.clipboard.writeText(val);
                },
                copyAll() {
                    if (this.records.length === 0) return;
                    const text = this.records.map(r => `${r.name}\t${r.type || this.recordType}\t${r.TTL || 300}\t${r.data}`).join('\n');
                    navigator.clipboard.writeText(text);
                    this.copiedAll = true;
                    setTimeout(() => this.copiedAll = false, 2000);
                },
                async lookup() {
                    if (!this.domain || !this.domain.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.records = [];
                    this.searched = true;
                    try {
                        const cleanDomain = this.domain.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();
                        const url = '/api/tools/dns-lookup?name=' + encodeURIComponent(cleanDomain) + '&type=' + encodeURIComponent(this.recordType);
                        const response = await fetch(url);
                        const res = await response.json();

                        if (response.ok && res.success) {
                            this.records = res.records || [];
                        } else if (res.status === 3 || response.status === 404) {
                            this.error = res.message || 'NXDOMAIN: Domain does not exist or has no active DNS zone.';
                        } else {
                            this.error = res.message || 'Unable to retrieve DNS records for this domain.';
                        }
                    } catch (e) {
                        this.error = 'Failed to resolve DNS query. Please check your network connection.';
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
