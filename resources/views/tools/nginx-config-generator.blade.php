<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="nginxConfigGenerator()">
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
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-4 text-xs">
                    <h3 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Server & Domain</h3>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Server Name / Domain:</label>
                        <input type="text" x-model="domain" @input="generate()" class="w-full font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2 text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Application Type:</label>
                        <select x-model="appType" @change="generate()" class="w-full font-semibold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2 text-slate-900 dark:text-slate-100">
                            <option value="php">PHP-FPM (Laravel / WordPress)</option>
                            <option value="proxy">Reverse Proxy (Node.js / Express / Next.js)</option>
                            <option value="spa">Static SPA (Vue / React / HTML)</option>
                        </select>
                    </div>
                    <div x-show="appType === 'php'">
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Root Directory:</label>
                        <input type="text" x-model="rootPath" @input="generate()" class="w-full font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2 text-slate-900 dark:text-slate-100">
                    </div>
                    <div x-show="appType === 'proxy'">
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Upstream Proxy URL:</label>
                        <input type="text" x-model="proxyPass" @input="generate()" class="w-full font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2 text-slate-900 dark:text-slate-100">
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm space-y-3 text-xs">
                    <h3 class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Security & Performance</h3>
                    <label class="flex items-center gap-2 text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="enableSsl" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>HTTPS / SSL (Port 443)</span>
                    </label>
                    <label class="flex items-center gap-2 text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="enableGzip" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Gzip Compression</span>
                    </label>
                    <label class="flex items-center gap-2 text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" x-model="securityHeaders" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Security Headers (X-Frame, nosniff)</span>
                    </label>
                </div>
            </div>

            <!-- Output Editor -->
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden h-full flex flex-col">
                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Generated Nginx server block</span>
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
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="htaccess-generator" />
    </div>

    @push('scripts')
    <script>
        function nginxConfigGenerator() {
            return {
                domain: 'example.com',
                appType: 'php',
                rootPath: '/var/www/example.com/public',
                proxyPass: 'http://127.0.0.1:3000',
                enableSsl: true,
                enableGzip: true,
                securityHeaders: true,
                output: '',
                copied: false,
                init() {
                    this.generate();
                },
                generate() {
                    let c = `# Nginx Configuration for ${this.domain || 'example.com'}\n\n`;

                    if (this.enableSsl) {
                        c += `server {\n    listen 80;\n    listen [::]:80;\n    server_name ${this.domain} www.${this.domain};\n    return 301 https://$host$request_uri;\n}\n\n`;
                        c += `server {\n    listen 443 ssl http2;\n    listen [::]:443 ssl http2;\n    server_name ${this.domain};\n\n`;
                        c += `    ssl_certificate /etc/letsencrypt/live/${this.domain}/fullchain.pem;\n    ssl_certificate_key /etc/letsencrypt/live/${this.domain}/privkey.pem;\n    ssl_protocols TLSv1.2 TLSv1.3;\n    ssl_ciphers HIGH:!aNULL:!MD5;\n\n`;
                    } else {
                        c += `server {\n    listen 80;\n    listen [::]:80;\n    server_name ${this.domain};\n\n`;
                    }

                    if (this.appType === 'php') {
                        c += `    root ${this.rootPath};\n    index index.php index.html;\n\n`;
                        c += `    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n\n`;
                        c += `    location ~ \\.php$ {\n        include snippets/fastcgi-php.conf;\n        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;\n    }\n\n`;
                        c += `    location ~ /\\.(?!well-known).* {\n        deny all;\n    }\n`;
                    } else if (this.appType === 'proxy') {
                        c += `    location / {\n        proxy_pass ${this.proxyPass};\n        proxy_http_version 1.1;\n        proxy_set_header Upgrade $http_upgrade;\n        proxy_set_header Connection 'upgrade';\n        proxy_set_header Host $host;\n        proxy_cache_bypass $http_upgrade;\n        proxy_set_header X-Real-IP $remote_addr;\n        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;\n        proxy_set_header X-Forwarded-Proto $scheme;\n    }\n`;
                    } else if (this.appType === 'spa') {
                        c += `    root /var/www/${this.domain}/dist;\n    index index.html;\n\n`;
                        c += `    location / {\n        try_files $uri $uri/ /index.html;\n    }\n`;
                    }

                    if (this.securityHeaders) {
                        c += `\n    # Security Headers\n    add_header X-Frame-Options "SAMEORIGIN" always;\n    add_header X-XSS-Protection "1; mode=block" always;\n    add_header X-Content-Type-Options "nosniff" always;\n    add_header Referrer-Policy "no-referrer-when-downgrade" always;\n`;
                    }

                    if (this.enableGzip) {
                        c += `\n    # Gzip Compression\n    gzip on;\n    gzip_types text/plain text/css application/json application/javascript text/xml application/xml image/svg+xml;\n`;
                    }

                    c += `}`;
                    this.output = c;
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
