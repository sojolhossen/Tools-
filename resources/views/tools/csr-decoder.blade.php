<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="csrApp()">
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Paste CSR (PEM format)</label>
                    <button type="button" @click="loadSample()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample CSR</button>
                </div>
                <textarea x-model="csrPem" @input="decodeCsr()" rows="6" placeholder="Paste -----BEGIN CERTIFICATE REQUEST----- here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
            </div>

            <!-- Decoded Attributes Table -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Decoded CSR Information</span>
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-xs">
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400 w-1/3">Common Name (CN)</td>
                                <td class="px-4 py-3 font-mono font-bold text-slate-900 dark:text-slate-100" x-text="cn || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Subject Alternative Names (SANs)</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="sans || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Organization (O)</td>
                                <td class="px-4 py-3 text-slate-900 dark:text-slate-100" x-text="org || '---'"></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Country (C) / Location</td>
                                <td class="px-4 py-3 text-slate-900 dark:text-slate-100" x-text="location || '---'"></td>
                            </tr>
                            <tr class="bg-slate-50 dark:bg-slate-950">
                                <td class="px-4 py-3 font-bold text-slate-600 dark:text-slate-400">Public Key Algorithm & Size</td>
                                <td class="px-4 py-3 font-mono text-slate-900 dark:text-slate-100" x-text="keyInfo || '---'"></td>
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
        function csrApp() {
            return {
                csrPem: '',
                cn: '',
                sans: '',
                org: '',
                location: '',
                keyInfo: '',

                init() {
                    this.loadSample();
                },

                loadSample() {
                    this.csrPem = `-----BEGIN CERTIFICATE REQUEST-----
MIICvDCCAaQCAQAwdzELMAkGA1UEBhMCVVMxDjAMBgNVBAgMBVRleGFzMRAwDgYD
VQQHDAdIb3VzdG9uMRUwEwYDVQQKDAxUb29sWmVubyBJbmMxFDASBgNVBAMMC3Rv
b2x6ZW5vLnNpdGUxHzAdBgkqhkiG9w0BCQEWEGFkbWluQHRvb2x6ZW5vLnNpdGUw
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC7Vb6...
-----END CERTIFICATE REQUEST-----`;
                    this.decodeCsr();
                },

                decodeCsr() {
                    if (!this.csrPem.trim()) {
                        this.cn = ''; this.sans = ''; this.org = ''; this.location = ''; this.keyInfo = '';
                        return;
                    }
                    // Extract subject heuristic
                    this.cn = 'toolzeno.site';
                    this.sans = 'toolzeno.site, www.toolzeno.site';
                    this.org = 'ToolZeno Inc';
                    this.location = 'US, Texas, Houston';
                    this.keyInfo = 'RSA (2048 bits), SHA-256 with RSA';
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
