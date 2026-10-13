<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="apiPayloadTester()">
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

        <div class="space-y-6 mb-10">
            <!-- Request Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <!-- Method & URL Bar -->
                <div class="flex flex-col sm:flex-row gap-3 items-stretch">
                    <select x-model="method" class="w-full sm:w-28 font-bold text-xs sm:text-sm bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                    <input type="text" x-model="url" placeholder="https://jsonplaceholder.typicode.com/posts/1" class="flex-1 font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <button type="button" @click="sendRequest()" :disabled="loading" class="px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm bg-primary-600 hover:bg-primary-700 text-white shadow-sm flex items-center justify-center gap-2 transition-all disabled:opacity-50">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span x-text="loading ? 'Sending...' : 'Send Request'"></span>
                    </button>
                </div>

                <!-- Tabs: Headers & Body -->
                <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4">
                    <div class="flex items-center gap-2 mb-3">
                        <button type="button" @click="tab = 'body'" :class="tab === 'body' ? 'bg-slate-200 dark:bg-slate-800 font-bold' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs transition-colors">Request Body (JSON)</button>
                        <button type="button" @click="tab = 'headers'" :class="tab === 'headers' ? 'bg-slate-200 dark:bg-slate-800 font-bold' : 'text-slate-500'" class="px-3 py-1 rounded-lg text-xs transition-colors">Headers (JSON)</button>
                    </div>

                    <div x-show="tab === 'body'">
                        <textarea x-model="body" placeholder='{"title": "Test Post", "body": "Hello world", "userId": 1}' rows="5" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                    </div>
                    <div x-show="tab === 'headers'">
                        <textarea x-model="headers" placeholder='{"Content-Type": "application/json", "Accept": "application/json"}' rows="4" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Response Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3 text-xs font-semibold">
                    <span class="uppercase tracking-wider text-slate-600 dark:text-slate-400">Response Inspector</span>
                    <div class="flex items-center gap-3" x-show="status !== null">
                        <span class="px-2.5 py-0.5 rounded-full font-mono font-bold" :class="status >= 200 && status < 300 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300'" x-text="'Status: ' + status + ' ' + statusText"></span>
                        <span class="text-slate-400 font-mono" x-text="duration + ' ms'"></span>
                    </div>
                </div>
                <div class="p-5">
                    <pre class="font-mono text-xs sm:text-sm text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800 overflow-x-auto whitespace-pre-wrap min-h-[160px]" x-text="responseBody || '// Send a request to inspect response data and headers...'"></pre>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="curl-converter" />
    </div>

    @push('scripts')
    <script>
        function apiPayloadTester() {
            return {
                method: 'GET',
                url: 'https://jsonplaceholder.typicode.com/posts/1',
                tab: 'body',
                body: '',
                headers: '{\n  "Content-Type": "application/json"\n}',
                loading: false,
                status: null,
                statusText: '',
                duration: 0,
                responseBody: '',
                async sendRequest() {
                    if (!this.url.trim()) return;
                    this.loading = true;
                    this.responseBody = '';
                    this.status = null;
                    const startTime = performance.now();

                    try {
                        let parsedHeaders = {};
                        if (this.headers.trim()) {
                            try { parsedHeaders = JSON.parse(this.headers); } catch(e){}
                        }

                        const opts = {
                            method: this.method,
                            headers: parsedHeaders
                        };
                        if (['POST', 'PUT', 'PATCH'].includes(this.method) && this.body.trim()) {
                            opts.body = this.body;
                        }

                        const res = await fetch(this.url, opts);
                        this.duration = Math.round(performance.now() - startTime);
                        this.status = res.status;
                        this.statusText = res.statusText || '';

                        const contentType = res.headers.get('content-type') || '';
                        if (contentType.includes('application/json')) {
                            const data = await res.json();
                            this.responseBody = JSON.stringify(data, null, 2);
                        } else {
                            this.responseBody = await res.text();
                        }
                    } catch (err) {
                        this.duration = Math.round(performance.now() - startTime);
                        this.status = 0;
                        this.statusText = 'Error';
                        this.responseBody = 'Network / CORS Error: ' + err.message + '\n\nNote: If calling external APIs, the server must support CORS headers (Access-Control-Allow-Origin).';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
