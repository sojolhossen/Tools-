<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="spfDkimApp()">
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
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="domain" @keydown.enter="auditEmailRecords()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <input type="text" x-model="dkimSelector" placeholder="DKIM Selector (e.g. google, default, k1)" class="w-full sm:w-56 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm">
                <button type="button" @click="auditEmailRecords()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Audit Email Security</span>
                    <span x-show="loading">Auditing Records...</span>
                </button>
            </div>

            <div class="space-y-6 pt-2">
                <!-- SPF Record -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">SPF Record (Sender Policy Framework)</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold" :class="spfRecord ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400'" x-text="spfRecord ? 'Valid SPF Record' : 'Missing SPF'"></span>
                    </div>
                    <div class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200 break-all p-3 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" x-text="spfRecord || 'No v=spf1 TXT record found on apex domain.'"></div>
                </div>

                <!-- DMARC Record -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">DMARC Policy (_dmarc.domain)</span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold" :class="dmarcRecord ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400'" x-text="dmarcRecord ? 'Protected' : 'Missing DMARC'"></span>
                    </div>
                    <div class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200 break-all p-3 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" x-text="dmarcRecord || 'No v=DMARC1 TXT record found at _dmarc host.'"></div>
                </div>

                <!-- DKIM Record -->
                <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500" x-text="'DKIM Key (' + (dkimSelector || 'default') + '._domainkey)'"></span>
                        <span class="px-2 py-0.5 rounded text-xs font-bold" :class="dkimRecord ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" x-text="dkimRecord ? 'DKIM Key Present' : 'Selector Not Found'"></span>
                    </div>
                    <div class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200 break-all p-3 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" x-text="dkimRecord || 'No public key found for this selector.'"></div>
                </div>
            </div>
        </div>

        <script>
        function spfDkimApp() {
            return {
                domain: 'google.com',
                dkimSelector: 'google',
                loading: false,
                spfRecord: '',
                dmarcRecord: '',
                dkimRecord: '',
                init() {
                    this.auditEmailRecords();
                },
                async auditEmailRecords() {
                    if (!this.domain.trim()) return;
                    this.loading = true;
                    this.spfRecord = '';
                    this.dmarcRecord = '';
                    this.dkimRecord = '';
                    const d = this.domain.replace(/^https?:\/\//, '').replace(/\/.*$/, '').trim();

                    try {
                        // 1. SPF query
                        const r1 = await fetch('https://dns.google/resolve?name=' + encodeURIComponent(d) + '&type=TXT').then(r => r.json());
                        if (r1.Answer) {
                            const match = r1.Answer.find(a => a.data && a.data.includes('v=spf1'));
                            if (match) this.spfRecord = match.data.replace(/"/g, '');
                        }

                        // 2. DMARC query
                        const r2 = await fetch('https://dns.google/resolve?name=' + encodeURIComponent('_dmarc.' + d) + '&type=TXT').then(r => r.json());
                        if (r2.Answer) {
                            const match2 = r2.Answer.find(a => a.data && a.data.includes('v=DMARC1'));
                            if (match2) this.dmarcRecord = match2.data.replace(/"/g, '');
                        }

                        // 3. DKIM query
                        const sel = this.dkimSelector.trim() || 'default';
                        const r3 = await fetch('https://dns.google/resolve?name=' + encodeURIComponent(sel + '._domainkey.' + d) + '&type=TXT').then(r => r.json());
                        if (r3.Answer) {
                            const match3 = r3.Answer.find(a => a.data && (a.data.includes('v=DKIM1') || a.data.includes('p=')));
                            if (match3) this.dkimRecord = match3.data.replace(/"/g, '');
                        }
                    } catch(e) {}
                    this.loading = false;
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
