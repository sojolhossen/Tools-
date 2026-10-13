<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="httpStatusLookup()">
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

        <!-- Search Bar and Category Filters -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4 sm:p-5 mb-8">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="search" placeholder="Search 200, 404, Unauthorized..." class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                    <button type="button" @click="activeGroup = 'all'" :class="activeGroup === 'all' ? 'bg-primary-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">All</button>
                    <button type="button" @click="activeGroup = '2xx'" :class="activeGroup === '2xx' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">2xx Success</button>
                    <button type="button" @click="activeGroup = '3xx'" :class="activeGroup === '3xx' ? 'bg-blue-600 text-white' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">3xx Redirect</button>
                    <button type="button" @click="activeGroup = '4xx'" :class="activeGroup === '4xx' ? 'bg-amber-600 text-white' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">4xx Client Error</button>
                    <button type="button" @click="activeGroup = '5xx'" :class="activeGroup === '5xx' ? 'bg-rose-600 text-white' : 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">5xx Server Error</button>
                </div>
            </div>
        </div>

        <!-- Status Codes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-10">
            <template x-for="item in filteredCodes" :key="item.code">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm hover:border-primary-500 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-xl font-extrabold px-2.5 py-0.5 rounded-lg" :class="item.badgeClass" x-text="item.code"></span>
                            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider" x-text="item.category"></span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1.5" x-text="item.title"></h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed" x-text="item.desc"></p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400 font-mono">
                        <span x-text="item.rfc"></span>
                        <button type="button" @click="copyCode(item.code)" class="text-primary-600 dark:text-primary-400 hover:underline">Copy Code</button>
                    </div>
                </div>
            </template>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="curl-converter" />
    </div>

    @push('scripts')
    <script>
        function httpStatusLookup() {
            return {
                search: '',
                activeGroup: 'all',
                codes: [
                    { code: 200, category: '2xx', title: 'OK', desc: 'Standard successful response for HTTP requests.', rfc: 'RFC 9110', badgeClass: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' },
                    { code: 201, category: '2xx', title: 'Created', desc: 'Request succeeded and a new resource was created.', rfc: 'RFC 9110', badgeClass: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' },
                    { code: 204, category: '2xx', title: 'No Content', desc: 'Request succeeded with no response body to return.', rfc: 'RFC 9110', badgeClass: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300' },
                    { code: 301, category: '3xx', title: 'Moved Permanently', desc: 'Resource URI has been permanently assigned to a new URI.', rfc: 'RFC 9110', badgeClass: 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300' },
                    { code: 302, category: '3xx', title: 'Found (Temporary Redirect)', desc: 'Resource temporarily resides under a different URI.', rfc: 'RFC 9110', badgeClass: 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300' },
                    { code: 304, category: '3xx', title: 'Not Modified', desc: 'Resource has not been modified since the version specified by cache.', rfc: 'RFC 9110', badgeClass: 'bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300' },
                    { code: 400, category: '4xx', title: 'Bad Request', desc: 'Server cannot process request due to client syntax error or malformed payload.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 401, category: '4xx', title: 'Unauthorized', desc: 'Authentication is required and has failed or not been provided.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 403, category: '4xx', title: 'Forbidden', desc: 'Server understood request but refuses authorization even if authenticated.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 404, category: '4xx', title: 'Not Found', desc: 'Server cannot find the requested resource at the target URL path.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 405, category: '4xx', title: 'Method Not Allowed', desc: 'Request method (e.g. POST) is not supported for requested route.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 422, category: '4xx', title: 'Unprocessable Entity', desc: 'Semantic errors in request data, widely used for validation failures.', rfc: 'RFC 9110', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 429, category: '4xx', title: 'Too Many Requests', desc: 'User has sent too many requests in a given amount of time (rate limit).', rfc: 'RFC 6585', badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300' },
                    { code: 500, category: '5xx', title: 'Internal Server Error', desc: 'Generic error indicating an unexpected condition occurred on backend server.', rfc: 'RFC 9110', badgeClass: 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' },
                    { code: 502, category: '5xx', title: 'Bad Gateway', desc: 'Server received an invalid response from upstream server or proxy.', rfc: 'RFC 9110', badgeClass: 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' },
                    { code: 503, category: '5xx', title: 'Service Unavailable', desc: 'Server is currently unable to handle request due to temporary overload or maintenance.', rfc: 'RFC 9110', badgeClass: 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' },
                    { code: 504, category: '5xx', title: 'Gateway Timeout', desc: 'Gateway server did not receive timely response from upstream application server.', rfc: 'RFC 9110', badgeClass: 'bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300' }
                ],
                get filteredCodes() {
                    const q = this.search.toLowerCase().trim();
                    return this.codes.filter(c => {
                        const matchGroup = this.activeGroup === 'all' || c.category === this.activeGroup;
                        const matchSearch = !q || c.code.toString().includes(q) || c.title.toLowerCase().includes(q) || c.desc.toLowerCase().includes(q);
                        return matchGroup && matchSearch;
                    });
                },
                copyCode(code) {
                    navigator.clipboard.writeText(code.toString());
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
