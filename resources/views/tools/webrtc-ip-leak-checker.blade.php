@php
    $rawIp = request()->header('CF-Connecting-IP') ?: (request()->header('X-Forwarded-For') ?: request()->ip());
    if ($rawIp && str_contains($rawIp, ',')) {
        $rawIp = trim(explode(',', $rawIp)[0]);
    }
@endphp

<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="webrtcLeakApp('{{ $rawIp }}')">
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
            <!-- Leak Verdict Badge -->
            <div class="p-6 rounded-2xl border text-center space-y-2" :class="leakDetected ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900'">
                <div class="text-3xl font-extrabold" :class="leakDetected ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'" x-text="statusText"></div>
                <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xl mx-auto" x-text="statusDesc"></p>
            </div>

            <!-- Discovered IPs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2">WebRTC STUN Public Candidate IP</span>
                    <div class="text-xl font-mono font-bold text-slate-900 dark:text-white break-all" x-text="webrtcIp || 'No STUN Candidate Leaked (Secure)'"></div>
                    <span class="text-xs text-slate-500 mt-2 block">Observed via STUN ICE Binding (Google STUN)</span>
                </div>

                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2">Regular HTTP Public IP</span>
                    <div class="text-xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="httpIp || 'Detecting...'"></div>
                    <span class="text-xs text-slate-500 mt-2 block">Standard Web Browser TCP Connection IP</span>
                </div>
            </div>

            <!-- Test Action -->
            <div class="flex justify-end pt-2">
                <button type="button" @click="testWebRTC()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center gap-2">
                    <span x-show="!testing">Re-run Leak Test</span>
                    <span x-show="testing">Testing WebRTC STUN...</span>
                </button>
            </div>
        </div>

        <script>
        function webrtcLeakApp(serverIp) {
            return {
                webrtcIp: '',
                httpIp: serverIp || '',
                testing: false,
                leakDetected: false,
                statusText: 'Analyzing...',
                statusDesc: 'Executing WebRTC STUN candidates to detect potential IP address exposure.',
                init() {
                    this.testWebRTC();
                },
                async testWebRTC() {
                    this.testing = true;
                    this.webrtcIp = '';
                    this.leakDetected = false;

                    // 1. Fetch normal HTTP IP if not already set
                    if (!this.httpIp) {
                        try {
                            const traceRes = await fetch('/cdn-cgi/trace').then(r => r.text());
                            const m = traceRes.match(/ip=([^\n]+)/);
                            if (m) this.httpIp = m[1].trim();
                        } catch(e) {}

                        if (!this.httpIp) {
                            try {
                                const r = await fetch('https://ipwho.is/').then(res => res.json());
                                if (r && r.ip) this.httpIp = r.ip;
                            } catch(e) {}
                        }
                    }

                    // 2. WebRTC STUN test
                    try {
                        const pc = new RTCPeerConnection({
                            iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
                        });
                        pc.createDataChannel('leakTest');
                        const offer = await pc.createOffer();
                        await pc.setLocalDescription(offer);

                        pc.onicecandidate = (event) => {
                            if (!event || !event.candidate) return;
                            const parts = event.candidate.candidate.split(' ');
                            const ip = parts[4];
                            const type = parts[7];
                            if (type === 'srflx' && ip) {
                                this.webrtcIp = ip;
                                if (this.httpIp && this.webrtcIp !== this.httpIp) {
                                    this.leakDetected = true;
                                    this.statusText = 'VPN LEAK DETECTED!';
                                    this.statusDesc = 'Your WebRTC STUN IP differs from your VPN / proxy IP. Your ISP public IP address is exposed to websites.';
                                } else {
                                    this.statusText = 'NO LEAK DETECTED (SECURE)';
                                    this.statusDesc = 'Your browser WebRTC candidate matches your public connection IP or is shielded by your VPN.';
                                }
                            }
                        };
                    } catch (err) {}

                    setTimeout(() => {
                        if (!this.webrtcIp) {
                            this.statusText = 'NO WEBRTC LEAK (SECURE)';
                            this.statusDesc = 'Your browser WebRTC candidates are masked or mDNS privacy protection is active.';
                        } else if (!this.leakDetected) {
                            this.statusText = 'NO LEAK DETECTED (SECURE)';
                            this.statusDesc = 'Your WebRTC IP matches your active connection IP.';
                        }
                        this.testing = false;
                    }, 1200);
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
