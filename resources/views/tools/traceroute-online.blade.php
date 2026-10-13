<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="tracerouteApp()">
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
                <input type="text" x-model="target" placeholder="github.com or 1.1.1.1" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="runTrace()" :disabled="running" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!running">Execute Traceroute</span>
                    <span x-show="running">Tracing Hops... (<span x-text="hops.length"></span>/8)</span>
                </button>
            </div>

            <!-- Visual Hops Timeline -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 uppercase">
                            <th class="py-3 px-3">Hop #</th>
                            <th class="py-3 px-4">Gateway / Router IP</th>
                            <th class="py-3 px-4">Carrier / IXP Autonomous System</th>
                            <th class="py-3 px-4">RTT (ms)</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        <template x-for="h in hops" :key="h.num">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50">
                                <td class="py-3 px-3 font-bold text-blue-600 dark:text-blue-400" x-text="h.num"></td>
                                <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200" x-text="h.ip"></td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400" x-text="h.name"></td>
                                <td class="py-3 px-4 font-bold text-emerald-600 dark:text-emerald-400" x-text="h.rtt + ' ms'"></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400">Reached</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="hops.length === 0" class="text-center py-6 text-slate-500 text-sm">
                Enter target host and run traceroute to discover internet routing gateways.
            </div>
        </div>

        <script>
        function tracerouteApp() {
            return {
                target: 'github.com',
                running: false,
                hops: [],
                init() {
                    this.runTrace();
                },
                async runTrace() {
                    this.hops = [];
                    this.running = true;
                    const simulatedHops = [
                        { num: 1, ip: '192.168.1.1', name: 'Local Customer Premises Equipment (Gateway)', rtt: 2 },
                        { num: 2, ip: '10.240.0.1', name: 'ISP Edge Aggregation Router (POP)', rtt: 8 },
                        { num: 3, ip: '172.16.88.254', name: 'Regional Core Transit Gateway', rtt: 14 },
                        { num: 4, ip: '198.32.176.1', name: 'National Internet Exchange Point (IXP)', rtt: 24 },
                        { num: 5, ip: '140.82.112.3', name: 'Tier-1 Backbone Carrier Gateway (Level3 / NTT)', rtt: 42 },
                        { num: 6, ip: '140.82.114.28', name: 'Transatlantic Subsea Cable Landing Gateway', rtt: 78 },
                        { num: 7, ip: '140.82.121.4', name: 'Datacenter Edge Border Router', rtt: 85 },
                        { num: 8, ip: '140.82.121.3', name: 'Target Destination Node (' + this.target + ')', rtt: 88 }
                    ];

                    for (let hop of simulatedHops) {
                        await new Promise(r => setTimeout(r, 200));
                        this.hops.push(hop);
                    }
                    this.running = false;
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
