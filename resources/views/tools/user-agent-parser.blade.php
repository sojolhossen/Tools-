<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="userAgentParserApp()">
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
                    if (/Edg\/([0-9.]+)/i.test(ua)) { bName = 'Microsoft Edge'; bVer = RegExp.$1; }
                    else if (/Chrome\/([0-9.]+)/i.test(ua)) { bName = 'Google Chrome'; bVer = RegExp.$1; }
                    else if (/Firefox\/([0-9.]+)/i.test(ua)) { bName = 'Mozilla Firefox'; bVer = RegExp.$1; }
                    else if (/Version\/([0-9.]+).*Safari/i.test(ua)) { bName = 'Apple Safari'; bVer = RegExp.$1; }
                    else if (/Opera|OPR\/([0-9.]+)/i.test(ua)) { bName = 'Opera'; bVer = RegExp.$1; }
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
