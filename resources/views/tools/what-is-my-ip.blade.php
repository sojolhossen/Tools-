@php
    $rawIp = request()->header('CF-Connecting-IP') ?: (request()->header('X-Forwarded-For') ?: request()->ip());
    if ($rawIp && str_contains($rawIp, ',')) {
        $rawIp = trim(explode(',', $rawIp)[0]);
    }
    $initialIpv4 = (filter_var($rawIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) ? $rawIp : '';
    $initialIpv6 = (filter_var($rawIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) ? $rawIp : '';
    $initialCountry = request()->header('CF-IPCountry') ?: '';
@endphp

<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="whatIsMyIpApp('{{ $initialIpv4 }}', '{{ $initialIpv6 }}', '{{ $initialCountry }}', '{{ $rawIp }}')">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- IPv4 Card -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Public IPv4 Address</span>
                        <div class="mt-2 text-2xl sm:text-3xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="ipv4 || (ipv6 ? 'Using IPv6 Connection' : (loading ? 'Checking...' : 'No IPv4 Detected'))"></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500" x-text="ipv4 ? 'Verified Public IP' : (ipv6 ? 'IPv6 Primary' : (loading ? 'Detecting...' : 'Ready'))"></span>
                        <button type="button" @click="copyIpv4()" x-show="ipv4" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-200 transition-colors flex items-center gap-1.5">
                            <span x-text="copied4 ? '✓ Copied!' : 'Copy IPv4'"></span>
                        </button>
                    </div>
                </div>

                <!-- IPv6 Card -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Public IPv6 Address</span>
                        <div class="mt-2 text-xl sm:text-2xl font-mono font-bold text-slate-800 dark:text-slate-200 break-all" x-text="ipv6 || 'Not Supported / IPv4 Only'"></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500" x-text="ipv6 ? 'Native IPv6 Network' : 'No IPv6 Route'"></span>
                        <button type="button" @click="copyIpv6()" x-show="ipv6" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-300 transition-colors flex items-center gap-1.5">
                            <span x-text="copied6 ? '✓ Copied!' : 'Copy IPv6'"></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Geolocation & Network Details -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100 mb-4">Connection & Network Diagnostics</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Country / City</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 truncate block" x-text="geo.country ? (geo.country + (geo.city ? ', ' + geo.city : '')) : (loading ? 'Detecting...' : 'Global')"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">ISP / Provider</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 truncate block" x-text="geo.isp || (loading ? 'Detecting...' : 'Broadband Network')"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Autonomous System (ASN)</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 truncate block" x-text="geo.asn || 'AS Network'"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Local Network IP</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 truncate block" x-text="localIp || 'Protected (mDNS)'"></span>
                    </div>
                </div>
            </div>

            <!-- Action Refresh Button with Status -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <div class="text-xs text-slate-500 order-2 sm:order-1 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full" :class="loading ? 'bg-amber-500 animate-ping' : 'bg-emerald-500'"></span>
                    <span>Last checked: <strong class="font-mono text-slate-700 dark:text-slate-300" x-text="lastUpdated || 'Just now'"></strong></span>
                </div>
                <div class="order-1 sm:order-2">
                    <button type="button" @click="detect()" :disabled="loading" class="px-5 py-2.5 rounded-xl font-semibold text-sm shadow-sm transition-all flex items-center gap-2" :class="refreshed ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : (loading ? 'bg-blue-500 text-white opacity-80 cursor-wait' : 'bg-blue-600 hover:bg-blue-700 text-white')">
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <svg x-show="!loading && !refreshed" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <svg x-show="refreshed" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="loading ? 'Refreshing IP...' : (refreshed ? '✓ Refreshed!' : 'Refresh IP Status')"></span>
                    </button>
                </div>
            </div>
        </div>

        <script>
        function whatIsMyIpApp(initIpv4, initIpv6, initCountry, serverRaw) {
            return {
                ipv4: initIpv4 || '',
                ipv6: initIpv6 || '',
                serverRaw: serverRaw || '',
                localIp: '',
                loading: false,
                refreshed: false,
                copied4: false,
                copied6: false,
                lastUpdated: '',
                geo: { country: initCountry || '', city: '', isp: '', asn: '' },
                init() {
                    if (!this.ipv4 && !this.ipv6 && this.serverRaw) {
                        if (this.serverRaw.includes(':')) {
                            this.ipv6 = this.serverRaw;
                        } else if (this.serverRaw !== '127.0.0.1' && this.serverRaw !== '::1') {
                            this.ipv4 = this.serverRaw;
                        }
                    }
                    this.lastUpdated = new Date().toLocaleTimeString();
                    this.detect();
                    this.detectLocalIp();
                },
                async detect() {
                    if (this.loading) return;
                    this.loading = true;
                    this.refreshed = false;

                    const fetchTimeout = (url, ms = 2500) => {
                        const controller = new AbortController();
                        const timer = setTimeout(() => controller.abort(), ms);
                        return fetch(url, { signal: controller.signal, cache: 'no-store' })
                            .finally(() => clearTimeout(timer));
                    };

                    try {
                        // 1. Same-Origin internal API (Zero adblocker risk, zero CORS risk)
                        try {
                            const localRes = await fetchTimeout('/api/tools/ip-info', 2000);
                            if (localRes.ok) {
                                const data = await localRes.json();
                                if (data.ipv4) this.ipv4 = data.ipv4;
                                if (data.ipv6) this.ipv6 = data.ipv6;
                                if (!this.ipv4 && !this.ipv6 && data.ip) {
                                    if (data.ip.includes(':')) this.ipv6 = data.ip;
                                    else this.ipv4 = data.ip;
                                }
                                if (data.country && !this.geo.country) this.geo.country = data.country;
                                if (data.city && !this.geo.city) this.geo.city = data.city;
                                if (data.isp && (!this.geo.isp || this.geo.isp === 'Broadband Network')) this.geo.isp = data.isp;
                                if (data.asn && (!this.geo.asn || this.geo.asn === 'AS Network')) this.geo.asn = data.asn;
                            }
                        } catch (e) {}

                        // 2. Cloudflare cdn-cgi trace
                        try {
                            const traceRes = await fetchTimeout('/cdn-cgi/trace', 1500);
                            if (traceRes.ok) {
                                const text = await traceRes.text();
                                const data = {};
                                text.split('\n').forEach(line => {
                                    const parts = line.split('=');
                                    if (parts.length >= 2) data[parts[0].trim()] = parts.slice(1).join('=').trim();
                                });
                                if (data.ip) {
                                    if (data.ip.includes(':')) this.ipv6 = data.ip;
                                    else this.ipv4 = data.ip;
                                }
                                if (data.loc) this.geo.country = data.loc;
                                if (data.colo) this.geo.city = 'Cloudflare (' + data.colo + ')';
                            }
                        } catch (e) {}

                        // 3. Enrich geolocation with ipwho.is if available
                        try {
                            const res = await fetchTimeout('https://ipwho.is/', 2000).then(r => r.json());
                            if (res && res.success) {
                                if (res.ip) {
                                    if (res.ip.includes(':')) this.ipv6 = res.ip;
                                    else this.ipv4 = res.ip;
                                }
                                this.geo.country = res.country || this.geo.country;
                                this.geo.city = res.city || this.geo.city;
                                this.geo.isp = (res.connection && res.connection.isp) || res.isp || this.geo.isp;
                                this.geo.asn = (res.connection && res.connection.asn) ? ('AS' + res.connection.asn) : (res.asn || this.geo.asn);
                            }
                        } catch (e) {}

                        // 4. Fallback to server-side detected IP
                        if (!this.ipv4 && !this.ipv6 && this.serverRaw) {
                            if (this.serverRaw.includes(':')) this.ipv6 = this.serverRaw;
                            else this.ipv4 = this.serverRaw;
                        }
                    } finally {
                        this.lastUpdated = new Date().toLocaleTimeString();
                        this.loading = false;
                        this.refreshed = true;
                        setTimeout(() => { this.refreshed = false; }, 3000);
                    }
                },
                detectLocalIp() {
                    try {
                        const pc = new RTCPeerConnection({ iceServers: [] });
                        pc.createDataChannel('');
                        pc.createOffer().then(o => pc.setLocalDescription(o));
                        pc.onicecandidate = (e) => {
                            if (!e || !e.candidate) return;
                            const ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3})/;
                            const match = ipRegex.exec(e.candidate.candidate);
                            if (match && match[1]) this.localIp = match[1];
                        };
                    } catch (e) {}
                },
                copyIpv4() {
                    if (this.ipv4 && navigator.clipboard) {
                        navigator.clipboard.writeText(this.ipv4);
                        this.copied4 = true;
                        setTimeout(() => { this.copied4 = false; }, 2000);
                    }
                },
                copyIpv6() {
                    if (this.ipv6 && navigator.clipboard) {
                        navigator.clipboard.writeText(this.ipv6);
                        this.copied6 = true;
                        setTimeout(() => { this.copied6 = false; }, 2000);
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
