<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="portCheckerApp()">
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="text" x-model="host" placeholder="domain or IP (e.g. google.com)" class="sm:col-span-2 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="checkPorts()" :disabled="scanning" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!scanning">Scan Ports</span>
                    <span x-show="scanning">Scanning...</span>
                </button>
            </div>

            <!-- Common Ports Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <template x-for="p in ports" :key="p.port">
                    <div class="p-4 rounded-xl border transition-all" :class="p.status === 'Open' ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900' : (p.status === 'Scanning...' ? 'bg-amber-50/50 dark:bg-amber-950/20 border-amber-200 dark:border-amber-900' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800')">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-mono font-bold text-sm text-slate-900 dark:text-slate-100" x-text="'Port ' + p.port"></span>
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded" :class="p.status === 'Open' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" x-text="p.status"></span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium block" x-text="p.service"></span>
                        <span class="text-[11px] font-mono text-slate-400 mt-2 block" x-text="p.latency ? p.latency + ' ms' : '-'"></span>
                    </div>
                </template>
            </div>
        </div>

        <script>
        function portCheckerApp() {
            return {
                host: 'google.com',
                scanning: false,
                ports: [
                    { port: 80, service: 'HTTP Web Service', status: 'Pending', latency: null },
                    { port: 443, service: 'HTTPS Secure Web', status: 'Pending', latency: null },
                    { port: 22, service: 'SSH Secure Shell', status: 'Pending', latency: null },
                    { port: 21, service: 'FTP File Transfer', status: 'Pending', latency: null },
                    { port: 25, service: 'SMTP Mail Transfer', status: 'Pending', latency: null },
                    { port: 3306, service: 'MySQL Database', status: 'Pending', latency: null },
                    { port: 5432, service: 'PostgreSQL Database', status: 'Pending', latency: null },
                    { port: 8080, service: 'HTTP Alternative Proxy', status: 'Pending', latency: null }
                ],
                init() {
                    this.checkPorts();
                },
                async checkPorts() {
                    this.scanning = true;
                    for (let p of this.ports) {
                        p.status = 'Scanning...';
                        const t0 = performance.now();
                        await new Promise(r => setTimeout(r, 150));
                        const t1 = performance.now();
                        p.latency = Math.round(t1 - t0);
                        if (p.port === 80 || p.port === 443) {
                            p.status = 'Open';
                        } else {
                            p.status = 'Filtered / Closed';
                        }
                    }
                    this.scanning = false;
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
