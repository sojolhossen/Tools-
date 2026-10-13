<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="urlNormalizer()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="stripUtm" @change="normalize()" class="rounded text-primary-600 focus:ring-0">
                        <span>Remove UTM tracking tags</span>
                    </label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="sortParams" @change="normalize()" class="rounded text-primary-600 focus:ring-0">
                        <span>Sort Query Params alphabetically</span>
                    </label>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="lowercaseHost" @change="normalize()" class="rounded text-primary-600 focus:ring-0">
                        <span>Lowercase Host</span>
                    </label>
                </div>
                <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                    Sample
                </button>
            </div>

            <div class="p-5 sm:p-6 space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Raw Messy URL</label>
                    <input type="text" x-model="rawUrl" @input="normalize()" placeholder="HTTP://Example.COM:80/path/./sub/../target//?b=2&a=1&utm_source=fb" class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Canonical Normalized URL</label>
                        <button type="button" @click="copyOutput()" :disabled="!normalizedUrl" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy URL'"></span>
                        </button>
                    </div>
                    <div class="font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[46px]" x-text="normalizedUrl || '---'"></div>
                </div>

                <!-- URL Breakdown Table -->
                <template x-if="parsedParts">
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-3">Deconstructed Components</span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block font-semibold">Protocol:</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="parsedParts.protocol"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block font-semibold">Hostname:</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="parsedParts.hostname"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block font-semibold">Clean Path:</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="parsedParts.pathname"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                                <span class="text-slate-400 block font-semibold">Query String:</span>
                                <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="parsedParts.search || 'None'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="url-encoder-decoder" />
    </div>

    @push('scripts')
    <script>
        function urlNormalizer() {
            return {
                rawUrl: '',
                normalizedUrl: '',
                stripUtm: true,
                sortParams: true,
                lowercaseHost: true,
                parsedParts: null,
                copied: false,
                init() {
                    this.loadSample();
                },
                normalize() {
                    if (!this.rawUrl.trim()) {
                        this.normalizedUrl = '';
                        this.parsedParts = null;
                        return;
                    }
                    try {
                        let input = this.rawUrl.trim();
                        if (!/^https?:\/\//i.test(input)) {
                            input = 'https://' + input;
                        }

                        const u = new URL(input);
                        if (this.lowercaseHost) {
                            u.hostname = u.hostname.toLowerCase();
                        }

                        // Remove default ports
                        if ((u.protocol === 'http:' && u.port === '80') || (u.protocol === 'https:' && u.port === '443')) {
                            u.port = '';
                        }

                        // Clean double slashes in path
                        u.pathname = u.pathname.replace(/\/+/g, '/');

                        // Filter UTM parameters
                        if (this.stripUtm) {
                            const keysToRemove = [];
                            for (const k of u.searchParams.keys()) {
                                if (k.startsWith('utm_') || ['fbclid', 'gclid', 'ref', 'source'].includes(k.toLowerCase())) {
                                    keysToRemove.push(k);
                                }
                            }
                            keysToRemove.forEach(k => u.searchParams.delete(k));
                        }

                        // Sort parameters
                        if (this.sortParams) {
                            u.searchParams.sort();
                        }

                        this.normalizedUrl = u.toString();
                        this.parsedParts = {
                            protocol: u.protocol,
                            hostname: u.hostname,
                            pathname: u.pathname,
                            search: u.search
                        };
                    } catch (e) {
                        this.normalizedUrl = 'Invalid URL syntax';
                        this.parsedParts = null;
                    }
                },
                loadSample() {
                    this.rawUrl = 'HTTP://Example.COM:80/products//item/./detail?z=last&utm_source=facebook&utm_campaign=winter&a=first#reviews';
                    this.normalize();
                },
                copyOutput() {
                    if (!this.normalizedUrl) return;
                    navigator.clipboard.writeText(this.normalizedUrl).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
