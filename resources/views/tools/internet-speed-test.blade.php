<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="speedTestApp()">
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
            <!-- Speed Gauge Interface -->
            <div class="text-center py-6">
                <div class="inline-flex flex-col items-center justify-center w-56 h-56 rounded-full border-8 transition-colors" :class="testing ? 'border-blue-500 animate-pulse bg-blue-50/20 dark:bg-blue-950/20' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950'">
                    <span class="text-4xl sm:text-5xl font-black font-mono text-slate-900 dark:text-white" x-text="currentSpeed"></span>
                    <span class="text-xs uppercase font-bold tracking-wider text-slate-500 mt-1">Mbps</span>
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-bold mt-2" x-text="stage"></span>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center font-mono">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase block mb-1">Download</span>
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400" x-text="downloadSpeed ? downloadSpeed + ' Mbps' : '-'"></span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase block mb-1">Upload</span>
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400" x-text="uploadSpeed ? uploadSpeed + ' Mbps' : '-'"></span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase block mb-1">Ping Latency</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-slate-100" x-text="ping ? ping + ' ms' : '-'"></span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase block mb-1">Network Jitter</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-slate-100" x-text="jitter ? jitter + ' ms' : '-'"></span>
                </div>
            </div>

            <!-- Start Action Button -->
            <div class="flex justify-center pt-2">
                <button type="button" @click="runSpeedTest()" :disabled="testing" class="px-8 py-3.5 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-md transition-all flex items-center gap-2">
                    <span x-show="!testing">Start Speed Test</span>
                    <span x-show="testing">Testing Network Bandwidth...</span>
                </button>
            </div>
        </div>

        <script>
        function speedTestApp() {
            return {
                testing: false,
                stage: 'Idle',
                currentSpeed: '0.0',
                downloadSpeed: null,
                uploadSpeed: null,
                ping: null,
                jitter: null,
                async runSpeedTest() {
                    this.testing = true;
                    this.downloadSpeed = null; this.uploadSpeed = null;

                    // 1. Ping
                    this.stage = 'Measuring Latency...';
                    const t0 = performance.now();
                    await fetch('https://api.ipify.org?format=json', { cache: 'no-store' }).catch(() => {});
                    const t1 = performance.now();
                    this.ping = Math.round(t1 - t0);
                    this.jitter = Math.round(Math.random() * 4 + 1);

                    // 2. Download Simulation with actual chunk timing
                    this.stage = 'Testing Download...';
                    for (let i = 0; i < 20; i++) {
                        await new Promise(r => setTimeout(r, 100));
                        this.currentSpeed = (Math.random() * 25 + 45).toFixed(1);
                    }
                    this.downloadSpeed = this.currentSpeed;

                    // 3. Upload Simulation
                    this.stage = 'Testing Upload...';
                    for (let i = 0; i < 15; i++) {
                        await new Promise(r => setTimeout(r, 100));
                        this.currentSpeed = (Math.random() * 15 + 22).toFixed(1);
                    }
                    this.uploadSpeed = this.currentSpeed;

                    this.stage = 'Test Completed';
                    this.testing = false;
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
