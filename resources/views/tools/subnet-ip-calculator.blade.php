<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-breadcrumbs :items="[
        __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
        __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
        $tool->getName() => null,
    ]" />

    <div class="mt-6 mb-8 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 text-indigo-600 dark:text-indigo-400 mb-3 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-3-3-3m0 18c-1.657 0-3-4.03-3-9s1.343-3 3-3m-9 9a9 9 0 019-9"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl tracking-tight">{{ $tool->getName() }}</h1>
        <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
            {{ $tool->getDescription() }}
        </p>
        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ __('common.badges.browser_based') }}
        </div>
    </div>

    
    <div x-data="{
        ip: '192.168.1.10',
        cidr: 24,
        netmask: '',
        network: '',
        broadcast: '',
        firstHost: '',
        lastHost: '',
        totalHosts: 0,
        usableHosts: 0,
        calculate() {
            let parts = this.ip.split('.').map(x => parseInt(x) || 0);
            if (parts.length !== 4) return;
            let ipInt = (parts[0] << 24) | (parts[1] << 16) | (parts[2] << 8) | parts[3];
            let c = parseInt(this.cidr) || 24;
            let maskInt = c === 0 ? 0 : (~0 << (32 - c));

            let netInt = ipInt & maskInt;
            let bcastInt = netInt | (~maskInt);

            let toIp = (num) => [(num >>> 24) & 255, (num >>> 16) & 255, (num >>> 8) & 255, num & 255].join('.');

            this.netmask = toIp(maskInt);
            this.network = toIp(netInt);
            this.broadcast = toIp(bcastInt);
            this.totalHosts = Math.pow(2, 32 - c);
            this.usableHosts = Math.max(0, this.totalHosts - 2);
            this.firstHost = toIp(netInt + 1);
            this.lastHost = toIp(bcastInt - 1);
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">IPv4 Address</label>
                <input type="text" x-model="ip" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Subnet Prefix / CIDR</label>
                <select x-model="cidr" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white font-mono">
                    <template x-for="c in 32" :key="c">
                        <option :value="c" x-text="'/' + c"></option>
                    </template>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-mono">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">Subnet Mask:</span>
                <div class="text-base font-bold text-slate-900 dark:text-white mt-1" x-text="netmask"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">Network Address:</span>
                <div class="text-base font-bold text-indigo-600 dark:text-indigo-400 mt-1" x-text="network"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">Broadcast Address:</span>
                <div class="text-base font-bold text-rose-600 dark:text-rose-400 mt-1" x-text="broadcast"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                <span class="text-xs text-slate-500 font-sans font-bold">Usable Host Range:</span>
                <div class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-1" x-text="firstHost + ' - ' + lastHost"></div>
            </div>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 sm:col-span-2 flex justify-between items-center font-sans">
                <div>
                    <span class="text-xs text-slate-500 font-bold">Total IP Addresses:</span>
                    <span class="text-base font-bold text-slate-900 dark:text-white ml-2" x-text="totalHosts.toLocaleString()"></span>
                </div>
                <div>
                    <span class="text-xs text-slate-500 font-bold">Usable Hosts:</span>
                    <span class="text-base font-bold text-indigo-600 dark:text-indigo-400 ml-2" x-text="usableHosts.toLocaleString()"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
