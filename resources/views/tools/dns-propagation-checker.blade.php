<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dnsPropagationApp()">
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
                <input type="text" x-model="domain" @keydown.enter="checkAll()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <select x-model="recordType" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-sm font-semibold focus:ring-2 focus:ring-blue-600 focus:outline-none cursor-pointer">
                    <option value="A">A Record (IPv4)</option>
                    <option value="AAAA">AAAA Record (IPv6)</option>
                    <option value="MX">MX Record</option>
                    <option value="TXT">TXT / SPF Record</option>
                    <option value="CNAME">CNAME Record</option>
                    <option value="NS">NS Record</option>
                </select>
                <button type="button" @click="checkAll()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-show="!loading">Check Global Nodes</span>
                    <span x-show="loading">Testing 8 Nodes...</span>
                </button>
            </div>

            <!-- Quick presets & Progress summary -->
            <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-2 text-slate-500">
                    <span>Quick Samples:</span>
                    <button type="button" @click="domain = 'cloudflare.com'; checkAll()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">cloudflare.com</button>
                    <button type="button" @click="domain = 'google.com'; checkAll()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">google.com</button>
                    <button type="button" @click="domain = 'github.com'; checkAll()" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-mono transition-colors">github.com</button>
                </div>
                <div class="font-semibold text-slate-600 dark:text-slate-400" x-show="resolvedCount > 0">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="resolvedCount"></span> / 8 Nodes Propagated
                </div>
            </div>

            <!-- Global Nodes Propagation Table -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead class="bg-slate-50 dark:bg-slate-950">
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-4 font-semibold">Location / Country</th>
                            <th class="py-3 px-4 font-semibold">DNS Resolver</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Resolved Answer</th>
                            <th class="py-3 px-4 text-right font-semibold">Latency</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
                        <template x-for="(node, idx) in nodes" :key="idx">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-100" x-text="node.flag + ' ' + node.location"></td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-300" x-text="node.name"></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-bold text-[11px]"
                                          :class="{
                                              'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800': node.status === 'Resolved',
                                              'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800': node.status === 'Querying...',
                                              'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700': node.status === 'No Record' || node.status === 'Idle',
                                              'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800': node.status === 'Failed'
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="{
                                                  'bg-emerald-500': node.status === 'Resolved',
                                                  'bg-amber-500 animate-pulse': node.status === 'Querying...',
                                                  'bg-slate-400': node.status === 'No Record' || node.status === 'Idle',
                                                  'bg-rose-500': node.status === 'Failed'
                                              }"></span>
                                        <span x-text="node.status"></span>
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200 break-all" x-text="node.answer || '-'"></td>
                                <td class="py-3 px-4 text-right text-slate-500" x-text="node.latency ? node.latency + ' ms' : '-'"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        function dnsPropagationApp() {
            return {
                domain: 'cloudflare.com',
                recordType: 'A',
                loading: false,
                nodes: [
                    { id: 'us-va', flag: '🇺🇸', location: 'USA (Virginia)', name: 'Google Public DNS', status: 'Idle', answer: '', latency: null },
                    { id: 'de-fra', flag: '🇩🇪', location: 'Germany (Frankfurt)', name: 'Cloudflare Edge (DE)', status: 'Idle', answer: '', latency: null },
                    { id: 'sg-sin', flag: '🇸🇬', location: 'Singapore (Asia East)', name: 'Cloudflare AP Node', status: 'Idle', answer: '', latency: null },
                    { id: 'jp-tyo', flag: '🇯🇵', location: 'Japan (Tokyo)', name: 'AliDNS Asia Node', status: 'Idle', answer: '', latency: null },
                    { id: 'gb-lon', flag: '🇬🇧', location: 'United Kingdom (London)', name: 'Cloudflare London', status: 'Idle', answer: '', latency: null },
                    { id: 'au-syd', flag: '🇦🇺', location: 'Australia (Sydney)', name: 'Google Anycast (AU)', status: 'Idle', answer: '', latency: null },
                    { id: 'us-ca', flag: '🇺🇸', location: 'USA (California)', name: 'Google Global Core', status: 'Idle', answer: '', latency: null },
                    { id: 'edge-isp', flag: '🌐', location: 'Authoritative ISP Edge', name: 'System DNS Resolver', status: 'Idle', answer: '', latency: null }
                ],
                get resolvedCount() {
                    return this.nodes.filter(n => n.status === 'Resolved').length;
                },
                init() {
                    this.checkAll();
                },
                async checkAll() {
                    if (!this.domain || !this.domain.trim()) return;
                    this.loading = true;
                    const cleanDomain = this.domain.replace(/^https?:\/\//i, '').replace(/\/.*$/, '').trim();

                    // Reset all nodes
                    this.nodes.forEach(node => {
                        node.status = 'Querying...';
                        node.answer = '';
                        node.latency = null;
                    });

                    // Query nodes concurrently via internal proxy API
                    const promises = this.nodes.map(async (node) => {
                        try {
                            const url = `/api/tools/dns-propagation?node=${encodeURIComponent(node.id)}&domain=${encodeURIComponent(cleanDomain)}&type=${encodeURIComponent(this.recordType)}`;
                            const response = await fetch(url);
                            const res = await response.json();

                            if (response.ok && res.success && res.node) {
                                node.status = res.node.status;
                                node.answer = res.node.answer;
                                node.latency = res.node.latency;
                            } else {
                                node.status = 'No Record';
                                node.answer = 'No answers found';
                            }
                        } catch(e) {
                            node.status = 'Failed';
                            node.answer = 'Network timeout';
                        }
                    });

                    await Promise.all(promises);
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
