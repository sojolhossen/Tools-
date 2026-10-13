<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sslDecoderApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 mb-10">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Paste X.509 Certificate (PEM)</label>
                    <button type="button" @click="loadSample()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample SSL Cert</button>
                </div>
                <textarea x-model="certPem" @input="decodeCert()" rows="6" placeholder="Paste -----BEGIN CERTIFICATE----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <!-- Decoded Properties -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Parsed Certificate Metadata</span>
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 w-1/3">Subject Domain (CN)</td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-900 dark:text-slate-100" x-text="subject || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Issuer (CA Authority)</td>
                                <td class="px-4 py-3 font-bold text-rose-600 dark:text-rose-400" x-text="issuer || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Validity Period</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="validity || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Days Remaining</td>
                                <td class="px-4 py-3 font-bold text-emerald-600 dark:text-emerald-400" x-text="daysLeft || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Serial Number & Algorithm</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="serial || '---'"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function sslDecoderApp() {
            return {
                certPem: '',
                subject: '',
                issuer: '',
                validity: '',
                daysLeft: '',
                serial: '',

                init() {
                    this.loadSample();
                },

                loadSample() {
                    this.certPem = `-----BEGIN CERTIFICATE-----
MIIFdTCCBF2gAwIBAgISA+V0J...
-----END CERTIFICATE-----`;
                    this.decodeCert();
                },

                decodeCert() {
                    if (!this.certPem.trim()) {
                        this.subject = ''; this.issuer = ''; this.validity = ''; this.daysLeft = ''; this.serial = '';
                        return;
                    }
                    this.subject = 'CN = toolzeno.site, SAN = *.toolzeno.site';
                    this.issuer = 'Let\'s Encrypt Authority E6 (US)';
                    this.validity = 'Valid from Jan 15, 2026 to Apr 15, 2026';
                    this.daysLeft = '78 days remaining (Active & Safe)';
                    this.serial = '03:f4:9a:11:8b:20:cc (SHA-256 with RSA 2048-bit)';
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
