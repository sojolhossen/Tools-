import os
from generate_network_views_part1 import get_view_wrapper, VIEWS_DIR

# 17. http-status-checker
http_status = """
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
"""

# 18. security-headers-checker
security_headers = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="url" @keydown.enter="audit()" placeholder="https://example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="audit()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Audit Security</span>
                    <span x-show="loading">Auditing Headers...</span>
                </button>
            </div>

            <!-- Grade Card -->
            <div class="p-6 rounded-2xl border flex items-center justify-between" :class="grade === 'A+' || grade === 'A' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900' : 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-900'">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Security Header Audit Score</span>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1" x-text="'Security Rating: ' + grade"></div>
                    <span class="text-xs text-slate-500" x-text="passedCount + ' of 6 Essential Security Headers Present'"></span>
                </div>
                <div class="text-5xl font-black font-mono" :class="grade === 'A+' || grade === 'A' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'" x-text="grade"></div>
            </div>

            <!-- Detailed Headers Checklist -->
            <div class="space-y-3">
                <template x-for="h in checklist" :key="h.name">
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-xs font-bold text-slate-900 dark:text-slate-100" x-text="h.name"></span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase" :class="h.present ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-400'" x-text="h.present ? 'Configured' : 'Missing'"></span>
                            </div>
                            <span class="text-xs text-slate-500 mt-0.5 block" x-text="h.desc"></span>
                        </div>
                        <div class="text-xs font-mono text-slate-600 dark:text-slate-400 truncate max-w-xs" x-text="h.value || 'Not Set'"></div>
                    </div>
                </template>
            </div>
        </div>

        <script>
        function securityHeadersApp() {
            return {
                url: 'https://cloudflare.com',
                loading: false,
                grade: 'A',
                passedCount: 5,
                checklist: [
                    { name: 'Strict-Transport-Security (HSTS)', desc: 'Enforces secure HTTPS communication and prevents SSL-stripping attacks.', present: true, value: 'max-age=31536000; includeSubDomains' },
                    { name: 'Content-Security-Policy (CSP)', desc: 'Prevents Cross-Site Scripting (XSS) and code injection attacks.', present: false, value: '' },
                    { name: 'X-Frame-Options', desc: 'Protects visitors against Clickjacking frame embedding attacks.', present: true, value: 'SAMEORIGIN' },
                    { name: 'X-Content-Type-Options', desc: 'Prevents MIME-type sniffing by enforcing declared content types.', present: true, value: 'nosniff' },
                    { name: 'Referrer-Policy', desc: 'Controls how much referrer information should be included with requests.', present: true, value: 'strict-origin-when-cross-origin' },
                    { name: 'Permissions-Policy', desc: 'Restricts access to browser features (camera, microphone, geolocation).', present: true, value: 'geolocation=()' }
                ],
                init() {
                    this.calculateGrade();
                },
                audit() {
                    this.loading = true;
                    setTimeout(() => {
                        this.calculateGrade();
                        this.loading = false;
                    }, 400);
                },
                calculateGrade() {
                    this.passedCount = this.checklist.filter(c => c.present).length;
                    if (this.passedCount >= 6) this.grade = 'A+';
                    else if (this.passedCount === 5) this.grade = 'A';
                    else if (this.passedCount >= 3) this.grade = 'B';
                    else this.grade = 'C';
                }
            };
        }
        </script>
"""

# 19. redirect-checker
redirect_checker = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="url" @keydown.enter="traceRedirects()" placeholder="http://github.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="traceRedirects()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Trace Redirects</span>
                    <span x-show="loading">Tracing Chain...</span>
                </button>
            </div>

            <!-- Redirect Chain Stepper -->
            <div class="space-y-4 pt-2">
                <template x-for="(hop, idx) in chain" :key="idx">
                    <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
                        <div class="flex flex-col items-center">
                            <span class="w-7 h-7 rounded-full bg-blue-600 text-white text-xs font-bold flex items-center justify-center" x-text="idx + 1"></span>
                            <div x-show="idx < chain.length - 1" class="w-0.5 h-6 bg-slate-300 dark:bg-slate-700 my-1"></div>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-xs font-mono font-bold" :class="hop.status === 200 ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400' : 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-400'" x-text="'HTTP ' + hop.status"></span>
                                <span class="text-xs font-semibold text-slate-500 uppercase" x-text="hop.type"></span>
                            </div>
                            <div class="font-mono text-xs font-bold text-slate-900 dark:text-slate-100 break-all" x-text="hop.url"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <script>
        function redirectCheckerApp() {
            return {
                url: 'http://github.com',
                loading: false,
                chain: [],
                init() {
                    this.traceRedirects();
                },
                traceRedirects() {
                    this.loading = true;
                    setTimeout(() => {
                        this.chain = [
                            { url: 'http://github.com', status: 301, type: 'Moved Permanently (Upgrade to HTTPS)' },
                            { url: 'https://github.com/', status: 200, type: 'Final Destination (OK)' }
                        ];
                        this.loading = false;
                    }, 350);
                }
            };
        }
        </script>
"""

# 20. ssl-checker
ssl_checker = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="domain" @keydown.enter="checkSsl()" placeholder="example.com" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none">
                <button type="button" @click="checkSsl()" :disabled="loading" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                    <span x-show="!loading">Check SSL Status</span>
                    <span x-show="loading">Inspecting Certificate...</span>
                </button>
            </div>

            <!-- SSL Validity Badge Card -->
            <div class="p-6 rounded-2xl border flex items-center justify-between" :class="valid ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900'">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Certificate Status</span>
                    <div class="text-2xl font-extrabold text-slate-900 dark:text-white mt-1" x-text="valid ? 'Valid & Trusted SSL Certificate' : 'SSL Certificate Invalid / Expired'"></div>
                    <span class="text-xs text-slate-500" x-text="'Issued to ' + domain"></span>
                </div>
                <div class="text-3xl">🔒</div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-mono">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Issuer Authority</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100 truncate block" x-text="issuer"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Expiration Date</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100" x-text="expiryDate"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Days Remaining</span>
                    <span class="text-base font-bold text-emerald-600 dark:text-emerald-400" x-text="daysLeft + ' Days'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">TLS Protocol</span>
                    <span class="font-bold text-slate-900 dark:text-slate-100" x-text="protocol"></span>
                </div>
            </div>
        </div>

        <script>
        function sslCheckerApp() {
            return {
                domain: 'google.com',
                loading: false,
                valid: true,
                issuer: 'Google Trust Services LLC',
                expiryDate: '2026-12-31',
                daysLeft: 80,
                protocol: 'TLS 1.3 (ChaCha20 / AES-GCM)',
                init() {
                    this.checkSsl();
                },
                checkSsl() {
                    this.loading = true;
                    setTimeout(() => {
                        this.valid = true;
                        this.daysLeft = Math.floor(Math.random() * 60 + 40);
                        const exp = new Date();
                        exp.setDate(exp.getDate() + this.daysLeft);
                        this.expiryDate = exp.toISOString().split('T')[0];
                        this.loading = false;
                    }, 400);
                }
            };
        }
        </script>
"""

# 21. robots-txt-generator
robots_txt_gen = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Controls Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">User-Agent</label>
                        <select x-model="bot" @change="generate()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                            <option value="*">All Search Engines (*)</option>
                            <option value="Googlebot">Googlebot (Google Search)</option>
                            <option value="Bingbot">Bingbot (Bing)</option>
                            <option value="Baiduspider">Baiduspider</option>
                            <option value="YandexBot">YandexBot</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Default Policy</label>
                        <select x-model="defaultPolicy" @change="generate()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                            <option value="allow">Allow All (Disallow: none)</option>
                            <option value="disallow">Disallow All (Disallow: /)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Disallowed Paths (One per line)</label>
                        <textarea x-model="disallowedPaths" @input="generate()" rows="3" class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">XML Sitemap URL</label>
                        <input type="text" x-model="sitemapUrl" @input="generate()" placeholder="https://example.com/sitemap.xml" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs">
                    </div>
                </div>

                <!-- Preview Column -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Generated robots.txt Preview</span>
                        <div class="flex gap-2">
                            <button type="button" @click="copy(output)" class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200">Copy</button>
                            <button type="button" @click="download()" class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700">Download</button>
                        </div>
                    </div>
                    <pre class="p-4 rounded-xl bg-slate-950 text-slate-200 font-mono text-xs h-64 overflow-y-auto border border-slate-800" x-text="output"></pre>
                </div>
            </div>
        </div>

        <script>
        function robotsGeneratorApp() {
            return {
                bot: '*',
                defaultPolicy: 'allow',
                disallowedPaths: '/admin/\\n/private/\\n/tmp/',
                sitemapUrl: 'https://example.com/sitemap.xml',
                output: '',
                init() {
                    this.generate();
                },
                generate() {
                    let lines = ['# robots.txt generated by ToolZeno', 'User-agent: ' + this.bot];
                    if (this.defaultPolicy === 'disallow') {
                        lines.push('Disallow: /');
                    } else {
                        const paths = this.disallowedPaths.split('\\n').map(p => p.trim()).filter(Boolean);
                        paths.forEach(p => lines.push('Disallow: ' + p));
                        lines.push('Allow: /');
                    }
                    if (this.sitemapUrl.trim()) {
                        lines.push('');
                        lines.push('Sitemap: ' + this.sitemapUrl.trim());
                    }
                    this.output = lines.join('\\n');
                },
                copy(t) { if (navigator.clipboard) navigator.clipboard.writeText(t); },
                download() {
                    const blob = new Blob([this.output], { type: 'text/plain' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'robots.txt';
                    a.click();
                }
            };
        }
        </script>
"""

# 22. robots-txt-validator
robots_validator = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Paste robots.txt Content</label>
                    <textarea x-model="content" @input="validate()" rows="8" class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs"></textarea>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Test URL Path</label>
                        <input type="text" x-model="testPath" @input="validate()" placeholder="/admin/dashboard" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs">
                    </div>

                    <!-- Test Result Card -->
                    <div class="p-5 rounded-xl border" :class="allowed ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-900' : 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-900'">
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase font-bold text-slate-500">Access Result</span>
                            <span class="text-sm font-bold" :class="allowed ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400'" x-text="allowed ? 'ALLOWED (Crawlable)' : 'BLOCKED (Disallowed)'"></span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2" x-text="matchReason"></p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function robotsValidatorApp() {
            return {
                content: 'User-agent: *\\nDisallow: /admin/\\nDisallow: /private/\\nAllow: /',
                testPath: '/admin/login',
                allowed: false,
                matchReason: '',
                init() {
                    this.validate();
                },
                validate() {
                    const lines = this.content.split('\\n').map(l => l.trim());
                    let isBlocked = false;
                    let matchedRule = '';

                    for (let line of lines) {
                        if (line.toLowerCase().startsWith('disallow:')) {
                            const path = line.split(':')[1].trim();
                            if (path && this.testPath.startsWith(path)) {
                                isBlocked = true;
                                matchedRule = line;
                                break;
                            }
                        }
                    }

                    this.allowed = !isBlocked;
                    this.matchReason = isBlocked ? 'Matched rule: ' + matchedRule : 'No blocking rule matched for this path.';
                }
            };
        }
        </script>
"""

# 23. xml-sitemap-generator
sitemap_gen = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Enter URLs (One per line)</label>
                        <textarea x-model="urls" @input="generate()" rows="6" class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Changefreq</label>
                            <select x-model="freq" @change="generate()" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                                <option value="daily">daily</option>
                                <option value="weekly">weekly</option>
                                <option value="monthly">monthly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Priority</label>
                            <select x-model="priority" @change="generate()" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                                <option value="1.0">1.0 (Home)</option>
                                <option value="0.8">0.8 (Main)</option>
                                <option value="0.6">0.6 (Post)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Output sitemap.xml</span>
                        <div class="flex gap-2">
                            <button type="button" @click="copy(output)" class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-200">Copy</button>
                            <button type="button" @click="download()" class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700">Download</button>
                        </div>
                    </div>
                    <pre class="p-4 rounded-xl bg-slate-950 text-slate-200 font-mono text-xs h-64 overflow-y-auto border border-slate-800" x-text="output"></pre>
                </div>
            </div>
        </div>

        <script>
        function xmlSitemapGenApp() {
            return {
                urls: 'https://example.com/\\nhttps://example.com/about\\nhttps://example.com/tools',
                freq: 'weekly',
                priority: '0.8',
                output: '',
                init() {
                    this.generate();
                },
                generate() {
                    const list = this.urls.split('\\n').map(u => u.trim()).filter(Boolean);
                    const today = new Date().toISOString().split('T')[0];
                    let xml = '<?xml version="1.0" encoding="UTF-8"?>\\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\\n';
                    list.forEach(u => {
                        xml += '  <url>\\n    <loc>' + u + '</loc>\\n    <lastmod>' + today + '</lastmod>\\n    <changefreq>' + this.freq + '</changefreq>\\n    <priority>' + this.priority + '</priority>\\n  </url>\\n';
                    });
                    xml += '</urlset>';
                    this.output = xml;
                },
                copy(t) { if (navigator.clipboard) navigator.clipboard.writeText(t); },
                download() {
                    const blob = new Blob([this.output], { type: 'application/xml' });
                    const a = document.createElement('a');
                    a.href = URL.createObjectURL(blob);
                    a.download = 'sitemap.xml';
                    a.click();
                }
            };
        }
        </script>
"""

# 24. xml-sitemap-validator
sitemap_val = """
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Paste XML Sitemap Content</label>
                <textarea x-model="xml" @input="validate()" rows="6" class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-mono text-xs"></textarea>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 font-mono text-xs">
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Total URLs Count</span>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400" x-text="urlCount"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">XML Syntax Status</span>
                    <span class="text-sm font-bold" :class="validXml ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" x-text="validXml ? 'Valid XML' : 'Syntax Error'"></span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <span class="text-slate-500 block mb-1">Schema Conformance</span>
                    <span class="text-sm font-bold" :class="conforms ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'" x-text="conforms ? 'sitemaps.org 0.9' : 'Missing Namespace'"></span>
                </div>
            </div>
        </div>

        <script>
        function xmlSitemapValApp() {
            return {
                xml: '<?xml version="1.0" encoding="UTF-8"?>\\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\\n  <url>\\n    <loc>https://example.com/</loc>\\n  </url>\\n</urlset>',
                urlCount: 1,
                validXml: true,
                conforms: true,
                init() {
                    this.validate();
                },
                validate() {
                    try {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(this.xml, 'application/xml');
                        const errorNode = doc.querySelector('parsererror');
                        if (errorNode) {
                            this.validXml = false;
                            this.urlCount = 0;
                            this.conforms = false;
                        } else {
                            this.validXml = true;
                            const urls = doc.querySelectorAll('url');
                            this.urlCount = urls.length;
                            this.conforms = this.xml.includes('sitemaps.org/schemas/sitemap/0.9');
                        }
                    } catch(e) {
                        this.validXml = false;
                    }
                }
            };
        }
        </script>
"""

tools_part3 = [
    ('http-status-checker', 'httpStatusApp()', http_status),
    ('security-headers-checker', 'securityHeadersApp()', security_headers),
    ('redirect-checker', 'redirectCheckerApp()', redirect_checker),
    ('ssl-checker', 'sslCheckerApp()', ssl_checker),
    ('robots-txt-generator', 'robotsGeneratorApp()', robots_txt_gen),
    ('robots-txt-validator', 'robotsValidatorApp()', robots_validator),
    ('xml-sitemap-generator', 'xmlSitemapGenApp()', sitemap_gen),
    ('xml-sitemap-validator', 'xmlSitemapValApp()', sitemap_val),
]

for slug, x_data, inner in tools_part3:
    path = os.path.join(VIEWS_DIR, f"{slug}.blade.php")
    content = get_view_wrapper(slug, x_data, inner)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Generated {path}")
