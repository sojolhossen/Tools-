<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="curlConverter()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
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
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getShortDescription() }}
            </p>
        </div>

        <div class="space-y-6 mb-10">
            <!-- cURL Input Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">cURL Command</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                            Sample POST
                        </button>
                        <button type="button" @click="curl = ''; convert()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                            Clear
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <textarea x-model="curl" @input="convert()" placeholder="curl -X POST https://api.example.com/v1/users -H 'Content-Type: application/json' -d '{&quot;name&quot;: &quot;John&quot;}'" rows="4" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>
                </div>
            </div>

            <!-- Target Language Tabs & Output -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-3 sm:p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-1.5">
                        <button type="button" @click="targetLang = 'fetch'; convert()" :class="targetLang === 'fetch' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            JS Fetch
                        </button>
                        <button type="button" @click="targetLang = 'axios'; convert()" :class="targetLang === 'axios' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            JS Axios
                        </button>
                        <button type="button" @click="targetLang = 'python'; convert()" :class="targetLang === 'python' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            Python Requests
                        </button>
                        <button type="button" @click="targetLang = 'php'; convert()" :class="targetLang === 'php' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                            PHP cURL
                        </button>
                    </div>
                    <button type="button" @click="copyCode()" :disabled="!convertedCode" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                        <i class="fa-regular fa-copy"></i>
                        <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                    </button>
                </div>
                <div class="p-5">
                    <pre class="font-mono text-xs sm:text-sm text-slate-900 dark:text-slate-100 bg-slate-50 dark:bg-slate-950 p-4 rounded-xl border border-slate-200 dark:border-slate-800 overflow-x-auto whitespace-pre-wrap min-h-[160px]" x-text="convertedCode || '// Converted code will appear here...'"></pre>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="json-formatter" />
    </div>

    @push('scripts')
    <script>
        function curlConverter() {
            return {
                curl: '',
                targetLang: 'fetch',
                convertedCode: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                convert() {
                    if (!this.curl.trim()) {
                        this.convertedCode = '';
                        return;
                    }

                    // Simple cURL parser
                    let method = 'GET';
                    if (/-X\s+([A-Z]+)/i.test(this.curl)) {
                        method = this.curl.match(/-X\s+([A-Z]+)/i)[1].toUpperCase();
                    } else if (/-d|--data/i.test(this.curl)) {
                        method = 'POST';
                    }

                    // Extract URL
                    let url = 'https://api.example.com';
                    const urlMatch = this.curl.match(/curl\s+(?:-[A-Za-z]+\s+(?:'[^\']*'|"[^\"]*"|\S+)\s+)*['"]?(https?:\/\/[^\s\'"]+)['"]?/i);
                    if (urlMatch && urlMatch[1]) {
                        url = urlMatch[1];
                    }

                    // Extract Headers
                    const headers = {};
                    const headerRegex = /-H\s+['"]([^'"]+)['"]/g;
                    let hMatch;
                    while ((hMatch = headerRegex.exec(this.curl)) !== null) {
                        const parts = hMatch[1].split(':');
                        if (parts.length >= 2) {
                            headers[parts[0].trim()] = parts.slice(1).join(':').trim();
                        }
                    }

                    // Extract Data
                    let body = '';
                    const dataMatch = this.curl.match(/(?:-d|--data|--data-raw)\s+['"]([\s\S]*?)['"](?:\s+-[a-zA-Z]|\s*$)/);
                    if (dataMatch && dataMatch[1]) {
                        body = dataMatch[1];
                    }

                    // Generate Code according to targetLang
                    if (this.targetLang === 'fetch') {
                        let opts = `    method: '${method}',\n`;
                        if (Object.keys(headers).length > 0) {
                            opts += `    headers: ${JSON.stringify(headers, null, 8).replace(/\n\s*}/, '\n    }')},\n`;
                        }
                        if (body) {
                            opts += `    body: ${JSON.stringify(body)}\n`;
                        }
                        this.convertedCode = `fetch('${url}', {\n${opts}})\n  .then(response => response.json())\n  .then(data => console.log(data))\n  .catch(error => console.error('Error:', error));`;
                    } else if (this.targetLang === 'axios') {
                        let opts = `  method: '${method.toLowerCase()}',\n  url: '${url}',\n`;
                        if (Object.keys(headers).length > 0) {
                            opts += `  headers: ${JSON.stringify(headers, null, 4).replace(/\n\s*}/, '\n  }')},\n`;
                        }
                        if (body) {
                            opts += `  data: ${JSON.stringify(body)}\n`;
                        }
                        this.convertedCode = `import axios from 'axios';\n\naxios({\n${opts}})\n  .then(response => {\n    console.log(response.data);\n  })\n  .catch(error => {\n    console.error(error);\n  });`;
                    } else if (this.targetLang === 'python') {
                        let py = `import requests\n\nurl = "${url}"\n`;
                        if (Object.keys(headers).length > 0) {
                            py += `headers = ${JSON.stringify(headers, null, 4)}\n`;
                        } else {
                            py += `headers = {}\n`;
                        }
                        if (body) {
                            py += `payload = ${JSON.stringify(body)}\n\nresponse = requests.request("${method}", url, headers=headers, data=payload)\n`;
                        } else {
                            py += `\nresponse = requests.request("${method}", url, headers=headers)\n`;
                        }
                        py += `print(response.text)`;
                        this.convertedCode = py;
                    } else if (this.targetLang === 'php') {
                        let phpHeaders = Object.entries(headers).map(([k, v]) => `    "${k}: ${v}",`).join('\n');
                        let php = `<?php\n\n$curl = curl_init();\n\ncurl_setopt_array($curl, [\n`;
                        php += `    CURLOPT_URL => "${url}",\n`;
                        php += `    CURLOPT_RETURNTRANSFER => true,\n`;
                        php += `    CURLOPT_CUSTOMREQUEST => "${method}",\n`;
                        if (body) {
                            php += `    CURLOPT_POSTFIELDS => '${body.replace(/'/g, "\\'")}',\n`;
                        }
                        if (phpHeaders) {
                            php += `    CURLOPT_HTTPHEADER => [\n${phpHeaders}\n    ],\n`;
                        }
                        php += `]);\n\n$response = curl_exec($curl);\ncurl_close($curl);\necho $response;`;
                        this.convertedCode = php;
                    }
                },
                loadSample() {
                    this.curl = `curl -X POST https://api.example.com/v1/users \\\n  -H "Content-Type: application/json" \\\n  -H "Authorization: Bearer my_secret_token" \\\n  -d '{"name": "Alex", "email": "alex@example.com"}'`;
                    this.convert();
                },
                copyCode() {
                    if (!this.convertedCode) return;
                    navigator.clipboard.writeText(this.convertedCode).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
