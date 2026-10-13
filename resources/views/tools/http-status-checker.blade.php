<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="httpStatusApp()">
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
            <!-- Search & Filter Bar -->
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="search" placeholder="Search by status code or name (e.g. 404, Not Found, 500, Unauthorized)" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <select x-model="categoryFilter" class="px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-sm font-semibold">
                    <option value="all">All Status Classes</option>
                    <option value="1xx">1xx Informational</option>
                    <option value="2xx">2xx Success</option>
                    <option value="3xx">3xx Redirection</option>
                    <option value="4xx">4xx Client Error</option>
                    <option value="5xx">5xx Server Error</option>
                </select>
            </div>

            <!-- Status Code Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="item in filteredList" :key="item.code">
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-lg font-black" :class="getColorClass(item.code)" x-text="item.code"></span>
                            <span class="text-xs font-bold px-2 py-0.5 rounded uppercase" :class="getBadgeClass(item.code)" x-text="item.title"></span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed" x-text="item.desc"></p>
                    </div>
                </template>
            </div>
        </div>

        <script>
        function httpStatusApp() {
            return {
                search: '',
                categoryFilter: 'all',
                statuses: [
                    { code: '200', title: 'OK', desc: 'Standard response for successful HTTP requests.' },
                    { code: '201', title: 'Created', desc: 'Request fulfilled and resulted in a new resource being created.' },
                    { code: '204', title: 'No Content', desc: 'Server successfully processed request but returns no content body.' },
                    { code: '301', title: 'Moved Permanently', desc: 'This and all future requests should be directed to the given URI.' },
                    { code: '302', title: 'Found', desc: 'Temporary redirect. Tells client to look at another URL.' },
                    { code: '304', title: 'Not Modified', desc: 'Resource has not been modified since version specified in headers.' },
                    { code: '307', title: 'Temporary Redirect', desc: 'Repeat request with same HTTP method to another URI.' },
                    { code: '308', title: 'Permanent Redirect', desc: 'Repeat request with same method permanently.' },
                    { code: '400', title: 'Bad Request', desc: 'Server cannot process request due to client syntax error or malformed payload.' },
                    { code: '401', title: 'Unauthorized', desc: 'Authentication is required and has failed or has not been provided.' },
                    { code: '403', title: 'Forbidden', desc: 'Request understood, but server refuses to authorize it.' },
                    { code: '404', title: 'Not Found', desc: 'Requested resource could not be found on server.' },
                    { code: '405', title: 'Method Not Allowed', desc: 'Request method is not supported for requested resource.' },
                    { code: '429', title: 'Too Many Requests', desc: 'User has sent too many requests in a given amount of time (rate limited).' },
                    { code: '500', title: 'Internal Server Error', desc: 'Generic error message when an unexpected condition was encountered.' },
                    { code: '502', title: 'Bad Gateway', desc: 'Server acting as gateway/proxy received an invalid upstream response.' },
                    { code: '503', title: 'Service Unavailable', desc: 'Server cannot handle request due to overload or maintenance.' },
                    { code: '504', title: 'Gateway Timeout', desc: 'Gateway/proxy did not receive a timely upstream response.' }
                ],
                get filteredList() {
                    return this.statuses.filter(s => {
                        const matchCat = this.categoryFilter === 'all' || s.code.startsWith(this.categoryFilter[0]);
                        const q = this.search.toLowerCase();
                        const matchQ = !q || s.code.includes(q) || s.title.toLowerCase().includes(q) || s.desc.toLowerCase().includes(q);
                        return matchCat && matchQ;
                    });
                },
                getColorClass(code) {
                    if (code.startsWith('2')) return 'text-emerald-600 dark:text-emerald-400';
                    if (code.startsWith('3')) return 'text-blue-600 dark:text-blue-400';
                    if (code.startsWith('4')) return 'text-amber-600 dark:text-amber-400';
                    return 'text-rose-600 dark:text-rose-400';
                },
                getBadgeClass(code) {
                    if (code.startsWith('2')) return 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400';
                    if (code.startsWith('3')) return 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400';
                    if (code.startsWith('4')) return 'bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400';
                    return 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400';
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
