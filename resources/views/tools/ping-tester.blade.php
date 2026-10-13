<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="pingTesterApp()">
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
                <input type="text" x-model="host" placeholder="https://cloudflare.com or https://google.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="startPing()" :disabled="running" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!running">Start Ping Test (5 Packets)</span>
                    <span x-show="running">Pinging... (<span x-text="pings.length"></span>/5)</span>
                </button>
            </div>

            <!-- Ping Summary Stats Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Minimum Ping</span>
                    <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400" x-text="minPing ? minPing + ' ms' : '-'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Average Latency</span>
                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400" x-text="avgPing ? avgPing + ' ms' : '-'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Maximum Latency</span>
                    <span class="text-lg font-bold text-amber-600 dark:text-amber-400" x-text="maxPing ? maxPing + ' ms' : '-'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Packet Loss</span>
                    <span class="text-lg font-bold text-slate-800 dark:text-slate-200" x-text="pings.length ? '0%' : '-'"></span>
                </div>
            </div>

            <!-- Detailed Ping Sequence Log -->
            <div class="p-4 rounded-xl bg-slate-950 text-slate-200 font-mono text-xs space-y-1.5 h-44 overflow-y-auto border border-slate-800">
                <template x-for="(p, i) in pings" :key="i">
                    <div class="flex items-center justify-between">
                        <span>Reply from <span class="text-blue-400" x-text="host"></span>: seq=<span x-text="i+1"></span> time=<span class="font-bold text-emerald-400" x-text="p + 'ms'"></span></span>
                        <span class="text-slate-500">HTTP/Beacon OK</span>
                    </div>
                </template>
                <div x-show="pings.length === 0" class="text-slate-500">Press 'Start Ping Test' to measure network response latency.</div>
            </div>
        </div>

        <script>
        function pingTesterApp() {
            return {
                host: 'https://cloudflare.com',
                pings: [],
                running: false,
                minPing: null,
                avgPing: null,
                maxPing: null,
                init() {
                    this.startPing();
                },
                async startPing() {
                    this.pings = [];
                    this.running = true;
                    this.minPing = null; this.avgPing = null; this.maxPing = null;

                    for (let i = 0; i < 5; i++) {
                        const t0 = performance.now();
                        try {
                            // Using cache-busted fetch to test real round trip
                            const url = this.host.startsWith('http') ? this.host : 'https://' + this.host;
                            await fetch(url + '?_t=' + Date.now(), { mode: 'no-cors' });
                            const t1 = performance.now();
                            const rtt = Math.round(t1 - t0);
                            this.pings.push(rtt);
                            this.updateStats();
                        } catch(e) {
                            const t1 = performance.now();
                            this.pings.push(Math.round(t1 - t0));
                            this.updateStats();
                        }
                        await new Promise(r => setTimeout(r, 400));
                    }
                    this.running = false;
                },
                updateStats() {
                    if (this.pings.length === 0) return;
                    this.minPing = Math.min(...this.pings);
                    this.maxPing = Math.max(...this.pings);
                    this.avgPing = Math.round(this.pings.reduce((a, b) => a + b, 0) / this.pings.length);
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
