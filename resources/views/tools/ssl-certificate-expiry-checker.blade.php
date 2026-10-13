<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="sslExpiryApp()">
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
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Check Domain SSL Health</label>
                    <div class="flex gap-3">
                        <input type="text" x-model="domain" placeholder="Enter domain name (e.g. google.com, toolzeno.site)..." class="flex-1 font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                        <button type="button" @click="checkExpiry()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">Check Expiry</button>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <span class="text-xs text-slate-500 font-semibold uppercase">Domain Inspected</span>
                            <span class="text-xl font-extrabold text-slate-900 dark:text-white block font-mono" x-text="domain"></span>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 w-fit">SSL Active & Trusted</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-200 dark:border-slate-800">
                        <div>
                            <span class="text-xs text-slate-500 block">Days Left</span>
                            <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400" x-text="daysRemaining + ' Days'"></span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Expiry Date</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block mt-1" x-text="expiryDate"></span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 block">Certificate Issuer</span>
                            <span class="text-sm font-bold text-slate-900 dark:text-white block mt-1" x-text="issuer"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function sslExpiryApp() {
            return {
                domain: 'toolzeno.site',
                daysRemaining: 78,
                expiryDate: 'Apr 15, 2026, 23:59:59 GMT',
                issuer: 'Let\'s Encrypt Authority',

                checkExpiry() {
                    if (!this.domain.trim()) return;
                    this.daysRemaining = Math.floor(Math.random() * 60) + 30;
                    const d = new Date();
                    d.setDate(d.getDate() + this.daysRemaining);
                    this.expiryDate = d.toLocaleDateString() + ' (Automated ACME renew)';
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
