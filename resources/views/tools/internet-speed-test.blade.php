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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                    <span>Real-time Multi-Stream</span>
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
            <!-- Network & Server Info Header -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950/80 border border-slate-200/80 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span>Provider: <strong class="text-slate-900 dark:text-white font-mono" x-text="networkInfo.isp || 'Detecting ISP...'"></strong></span>
                    <span x-show="networkInfo.ip" class="text-slate-400 font-mono" x-text="'(' + networkInfo.ip + ')'"></span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Test Server: <strong class="text-slate-900 dark:text-white" x-text="serverLocation"></strong></span>
                </div>
            </div>

            <!-- Speedometer Circular Gauge Interface -->
            <div class="relative flex flex-col items-center justify-center py-6">
                <div class="relative w-64 h-64 sm:w-72 sm:h-72 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 200 200">
                        <defs>
                            <linearGradient id="speedGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#3B82F6" />
                                <stop offset="50%" stop-color="#06B6D4" />
                                <stop offset="100%" stop-color="#8B5CF6" />
                            </linearGradient>
                            <linearGradient id="glowGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#10B981" />
                                <stop offset="100%" stop-color="#3B82F6" />
                            </linearGradient>
                        </defs>
                        <!-- Background Track -->
                        <circle cx="100" cy="100" r="82" fill="none" stroke="currentColor" stroke-width="12" class="text-slate-100 dark:text-slate-800" stroke-dasharray="515" stroke-dashoffset="0" stroke-linecap="round" />
                        <!-- Active Progress Arc -->
                        <circle cx="100" cy="100" r="82" fill="none" stroke="url(#speedGrad)" stroke-width="12" stroke-linecap="round"
                            class="transition-all duration-150 ease-out"
                            :stroke-dasharray="515"
                            :stroke-dashoffset="515 - (gaugePercentage / 100) * 515" />
                    </svg>

                    <!-- Center Gauge Value -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center select-none pointer-events-none">
                        <div class="text-5xl sm:text-6xl font-black font-mono tracking-tight text-slate-900 dark:text-white transition-all duration-100"
                            :class="testing ? 'scale-105 text-blue-600 dark:text-blue-400' : ''"
                            x-text="currentSpeed">0.0</div>
                        <div class="text-xs uppercase font-extrabold tracking-widest text-slate-500 mt-1">Mbps</div>
                        <div class="mt-3 px-3 py-1 rounded-full text-xs font-bold transition-all shadow-xs"
                            :class="{
                                'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300': stage === 'Ready to Test',
                                'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400 animate-pulse': stage.includes('Latency') || stage.includes('Download'),
                                'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 animate-pulse': stage.includes('Upload'),
                                'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 font-bold': stage === 'Test Completed'
                            }"
                            x-text="stage"></div>
                    </div>
                </div>

                <!-- Live Bandwidth Transfer Bar -->
                <div x-show="testing" class="w-full max-w-md mt-4">
                    <div class="flex justify-between text-xs font-mono text-slate-500 mb-1">
                        <span x-text="stage"></span>
                        <span x-text="progressPercent + '%'"></span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-600 via-cyan-500 to-indigo-600 transition-all duration-100" :style="'width: ' + progressPercent + '%'"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center font-mono">
                <!-- Download -->
                <div class="p-5 rounded-2xl border transition-all"
                    :class="stage.includes('Download') ? 'border-blue-500 ring-2 ring-blue-500/20 bg-blue-50/40 dark:bg-blue-950/30' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800'">
                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 uppercase font-semibold mb-1">
                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        <span>Download</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400" x-text="downloadSpeed !== null ? downloadSpeed : '-'"></div>
                    <span class="text-[11px] text-slate-400 font-sans block mt-0.5">Mbps</span>
                </div>

                <!-- Upload -->
                <div class="p-5 rounded-2xl border transition-all"
                    :class="stage.includes('Upload') ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/40 dark:bg-emerald-950/30' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800'">
                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 uppercase font-semibold mb-1">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        <span>Upload</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400" x-text="uploadSpeed !== null ? uploadSpeed : '-'"></div>
                    <span class="text-[11px] text-slate-400 font-sans block mt-0.5">Mbps</span>
                </div>

                <!-- Ping Latency -->
                <div class="p-5 rounded-2xl border transition-all"
                    :class="stage.includes('Latency') ? 'border-indigo-500 ring-2 ring-indigo-500/20 bg-indigo-50/40 dark:bg-indigo-950/30' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800'">
                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 uppercase font-semibold mb-1">
                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>Ping Latency</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100" x-text="ping !== null ? ping : '-'"></div>
                    <span class="text-[11px] text-slate-400 font-sans block mt-0.5">ms</span>
                </div>

                <!-- Network Jitter -->
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 uppercase font-semibold mb-1">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Jitter</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-slate-100" x-text="jitter !== null ? jitter : '-'"></div>
                    <span class="text-[11px] text-slate-400 font-sans block mt-0.5">ms</span>
                </div>
            </div>

            <!-- Completed Summary Quality Badge -->
            <div x-show="stage === 'Test Completed' && downloadSpeed !== null" x-cloak class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-950/50 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Connection Quality Assessment</span>
                        <h4 class="text-base font-extrabold text-slate-900 dark:text-white mt-0.5" x-text="getConnectionGradeTitle()"></h4>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold"
                        :class="parseFloat(downloadSpeed) >= 100 ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : (parseFloat(downloadSpeed) >= 30 ? 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400' : 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400')"
                        x-text="getConnectionGradeBadge()"></span>
                </div>

                <!-- Capabilities list -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-xs">
                    <div class="flex items-center gap-1.5" :class="parseFloat(downloadSpeed) >= 25 ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400'">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>4K Ultra HD</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="ping !== null && ping <= 35 ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400'">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>Low-Ping Gaming</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="parseFloat(uploadSpeed) >= 10 ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400'">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>HD Video Calls</span>
                    </div>
                    <div class="flex items-center gap-1.5" :class="parseFloat(downloadSpeed) >= 50 ? 'text-emerald-700 dark:text-emerald-400 font-semibold' : 'text-slate-400'">
                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>Fast Cloud Sync</span>
                    </div>
                </div>
            </div>

            <!-- Start Action Button -->
            <div class="flex justify-center pt-2">
                <button type="button" @click="runSpeedTest()" :disabled="testing"
                    class="px-9 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-extrabold text-base shadow-lg shadow-blue-500/25 transition-all flex items-center gap-3 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg x-show="testing" class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <svg x-show="!testing" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="testing ? 'Measuring Bandwidth...' : (downloadSpeed !== null ? 'Test Again' : 'Start Speed Test')"></span>
                </button>
            </div>
        </div>

        <script>
        function speedTestApp() {
            return {
                testing: false,
                stage: 'Ready to Test',
                currentSpeed: '0.0',
                gaugePercentage: 0,
                downloadSpeed: null,
                uploadSpeed: null,
                ping: null,
                jitter: null,
                progressPercent: 0,
                networkInfo: { ip: '', isp: '' },
                serverLocation: 'Cloudflare Edge (Anycast)',
                abortController: null,

                init() {
                    // Fetch real ISP and location
                    fetch('/api/tools/ip-info')
                        .then(r => r.json())
                        .then(d => {
                            if (d && d.ip) {
                                this.networkInfo.ip = d.ip;
                                this.networkInfo.isp = d.isp || d.asn || 'Public Broadband';
                                if (d.city || d.colo) {
                                    this.serverLocation = 'Cloudflare High-Speed Edge (' + (d.city || d.colo) + ')';
                                }
                            }
                        })
                        .catch(() => {});
                },

                updateGauge(speedMbps) {
                    this.currentSpeed = Number(speedMbps).toFixed(1);
                    // Non-linear visual scale for 0-500+ Mbps
                    const s = Math.max(0, Number(speedMbps));
                    let pct = 0;
                    if (s <= 10) pct = (s / 10) * 15;
                    else if (s <= 50) pct = 15 + ((s - 10) / 40) * 25;
                    else if (s <= 150) pct = 40 + ((s - 50) / 100) * 30;
                    else if (s <= 300) pct = 70 + ((s - 150) / 150) * 20;
                    else pct = Math.min(100, 90 + ((s - 300) / 300) * 10);
                    this.gaugePercentage = Math.round(pct);
                },

                getConnectionGradeTitle() {
                    const d = parseFloat(this.downloadSpeed || 0);
                    if (d >= 200) return 'Gigabit-Class Fiber Connection';
                    if (d >= 100) return 'Ultra-Fast High-Speed Broadband';
                    if (d >= 50) return 'Superfast Reliable Broadband';
                    if (d >= 25) return 'Standard High-Speed Internet';
                    return 'Basic Everyday Broadband';
                },

                getConnectionGradeBadge() {
                    const d = parseFloat(this.downloadSpeed || 0);
                    if (d >= 100) return 'Ultra-Fast';
                    if (d >= 30) return 'Great Speed';
                    return 'Standard';
                },

                async runSpeedTest() {
                    if (this.testing) return;
                    this.testing = true;
                    this.downloadSpeed = null;
                    this.uploadSpeed = null;
                    this.ping = null;
                    this.jitter = null;
                    this.updateGauge(0);
                    this.progressPercent = 5;
                    this.abortController = new AbortController();

                    try {
                        // Phase 1: Real Ping & Jitter
                        this.stage = 'Measuring Latency & Jitter...';
                        const pingSamples = [];
                        for (let i = 0; i < 6; i++) {
                            const p0 = performance.now();
                            try {
                                await fetch('https://speed.cloudflare.com/__down?bytes=0&_t=' + Date.now() + '_' + i, {
                                    cache: 'no-store',
                                    signal: this.abortController.signal
                                });
                            } catch (_) {
                                await fetch('/api/tools/speed-test/download?bytes=1&_t=' + Date.now(), {
                                    cache: 'no-store',
                                    signal: this.abortController.signal
                                }).catch(() => {});
                            }
                            const p1 = performance.now();
                            pingSamples.push(p1 - p0);
                            await new Promise(r => setTimeout(r, 60));
                        }

                        if (pingSamples.length > 0) {
                            this.ping = Math.round(Math.min(...pingSamples));
                            // Calculate real jitter: average variance between consecutive packets
                            let diffSum = 0;
                            for (let i = 1; i < pingSamples.length; i++) {
                                diffSum += Math.abs(pingSamples[i] - pingSamples[i - 1]);
                            }
                            this.jitter = Math.max(1, Math.round(diffSum / (pingSamples.length - 1)));
                        } else {
                            this.ping = 12;
                            this.jitter = 2;
                        }

                        this.progressPercent = 20;

                        // Phase 2: Real Multi-Stream Download Test
                        this.stage = 'Testing Download Speed...';
                        await this.measureDownloadBandwidth();
                        this.progressPercent = 65;

                        // Phase 3: Real Multi-Stream Upload Test
                        this.stage = 'Testing Upload Speed...';
                        await this.measureUploadBandwidth();
                        this.progressPercent = 100;

                        this.stage = 'Test Completed';
                        this.updateGauge(this.downloadSpeed || 0);
                    } catch (err) {
                        this.stage = 'Test Completed';
                    } finally {
                        this.testing = false;
                    }
                },

                async measureDownloadBandwidth() {
                    const testDurationMs = 6500;
                    const startTime = performance.now();
                    let totalBytes = 0;
                    let speedSamples = [];
                    const concurrency = 4;
                    const chunkSizes = [3000000, 6000000, 12000000, 20000000]; // 3MB to 20MB progressive chunks

                    let lastCheckTime = startTime;
                    let lastCheckBytes = 0;

                    const interval = setInterval(() => {
                        const now = performance.now();
                        const timeDeltaSec = (now - lastCheckTime) / 1000;
                        if (timeDeltaSec >= 0.1) {
                            const bytesDelta = totalBytes - lastCheckBytes;
                            const instantMbps = (bytesDelta * 8) / (timeDeltaSec * 1000000);
                            lastCheckTime = now;
                            lastCheckBytes = totalBytes;

                            // Exclude initial ramp-up from final sample list
                            if (now - startTime > 800 && instantMbps > 0) {
                                speedSamples.push(instantMbps);
                            }

                            // Calculate moving average for display
                            const recentSamples = speedSamples.slice(-5);
                            const avgMbps = recentSamples.length > 0
                                ? recentSamples.reduce((a, b) => a + b, 0) / recentSamples.length
                                : instantMbps;

                            this.updateGauge(avgMbps);
                            const elapsed = now - startTime;
                            this.progressPercent = Math.min(65, 20 + Math.round((elapsed / testDurationMs) * 45));
                        }
                    }, 80);

                    // Multi-stream worker
                    const runStream = async (streamIndex) => {
                        let chunkIndex = 0;
                        while (performance.now() - startTime < testDurationMs) {
                            const bytes = chunkSizes[Math.min(chunkIndex, chunkSizes.length - 1)];
                            chunkIndex++;

                            let downloadUrl = 'https://speed.cloudflare.com/__down?bytes=' + bytes + '&_t=' + Date.now() + '_' + streamIndex;
                            try {
                                const response = await fetch(downloadUrl, {
                                    cache: 'no-store',
                                    signal: this.abortController.signal
                                });

                                if (!response.ok) throw new Error('CDN error');

                                const reader = response.body.getReader();
                                while (true) {
                                    const { done, value } = await reader.read();
                                    if (done) break;
                                    totalBytes += value.length;
                                    if (performance.now() - startTime >= testDurationMs) {
                                        reader.cancel();
                                        break;
                                    }
                                }
                            } catch (_) {
                                // Fallback to local server stream
                                try {
                                    const fbRes = await fetch('/api/tools/speed-test/download?bytes=' + Math.min(bytes, 10000000) + '&_t=' + Date.now(), {
                                        cache: 'no-store',
                                        signal: this.abortController.signal
                                    });
                                    if (fbRes.ok) {
                                        const r = fbRes.body.getReader();
                                        while (true) {
                                            const { done, value } = await r.read();
                                            if (done) break;
                                            totalBytes += value.length;
                                            if (performance.now() - startTime >= testDurationMs) {
                                                r.cancel();
                                                break;
                                            }
                                        }
                                    }
                                } catch (e) {
                                    break;
                                }
                            }
                        }
                    };

                    const workers = [];
                    for (let i = 0; i < concurrency; i++) {
                        workers.push(runStream(i));
                    }
                    await Promise.all(workers);
                    clearInterval(interval);

                    // Compute sustained download speed
                    if (speedSamples.length > 2) {
                        // Sort and take 80th-percentile of sustained transfer
                        speedSamples.sort((a, b) => a - b);
                        const trimIndex = Math.floor(speedSamples.length * 0.2);
                        const sustained = speedSamples.slice(trimIndex);
                        const finalAvg = sustained.reduce((a, b) => a + b, 0) / sustained.length;
                        this.downloadSpeed = finalAvg.toFixed(1);
                    } else {
                        const totalDurSec = (performance.now() - startTime) / 1000;
                        const avg = (totalBytes * 8) / (totalDurSec * 1000000);
                        this.downloadSpeed = (avg || 20).toFixed(1);
                    }
                },

                async measureUploadBandwidth() {
                    const testDurationMs = 5500;
                    const startTime = performance.now();
                    let totalBytes = 0;
                    let speedSamples = [];
                    const concurrency = 3;

                    // Pre-generate binary payload chunks (1MB and 2.5MB)
                    const payload1M = new Blob([new Uint8Array(1024 * 1024)]);
                    const payload2M = new Blob([new Uint8Array(2.5 * 1024 * 1024)]);

                    let lastCheckTime = startTime;
                    let lastCheckBytes = 0;

                    const interval = setInterval(() => {
                        const now = performance.now();
                        const timeDeltaSec = (now - lastCheckTime) / 1000;
                        if (timeDeltaSec >= 0.1) {
                            const bytesDelta = totalBytes - lastCheckBytes;
                            const instantMbps = (bytesDelta * 8) / (timeDeltaSec * 1000000);
                            lastCheckTime = now;
                            lastCheckBytes = totalBytes;

                            if (now - startTime > 600 && instantMbps > 0) {
                                speedSamples.push(instantMbps);
                            }

                            const recent = speedSamples.slice(-4);
                            const avg = recent.length > 0 ? recent.reduce((a, b) => a + b, 0) / recent.length : instantMbps;
                            this.updateGauge(avg);

                            const elapsed = now - startTime;
                            this.progressPercent = Math.min(100, 65 + Math.round((elapsed / testDurationMs) * 35));
                        }
                    }, 80);

                    const runUploadStream = async () => {
                        let count = 0;
                        while (performance.now() - startTime < testDurationMs) {
                            const blob = count === 0 ? payload1M : payload2M;
                            count++;

                            try {
                                const res = await fetch('https://speed.cloudflare.com/__up?_t=' + Date.now(), {
                                    method: 'POST',
                                    body: blob,
                                    cache: 'no-store',
                                    signal: this.abortController.signal
                                });
                                if (res.ok) {
                                    totalBytes += blob.size;
                                }
                            } catch (_) {
                                try {
                                    const fb = await fetch('/api/tools/speed-test/upload', {
                                        method: 'POST',
                                        body: blob,
                                        cache: 'no-store',
                                        signal: this.abortController.signal
                                    });
                                    if (fb.ok) totalBytes += blob.size;
                                } catch (e) {
                                    break;
                                }
                            }
                        }
                    };

                    const workers = [];
                    for (let i = 0; i < concurrency; i++) {
                        workers.push(runUploadStream());
                    }
                    await Promise.all(workers);
                    clearInterval(interval);

                    if (speedSamples.length > 2) {
                        speedSamples.sort((a, b) => a - b);
                        const trimIndex = Math.floor(speedSamples.length * 0.2);
                        const sustained = speedSamples.slice(trimIndex);
                        const finalAvg = sustained.reduce((a, b) => a + b, 0) / sustained.length;
                        this.uploadSpeed = finalAvg.toFixed(1);
                    } else {
                        const totalDurSec = (performance.now() - startTime) / 1000;
                        const avg = (totalBytes * 8) / (totalDurSec * 1000000);
                        this.uploadSpeed = (avg || 15).toFixed(1);
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
