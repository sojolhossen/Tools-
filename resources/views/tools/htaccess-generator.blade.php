<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="htaccessGenerator()">
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
            <!-- Settings Controls -->
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">HTTPS & Canonical Domain</h3>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="forceHttps" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Force HTTPS Redirection</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="forceWww" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Redirect non-www to www</span>
                    </label>
                    <div x-show="forceWww">
                        <input type="text" x-model="domainName" @input="generate()" placeholder="example.com" class="w-full text-xs font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2">
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Security & Directory Options</h3>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="disableIndexing" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Disable Directory Browsing (Options -Indexes)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="protectHtaccess" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Protect .htaccess & hidden files</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="securityHeaders" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Add Security Headers (X-Frame-Options, XSS)</span>
                    </label>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Performance & Caching</h3>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="enableGzip" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Enable Gzip Compression (mod_deflate)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="browserCaching" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Browser Cache Expire Headers (mod_expires)</span>
                    </label>
                </div>
            </div>

            <!-- Output Editor -->
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Generated .htaccess Content</span>
                        <button type="button" @click="copyCode()" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy Config'"></span>
                        </button>
                    </div>
                    <div class="p-5 flex-1">
                        <textarea x-model="output" readonly rows="22" class="w-full h-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-4 text-slate-900 dark:text-slate-100 focus:outline-none resize-none"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="chmod-calculator" />
    </div>

    @push('scripts')
    <script>
        function htaccessGenerator() {
            return {
                forceHttps: true,
                forceWww: false,
                domainName: 'example.com',
                disableIndexing: true,
                protectHtaccess: true,
                securityHeaders: true,
                enableGzip: true,
                browserCaching: true,
                output: '',
                copied: false,
                init() {
                    this.generate();
                },
                generate() {
                    let rules = ["# ----------------------------------------------------------------------", "# Apache .htaccess configuration generated by ToolZeno", "# ----------------------------------------------------------------------\n"];

                    rules.push("<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteBase /");

                    if (this.forceHttps) {
                        rules.push("\n    # Force HTTPS\n    RewriteCond %{HTTPS} !=on\n    RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]");
                    }

                    if (this.forceWww && this.domainName) {
                        const d = this.domainName.replace(/https?:\/\//, '').replace(/\/$/, '');
                        rules.push(`\n    # Redirect Non-WWW to WWW\n    RewriteCond %{HTTP_HOST} ^${d}$ [NC]\n    RewriteRule ^(.*)$ https://www.${d}/$1 [R=301,L]`);
                    }

                    rules.push("</IfModule>\n");

                    if (this.disableIndexing) {
                        rules.push("# Disable Directory Browsing\nOptions -Indexes\n");
                    }

                    if (this.protectHtaccess) {
                        rules.push("# Protect Hidden Files & .htaccess\n<FilesMatch \"^\\.\">\n    Order allow,deny\n    Deny from all\n</FilesMatch>\n");
                    }

                    if (this.securityHeaders) {
                        rules.push("<IfModule mod_headers.c>\n    # Security Headers\n    Header set X-Content-Type-Options \"nosniff\"\n    Header set X-Frame-Options \"SAMEORIGIN\"\n    Header set X-XSS-Protection \"1; mode=block\"\n    Header set Referrer-Policy \"strict-origin-when-cross-origin\"\n</IfModule>\n");
                    }

                    if (this.enableGzip) {
                        rules.push("<IfModule mod_deflate.c>\n    # Compress text, html, javascript, css, xml\n    AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css application/xml application/xhtml+xml application/rss+xml application/javascript application/x-javascript application/json\n</IfModule>\n");
                    }

                    if (this.browserCaching) {
                        rules.push("<IfModule mod_expires.c>\n    # Browser Caching\n    ExpiresActive On\n    ExpiresByType image/jpg \"access plus 1 year\"\n    ExpiresByType image/jpeg \"access plus 1 year\"\n    ExpiresByType image/gif \"access plus 1 year\"\n    ExpiresByType image/png \"access plus 1 year\"\n    ExpiresByType image/webp \"access plus 1 year\"\n    ExpiresByType text/css \"access plus 1 month\"\n    ExpiresByType application/javascript \"access plus 1 month\"\n</IfModule>\n");
                    }

                    this.output = rules.join('\n');
                },
                copyCode() {
                    navigator.clipboard.writeText(this.output).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
