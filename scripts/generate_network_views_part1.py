import os

VIEWS_DIR = 'resources/views/tools'

# Helper header and footer generators
def get_view_wrapper(slug, x_data, inner_content):
    tpl = """<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="___X_DATA___">
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

___INNER_CONTENT___

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
"""
    return tpl.replace("___X_DATA___", x_data).replace("___INNER_CONTENT___", inner_content)

# 1. what-is-my-ip
what_is_my_ip = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- IPv4 Card -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Public IPv4 Address</span>
                        <div class="mt-2 text-2xl sm:text-3xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="ipv4 || 'Detecting...'"></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500" x-text="ipv4 ? 'Verified via Web Protocol' : 'Scanning...'"></span>
                        <button type="button" @click="copy(ipv4)" x-show="ipv4" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-200 transition-colors">
                            Copy IPv4
                        </button>
                    </div>
                </div>

                <!-- IPv6 Card -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Public IPv6 Address</span>
                        <div class="mt-2 text-xl sm:text-2xl font-mono font-bold text-slate-800 dark:text-slate-200 break-all" x-text="ipv6 || 'Not Supported / Disabled'"></div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-xs text-slate-500" x-text="ipv6 ? 'Native IPv6 Network' : 'No IPv6 Route'"></span>
                        <button type="button" @click="copy(ipv6)" x-show="ipv6" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-300 transition-colors">
                            Copy IPv6
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
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="(geo.country || '...') + ', ' + (geo.city || '...')"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">ISP / Provider</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 truncate block" x-text="geo.isp || 'Detecting...'"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Autonomous System (ASN)</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="geo.asn || 'AS Unknown'"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-lg border border-slate-200 dark:border-slate-800">
                        <span class="text-slate-500 block mb-1">Local Network IP</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="localIp || 'Protected (mDNS)'"></span>
                    </div>
                </div>
            </div>

            <!-- Action Refresh Button -->
            <div class="flex justify-end pt-2">
                <button type="button" @click="detect()" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" :class="loading ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Refresh IP Status</span>
                </button>
            </div>
        </div>

        <script>
        function whatIsMyIpApp() {
            return {
                ipv4: '',
                ipv6: '',
                localIp: '',
                loading: false,
                geo: { country: '', city: '', isp: '', asn: '' },
                init() {
                    this.detect();
                    this.detectLocalIp();
                },
                async detect() {
                    this.loading = true;
                    try {
                        const res4 = await fetch('https://api.ipify.org?format=json').then(r => r.json()).catch(() => null);
                        if (res4 && res4.ip) this.ipv4 = res4.ip;

                        const geoRes = await fetch('https://ipapi.co/json/').then(r => r.json()).catch(() => null);
                        if (geoRes) {
                            if (!this.ipv4 && geoRes.ip) this.ipv4 = geoRes.ip;
                            this.geo = {
                                country: geoRes.country_name || '',
                                city: geoRes.city || '',
                                isp: geoRes.org || '',
                                asn: geoRes.asn || ''
                            };
                        }
                    } catch(e) {}
                    this.loading = false;
                },
                detectLocalIp() {
                    try {
                        const pc = new RTCPeerConnection({ iceServers: [] });
                        pc.createDataChannel('');
                        pc.createOffer().then(o => pc.setLocalDescription(o));
                        pc.onicecandidate = (e) => {
                            if (!e || !e.candidate) return;
                            const ipRegex = /([0-9]{1,3}(\\.[0-9]{1,3}){3})/;
                            const match = ipRegex.exec(e.candidate.candidate);
                            if (match && match[1]) this.localIp = match[1];
                        };
                    } catch (e) {}
                },
                copy(text) {
                    if (navigator.clipboard) navigator.clipboard.writeText(text);
                }
            };
        }
        </script>
"""

# 2. ip-lookup
ip_lookup = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="query" @keydown.enter="lookup()" placeholder="Enter IP address or domain (e.g. 8.8.8.8, github.com)" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Lookup IP</span>
                    <span x-show="loading" class="flex items-center gap-1.5"><svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Searching...</span>
                </button>
            </div>

            <!-- Error Banner -->
            <div x-show="error" x-text="error" class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm"></div>

            <!-- Result Cards Grid -->
            <div x-show="result" class="space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Target IP</span>
                        <span class="font-mono font-bold text-sm text-blue-600 dark:text-blue-400" x-text="result?.ip || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Country</span>
                        <span class="font-bold text-sm text-slate-900 dark:text-slate-100" x-text="result?.country || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">City & Region</span>
                        <span class="font-bold text-sm text-slate-900 dark:text-slate-100" x-text="(result?.city || '-') + ', ' + (result?.region || '-')"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Postal Code</span>
                        <span class="font-mono font-bold text-sm text-slate-900 dark:text-slate-100" x-text="result?.postal || '-'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">ISP / Organization</span>
                        <span class="font-bold text-sm text-slate-900 dark:text-slate-100 truncate block" x-text="result?.connection?.isp || result?.isp || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Autonomous System (ASN)</span>
                        <span class="font-mono font-bold text-sm text-slate-900 dark:text-slate-100" x-text="result?.connection?.asn || result?.asn || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Timezone & Coordinates</span>
                        <span class="font-mono text-xs text-slate-900 dark:text-slate-100 block" x-text="(result?.timezone?.id || result?.timezone || '-') + ' (' + (result?.latitude || '-') + ', ' + (result?.longitude || '-') + ')'"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function ipLookupApp() {
            return {
                query: '',
                loading: false,
                error: null,
                result: null,
                init() {
                    this.lookup();
                },
                async lookup() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const target = this.query.trim();
                        const url = target ? 'https://ipwho.is/' + encodeURIComponent(target) : 'https://ipwho.is/';
                        const res = await fetch(url).then(r => r.json());
                        if (res.success === false) {
                            this.error = res.message || 'Invalid IP or domain address.';
                            this.result = null;
                        } else {
                            this.result = res;
                        }
                    } catch (e) {
                        this.error = 'Network request failed. Please check connection.';
                    }
                    this.loading = false;
                }
            };
        }
        </script>
"""

# 3. user-agent-parser
user_agent_parser = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">User-Agent String</label>
                <textarea x-model="uaString" @input="parse()" rows="3" class="w-full p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
                <div class="mt-2 flex justify-between">
                    <button type="button" @click="resetToCurrent()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Reset to My Browser User-Agent</button>
                    <button type="button" @click="copy(uaString)" class="text-xs text-slate-500 hover:text-slate-700">Copy String</button>
                </div>
            </div>

            <!-- Parsed Breakdown Cards -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Detected Hardware & Software Specification</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Browser Name</span>
                        <span class="text-base font-bold text-blue-600 dark:text-blue-400" x-text="browser.name || 'Unknown'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Browser Version</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200" x-text="browser.version || 'N/A'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Operating System</span>
                        <span class="text-base font-bold text-slate-900 dark:text-slate-100" x-text="os.name || 'Unknown'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">OS Version</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200" x-text="os.version || 'N/A'"></span>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Device Form Factor</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="device.type || 'Desktop / Laptop'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Rendering Engine</span>
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200" x-text="engine || 'Blink / WebKit'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 block mb-1">Architecture</span>
                        <span class="font-mono text-sm font-bold text-slate-800 dark:text-slate-200" x-text="arch || '64-bit (x86_64 / ARM64)'"></span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function userAgentParserApp() {
            return {
                uaString: navigator.userAgent,
                browser: {},
                os: {},
                device: {},
                engine: '',
                arch: '',
                init() {
                    this.parse();
                },
                resetToCurrent() {
                    this.uaString = navigator.userAgent;
                    this.parse();
                },
                parse() {
                    const ua = this.uaString;
                    // OS detection
                    let osName = 'Unknown OS', osVer = '';
                    if (/Windows NT 10.0/i.test(ua)) { osName = 'Windows'; osVer = '10 / 11'; }
                    else if (/Windows NT 6.3/i.test(ua)) { osName = 'Windows'; osVer = '8.1'; }
                    else if (/Windows NT 6.1/i.test(ua)) { osName = 'Windows'; osVer = '7'; }
                    else if (/Mac OS X ([0-9_]+)/i.test(ua)) { osName = 'macOS'; osVer = RegExp.$1.replace(/_/g, '.'); }
                    else if (/Android ([0-9.]+)/i.test(ua)) { osName = 'Android'; osVer = RegExp.$1; }
                    else if (/iPhone OS ([0-9_]+)/i.test(ua)) { osName = 'iOS'; osVer = RegExp.$1.replace(/_/g, '.'); }
                    else if (/Linux/i.test(ua)) { osName = 'Linux'; osVer = 'Generic'; }
                    this.os = { name: osName, version: osVer };

                    // Browser detection
                    let bName = 'Browser', bVer = '';
                    if (/Edg\\/([0-9.]+)/i.test(ua)) { bName = 'Microsoft Edge'; bVer = RegExp.$1; }
                    else if (/Chrome\\/([0-9.]+)/i.test(ua)) { bName = 'Google Chrome'; bVer = RegExp.$1; }
                    else if (/Firefox\\/([0-9.]+)/i.test(ua)) { bName = 'Mozilla Firefox'; bVer = RegExp.$1; }
                    else if (/Version\\/([0-9.]+).*Safari/i.test(ua)) { bName = 'Apple Safari'; bVer = RegExp.$1; }
                    else if (/Opera|OPR\\/([0-9.]+)/i.test(ua)) { bName = 'Opera'; bVer = RegExp.$1; }
                    this.browser = { name: bName, version: bVer };

                    // Engine
                    if (/AppleWebKit/i.test(ua)) this.engine = 'WebKit / Blink';
                    else if (/Gecko/i.test(ua)) this.engine = 'Gecko';
                    else if (/Trident/i.test(ua)) this.engine = 'Trident';
                    else this.engine = 'Unknown';

                    // Device & arch
                    this.device = { type: (/Mobile|Android|iPhone/i.test(ua) ? 'Mobile Device' : 'Desktop / PC') };
                    this.arch = (/Win64|x86_64|x64|WOW64/i.test(ua) ? '64-bit (x64)' : (/arm64|aarch64/i.test(ua) ? 'ARM64' : '32-bit'));
                },
                copy(t) { if (navigator.clipboard) navigator.clipboard.writeText(t); }
            };
        }
        </script>
"""

# 4. browser-info
browser_info = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Active Client Environment Diagnostic</h3>
                <button type="button" @click="refresh()" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 hover:bg-blue-100 transition-colors">Refresh</button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Screen Resolution</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="screenRes"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Viewport Dimensions</span>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400" x-text="viewport"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Color Depth</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="colorDepth + '-bit'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Device Pixel Ratio (DPR)</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="dpr + 'x'"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Cookies Enabled</span>
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="cookies ? 'Yes' : 'No'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Touch Screen</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="touch ? 'Supported' : 'No Touch'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">CPU Cores</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="cores + ' Cores'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Device RAM</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="ram ? ram + ' GB' : 'N/A'"></span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-xs font-mono">
                <span class="text-slate-500 block mb-1">Graphics / WebGL Renderer</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 break-all" x-text="gpu || 'Default Hardware Accelerated GPU'"></span>
            </div>
        </div>

        <script>
        function browserInfoApp() {
            return {
                screenRes: '',
                viewport: '',
                colorDepth: '',
                dpr: '',
                cookies: true,
                touch: false,
                cores: '',
                ram: '',
                gpu: '',
                init() {
                    this.refresh();
                },
                refresh() {
                    this.screenRes = window.screen.width + ' x ' + window.screen.height;
                    this.viewport = window.innerWidth + ' x ' + window.innerHeight;
                    this.colorDepth = window.screen.colorDepth;
                    this.dpr = window.devicePixelRatio || 1;
                    this.cookies = navigator.cookieEnabled;
                    this.touch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
                    this.cores = navigator.hardwareConcurrency || 4;
                    this.ram = navigator.deviceMemory || '';

                    try {
                        const canvas = document.createElement('canvas');
                        const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
                        if (gl) {
                            const dbg = gl.getExtension('WEBGL_debug_renderer_info');
                            if (dbg) this.gpu = gl.getParameter(dbg.UNMASKED_RENDERER_WEBGL);
                        }
                    } catch (e) {}
                }
            };
        }
        </script>
"""

# 5. webrtc-ip-leak-checker
webrtc_leak = """
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
                    <div class="text-xl font-mono font-bold text-slate-900 dark:text-white break-all" x-text="webrtcIp || 'No STUN Public Candidate Detected'"></div>
                    <span class="text-xs text-slate-500 mt-2 block">Observed via STUN ICE Binding (Google STUN)</span>
                </div>

                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-2">Regular HTTP Public IP</span>
                    <div class="text-xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="httpIp || 'Scanning...'"></div>
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
        function webrtcLeakApp() {
            return {
                webrtcIp: '',
                httpIp: '',
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

                    // 1. Fetch normal HTTP IP
                    try {
                        const r = await fetch('https://api.ipify.org?format=json').then(res => res.json());
                        if (r && r.ip) this.httpIp = r.ip;
                    } catch(e) {}

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
                            this.statusText = 'NO WEBRTC LEAK';
                            this.statusDesc = 'Your browser blocks WebRTC host / reflexive ICE candidates or mDNS anonymization is enabled.';
                        }
                        this.testing = false;
                    }, 2500);
                }
            };
        }
        </script>
"""

# 6. dns-lookup
dns_lookup = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="domain" @keydown.enter="lookup()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <select x-model="recordType" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-sm font-semibold">
                    <option value="A">A (IPv4)</option>
                    <option value="AAAA">AAAA (IPv6)</option>
                    <option value="CNAME">CNAME</option>
                    <option value="MX">MX (Mail)</option>
                    <option value="TXT">TXT / SPF</option>
                    <option value="NS">NS (Name Server)</option>
                    <option value="SOA">SOA</option>
                    <option value="CAA">CAA</option>
                </select>
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Query DNS</span>
                    <span x-show="loading">Resolving...</span>
                </button>
            </div>

            <!-- Error -->
            <div x-show="error" x-text="error" class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm"></div>

            <!-- DNS Records Table -->
            <div x-show="records.length > 0" class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-mono">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-500 uppercase">
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">TTL</th>
                            <th class="py-3 px-4">Data / Target Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        <template x-for="(r, idx) in records" :key="idx">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50">
                                <td class="py-3 px-4 font-bold text-slate-900 dark:text-slate-100" x-text="r.name"></td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold" x-text="recordType"></span></td>
                                <td class="py-3 px-4 text-slate-500" x-text="r.TTL + 's'"></td>
                                <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-200 break-all" x-text="r.data"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="!loading && searched && records.length === 0 && !error" class="text-center py-8 text-slate-500 text-sm">
                No DNS records found for this domain and type.
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
                init() {
                    this.lookup();
                },
                async lookup() {
                    if (!this.domain.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.records = [];
                    this.searched = true;
                    try {
                        const cleanDomain = this.domain.replace(/^https?:\\/\\//, '').replace(/\\/.*$/, '').trim();
                        const url = 'https://dns.google/resolve?name=' + encodeURIComponent(cleanDomain) + '&type=' + this.recordType;
                        const res = await fetch(url).then(r => r.json());
                        if (res.Answer && Array.isArray(res.Answer)) {
                            this.records = res.Answer;
                        } else if (res.Status === 3) {
                            this.error = 'NXDOMAIN: Domain does not exist.';
                        } else {
                            this.records = [];
                        }
                    } catch (e) {
                        this.error = 'Failed to resolve DNS over HTTPS query.';
                    }
                    this.loading = false;
                }
            };
        }
        </script>
"""

# 7. reverse-dns-lookup
reverse_dns = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="ip" @keydown.enter="lookup()" placeholder="8.8.8.8 or 1.1.1.1" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Lookup rDNS (PTR)</span>
                    <span x-show="loading">Resolving PTR...</span>
                </button>
            </div>

            <!-- Result Card -->
            <div x-show="hostname || error" class="p-6 rounded-xl border" :class="error ? 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400' : 'bg-slate-50 dark:bg-slate-950 border-slate-200 dark:border-slate-800'">
                <div x-show="error" x-text="error" class="text-sm font-semibold"></div>
                <div x-show="hostname" class="space-y-3">
                    <span class="text-xs uppercase font-bold text-slate-500 tracking-wider">Resolved Hostname (PTR Record)</span>
                    <div class="text-2xl font-mono font-bold text-blue-600 dark:text-blue-400 break-all" x-text="hostname"></div>
                    <div class="text-xs font-mono text-slate-500" x-text="'ARPA Query: ' + arpaQuery"></div>
                </div>
            </div>
        </div>

        <script>
        function reverseDnsApp() {
            return {
                ip: '8.8.8.8',
                hostname: '',
                arpaQuery: '',
                loading: false,
                error: null,
                init() {
                    this.lookup();
                },
                async lookup() {
                    if (!this.ip.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.hostname = '';
                    try {
                        const parts = this.ip.trim().split('.');
                        if (parts.length !== 4) throw new Error('Invalid IPv4 address format.');
                        this.arpaQuery = parts.reverse().join('.') + '.in-addr.arpa';
                        const url = 'https://dns.google/resolve?name=' + this.arpaQuery + '&type=PTR';
                        const res = await fetch(url).then(r => r.json());
                        if (res.Answer && res.Answer.length > 0) {
                            this.hostname = res.Answer[0].data;
                        } else {
                            this.error = 'No PTR (reverse DNS) record found for this IP address.';
                        }
                    } catch (e) {
                        this.error = e.message || 'Reverse DNS lookup failed.';
                    }
                    this.loading = false;
                }
            };
        }
        </script>
"""

# 8. whois-lookup
whois_lookup = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="domain" @keydown.enter="lookup()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="lookup()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Query WHOIS / RDAP</span>
                    <span x-show="loading">Querying Registry...</span>
                </button>
            </div>

            <!-- Error -->
            <div x-show="error" x-text="error" class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-rose-700 dark:text-rose-400 text-sm"></div>

            <!-- Results -->
            <div x-show="data" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Registration Date</span>
                        <span class="font-mono text-sm font-bold text-slate-900 dark:text-slate-100" x-text="created || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Expiration Date</span>
                        <span class="font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="expires || '-'"></span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="text-xs text-slate-500 uppercase tracking-wider block mb-1">Registrar</span>
                        <span class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate block" x-text="registrar || '-'"></span>
                    </div>
                </div>

                <!-- Nameservers -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-xs text-slate-500 uppercase tracking-wider block mb-2">Authoritative Nameservers</span>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(ns, i) in nameservers" :key="i">
                            <span class="px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-mono text-xs" x-text="ns"></span>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function whoisLookupApp() {
            return {
                domain: 'github.com',
                loading: false,
                data: null,
                created: '',
                expires: '',
                registrar: '',
                nameservers: [],
                error: null,
                init() {
                    this.lookup();
                },
                async lookup() {
                    if (!this.domain.trim()) return;
                    this.loading = true;
                    this.error = null;
                    this.data = null;
                    try {
                        const clean = this.domain.replace(/^https?:\\/\\//, '').replace(/\\/.*$/, '').trim();
                        const res = await fetch('https://rdap.org/domain/' + encodeURIComponent(clean)).then(r => r.json());
                        if (res && res.handle) {
                            this.data = res;
                            if (res.events) {
                                res.events.forEach(e => {
                                    if (e.eventAction === 'registration') this.created = new Date(e.eventDate).toLocaleDateString();
                                    if (e.eventAction === 'expiration') this.expires = new Date(e.eventDate).toLocaleDateString();
                                });
                            }
                            if (res.entities && res.entities[0] && res.entities[0].vcardArray) {
                                const vcard = res.entities[0].vcardArray[1];
                                const fn = vcard.find(item => item[0] === 'fn');
                                if (fn) this.registrar = fn[3];
                            }
                            if (res.nameservers) {
                                this.nameservers = res.nameservers.map(n => n.ldhName);
                            }
                        } else {
                            this.error = 'Domain RDAP/WHOIS data not available.';
                        }
                    } catch (e) {
                        this.error = 'WHOIS service query failed.';
                    }
                    this.loading = false;
                }
            };
        }
        </script>
"""

tools_part1 = [
    ('what-is-my-ip', 'whatIsMyIpApp()', what_is_my_ip),
    ('ip-lookup', 'ipLookupApp()', ip_lookup),
    ('user-agent-parser', 'userAgentParserApp()', user_agent_parser),
    ('browser-info', 'browserInfoApp()', browser_info),
    ('webrtc-ip-leak-checker', 'webrtcLeakApp()', webrtc_leak),
    ('dns-lookup', 'dnsLookupApp()', dns_lookup),
    ('reverse-dns-lookup', 'reverseDnsApp()', reverse_dns),
    ('whois-lookup', 'whoisLookupApp()', whois_lookup),
]

for slug, x_data, inner in tools_part1:
    path = os.path.join(VIEWS_DIR, f"{slug}.blade.php")
    content = get_view_wrapper(slug, x_data, inner)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Generated {path}")
