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
            <div class="p-6 rounded-2xl border text-center space-y-2 transition-all"
                 :class="{
                     'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900': verdictType === 'leak',
                     'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900': verdictType === 'secure' || verdictType === 'same_isp' || verdictType === 'shielded',
                     'bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800': verdictType === 'analyzing'
                 }">
                <div class="flex items-center justify-center gap-2">
                    <template x-if="verdictType === 'leak'">
                        <svg class="w-8 h-8 text-rose-600 dark:text-rose-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </template>
                    <template x-if="verdictType === 'secure' || verdictType === 'same_isp' || verdictType === 'shielded'">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </template>
                    <div class="text-2xl sm:text-3xl font-extrabold tracking-tight"
                         :class="{
                             'text-rose-600 dark:text-rose-400': verdictType === 'leak',
                             'text-emerald-600 dark:text-emerald-400': verdictType === 'secure' || verdictType === 'same_isp' || verdictType === 'shielded',
                             'text-slate-800 dark:text-slate-200': verdictType === 'analyzing'
                         }"
                         x-text="statusText"></div>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed" x-text="statusDesc"></p>

                <template x-if="verdictType === 'same_isp'">
                    <div class="pt-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Verified Same ISP Network Pool (Normal CGNAT Routing • No Leak)
                        </span>
                    </div>
                </template>
            </div>

            <!-- Discovered IPs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">WebRTC STUN Public Candidate IP</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">UDP STUN</span>
                    </div>
                    <div class="text-xl font-mono font-bold text-slate-900 dark:text-white break-all" x-text="webrtcIp || (testing ? 'Detecting...' : 'No STUN Candidate Leaked (Secure)')"></div>
                    <div class="mt-2 text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5" x-show="webrtcInfo">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span x-text="webrtcInfo"></span>
                    </div>
                    <span class="text-xs text-slate-500 mt-2 block" x-show="!webrtcInfo">Observed via STUN ICE Binding (Google STUN)</span>
                </div>

                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Regular HTTP Public IP</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300">TCP HTTP</span>
                    </div>
                    <div class="text-xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="httpIp || 'Detecting...'"></div>
                    <div class="mt-2 text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5" x-show="httpInfo">
                        <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span x-text="httpInfo"></span>
                    </div>
                    <span class="text-xs text-slate-500 mt-2 block" x-show="!httpInfo">Standard Web Browser TCP Connection IP</span>
                </div>
            </div>

            <!-- Test Action -->
            <div class="flex justify-end pt-2">
                <button type="button" @click="testWebRTC()" :disabled="testing" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold text-sm shadow-sm transition-all flex items-center gap-2">
                    <svg x-show="testing" class="animate-spin -ml-1 mr-1.5 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
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
                webrtcInfo: '',
                httpInfo: '',
                webrtcAsn: '',
                httpAsn: '',
                testing: false,
                verdictType: 'analyzing', // 'analyzing', 'secure', 'same_isp', 'shielded', 'leak'
                statusText: 'Analyzing WebRTC STUN...',
                statusDesc: 'Executing WebRTC STUN candidate discovery to inspect public IP binding and detect potential leaks.',
                init() {
                    this.testWebRTC();
                },
                isSameSubnet(ip1, ip2) {
                    if (!ip1 || !ip2) return false;
                    if (ip1 === ip2) return true;
                    // IPv4 check: match first 3 octets (/24)
                    const p1 = ip1.split('.');
                    const p2 = ip2.split('.');
                    if (p1.length === 4 && p2.length === 4) {
                        return p1[0] === p2[0] && p1[1] === p2[1] && p1[2] === p2[2];
                    }
                    // IPv6 check: match first 3 segments (/48)
                    const v6_1 = ip1.split(':');
                    const v6_2 = ip2.split(':');
                    if (v6_1.length > 2 && v6_2.length > 2) {
                        return v6_1.slice(0, 3).join(':') === v6_2.slice(0, 3).join(':');
                    }
                    return false;
                },
                async evaluateLeak() {
                    if (!this.webrtcIp) {
                        this.verdictType = 'shielded';
                        this.statusText = 'NO WEBRTC LEAK (SHIELDED)';
                        this.statusDesc = 'Your browser WebRTC candidates are masked, shielded, or mDNS privacy protection is active.';
                        return;
                    }

                    if (!this.httpIp) {
                        this.verdictType = 'analyzing';
                        this.statusText = 'WEBRTC CANDIDATE OBSERVED';
                        this.statusDesc = 'WebRTC public candidate observed: ' + this.webrtcIp;
                        return;
                    }

                    // 1. Exact match
                    if (this.webrtcIp === this.httpIp) {
                        this.verdictType = 'secure';
                        this.statusText = 'NO LEAK DETECTED (SECURE)';
                        this.statusDesc = 'Your WebRTC STUN IP matches your regular HTTP connection IP. No leak detected.';
                    } else if (this.isSameSubnet(this.webrtcIp, this.httpIp)) {
                        // 2. Same subnet (/24 block, e.g. 163.227.144.x)
                        const p = this.webrtcIp.split('.');
                        const prefix = p.length === 4 ? `${p[0]}.${p[1]}.${p[2]}.x` : 'the same subnet';
                        this.verdictType = 'same_isp';
                        this.statusText = 'NO LEAK DETECTED (SAME ISP POOL)';
                        this.statusDesc = `Both IPs belong to your ISP's same network block (${prefix}). This is standard Carrier-Grade NAT (CGNAT) / multi-homing routing by your internet provider, not a VPN leak.`;
                    } else {
                        // Default candidate if different
                        this.verdictType = 'leak';
                        this.statusText = 'POTENTIAL VPN LEAK DETECTED!';
                        this.statusDesc = 'WebRTC STUN returned an IP (' + this.webrtcIp + ') from a different network than your HTTP connection (' + this.httpIp + '). If you are using a VPN or proxy, your physical ISP IP may be leaking.';
                    }

                    // 3. Fetch ISP/ASN info asynchronously to enhance details and double check ASN match
                    try {
                        const [wRes, hRes] = await Promise.all([
                            fetch('/api/tools/ip-lookup?query=' + encodeURIComponent(this.webrtcIp)).then(r => r.json()).catch(() => null),
                            fetch('/api/tools/ip-lookup?query=' + encodeURIComponent(this.httpIp)).then(r => r.json()).catch(() => null)
                        ]);

                        if (wRes && wRes.success) {
                            const isp = wRes.connection?.isp || wRes.isp || '';
                            const country = wRes.country || '';
                            this.webrtcInfo = [isp, country].filter(Boolean).join(' • ');
                            this.webrtcAsn = String(wRes.connection?.asn || wRes.asn || '');
                        }

                        if (hRes && hRes.success) {
                            const isp = hRes.connection?.isp || hRes.isp || '';
                            const country = hRes.country || '';
                            this.httpInfo = [isp, country].filter(Boolean).join(' • ');
                            this.httpAsn = String(hRes.connection?.asn || hRes.asn || '');
                        }

                        // If ASN or ISP matches, it's definitely the same ISP
                        const sameAsn = this.webrtcAsn && this.httpAsn && (this.webrtcAsn === this.httpAsn);
                        const wIsp = (wRes?.connection?.isp || wRes?.isp || '').toLowerCase().trim();
                        const hIsp = (hRes?.connection?.isp || hRes?.isp || '').toLowerCase().trim();
                        const sameIsp = wIsp && hIsp && (wIsp === hIsp);

                        if ((sameAsn || sameIsp) && this.verdictType !== 'secure') {
                            this.verdictType = 'same_isp';
                            this.statusText = 'NO LEAK DETECTED (SAME ISP POOL)';
                            const ispName = hRes?.connection?.isp || hRes?.isp || 'your ISP';
                            this.statusDesc = `Both IPs originate from ${ispName}. This is normal Carrier-Grade NAT (CGNAT) / dynamic pool routing across your provider's network, not a VPN leak.`;
                        }
                    } catch(e) {}
                },
                async testWebRTC() {
                    this.testing = true;
                    this.webrtcIp = '';
                    this.webrtcInfo = '';
                    this.verdictType = 'analyzing';
                    this.statusText = 'Analyzing WebRTC STUN...';
                    this.statusDesc = 'Executing WebRTC STUN candidate discovery to inspect public IP binding and detect potential leaks.';

                    // 1. Fetch normal HTTP IP if not already set
                    if (!this.httpIp) {
                        try {
                            const localRes = await fetch('/api/tools/ip-info').then(r => r.json());
                            if (localRes && localRes.ip) this.httpIp = localRes.ip;
                        } catch(e) {}

                        if (!this.httpIp) {
                            try {
                                const traceRes = await fetch('/cdn-cgi/trace').then(r => r.text());
                                const m = traceRes.match(/ip=([^\n]+)/);
                                if (m) this.httpIp = m[1].trim();
                            } catch(e) {}
                        }

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
                            iceServers: [
                                { urls: 'stun:stun.l.google.com:19302' },
                                { urls: 'stun:stun1.l.google.com:19302' }
                            ]
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
                                this.evaluateLeak();
                            }
                        };
                    } catch (err) {}

                    setTimeout(async () => {
                        await this.evaluateLeak();
                        this.testing = false;
                    }, 1800);
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
