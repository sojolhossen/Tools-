<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="subnetCalcApp()">
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
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">IPv4 Address</label>
                    <input type="text" x-model="ip" @input="calculate()" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">CIDR Prefix Mask</label>
                    <select x-model.number="cidr" @change="calculate()" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm font-semibold">
                        <template x-for="c in 32" :key="c">
                            <option :value="c" x-text="'/' + c + ' - ' + getSubnetMask(c)"></option>
                        </template>
                    </select>
                </div>
            </div>

            <!-- Subnet Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono pt-2">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Network Address</span>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400" x-text="network"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Broadcast Address</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="broadcast"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Subnet Mask</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-slate-100" x-text="mask"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Total Usable Hosts</span>
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="usableHosts.toLocaleString()"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-mono">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Usable Host Range</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="firstHost + ' — ' + lastHost"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Wildcard Mask</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200" x-text="wildcard"></span>
                </div>
            </div>
        </div>

        <script>
        function subnetCalcApp() {
            return {
                ip: '192.168.1.100',
                cidr: 24,
                network: '',
                broadcast: '',
                mask: '',
                wildcard: '',
                firstHost: '',
                lastHost: '',
                usableHosts: 0,
                init() {
                    this.calculate();
                },
                getSubnetMask(c) {
                    const maskNum = (0xFFFFFFFF << (32 - c)) >>> 0;
                    return [(maskNum >>> 24) & 255, (maskNum >>> 16) & 255, (maskNum >>> 8) & 255, maskNum & 255].join('.');
                },
                calculate() {
                    const parts = this.ip.trim().split('.').map(Number);
                    if (parts.length !== 4 || parts.some(p => isNaN(p) || p < 0 || p > 255)) return;
                    const ipNum = ((parts[0] << 24) | (parts[1] << 16) | (parts[2] << 8) | parts[3]) >>> 0;
                    const maskNum = (0xFFFFFFFF << (32 - this.cidr)) >>> 0;
                    const netNum = (ipNum & maskNum) >>> 0;
                    const bcastNum = (netNum | (~maskNum >>> 0)) >>> 0;

                    const toIp = num => [(num >>> 24) & 255, (num >>> 16) & 255, (num >>> 8) & 255, num & 255].join('.');
                    this.network = toIp(netNum);
                    this.broadcast = toIp(bcastNum);
                    this.mask = toIp(maskNum);
                    this.wildcard = toIp((~maskNum) >>> 0);

                    if (this.cidr >= 31) {
                        this.usableHosts = 0;
                        this.firstHost = this.network;
                        this.lastHost = this.broadcast;
                    } else {
                        this.usableHosts = Math.pow(2, 32 - this.cidr) - 2;
                        this.firstHost = toIp(netNum + 1);
                        this.lastHost = toIp(bcastNum - 1);
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
