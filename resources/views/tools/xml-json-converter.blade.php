<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="xmlJsonConverter()">
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
                <div class="flex items-center gap-2">
                    <button type="button" @click="direction = 'xml-to-json'; convert()" :class="direction === 'xml-to-json' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        XML &rarr; JSON
                    </button>
                    <button type="button" @click="direction = 'json-to-xml'; convert()" :class="direction === 'json-to-xml' ? 'bg-primary-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all">
                        JSON &rarr; XML
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="loadSample()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        Sample
                    </button>
                    <button type="button" @click="clearAll()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        Clear
                    </button>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                        <span x-text="direction === 'xml-to-json' ? 'Input XML' : 'Input JSON'"></span>
                    </label>
                    <textarea x-model="input" @input="convert()" placeholder="Paste XML or JSON content here..." rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            <span x-text="direction === 'xml-to-json' ? 'Converted JSON' : 'Converted XML'"></span>
                        </label>
                        <button type="button" @click="copyOutput()" :disabled="!output" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <textarea x-model="output" readonly rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="Converted result will appear here..."></textarea>
                </div>
            </div>

            <template x-if="error">
                <div class="px-6 py-3 border-t border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 text-xs font-mono flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span x-text="error"></span>
                </div>
            </template>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="xml-formatter" />
    </div>

    @push('scripts')
    <script>
        function xmlJsonConverter() {
            return {
                direction: 'xml-to-json',
                input: '',
                output: '',
                error: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                convert() {
                    this.error = '';
                    if (!this.input.trim()) {
                        this.output = '';
                        return;
                    }
                    try {
                        if (this.direction === 'xml-to-json') {
                            const parser = new DOMParser();
                            const xmlDoc = parser.parseFromString(this.input, "text/xml");
                            const parseError = xmlDoc.getElementsByTagName("parsererror");
                            if (parseError.length > 0) {
                                throw new Error(parseError[0].textContent);
                            }
                            const obj = this.xmlNodeToObject(xmlDoc.documentElement);
                            const root = {};
                            root[xmlDoc.documentElement.nodeName] = obj;
                            this.output = JSON.stringify(root, null, 2);
                        } else {
                            const parsed = JSON.parse(this.input);
                            const xmlStr = '<?xml version="1.0" encoding="UTF-8"?>\n' + this.jsonToXml(parsed, 0);
                            this.output = xmlStr;
                        }
                    } catch (e) {
                        this.error = e.message;
                    }
                },
                xmlNodeToObject(node) {
                    if (node.nodeType === 3) return node.nodeValue.trim();
                    const obj = {};
                    // Attributes
                    if (node.attributes && node.attributes.length > 0) {
                        for (let j = 0; j < node.attributes.length; j++) {
                            const attr = node.attributes.item(j);
                            obj['@' + attr.nodeName] = attr.nodeValue;
                        }
                    }
                    // Children
                    if (node.hasChildNodes()) {
                        let textOnly = true;
                        for (let i = 0; i < node.childNodes.length; i++) {
                            const child = node.childNodes.item(i);
                            if (child.nodeType === 1) {
                                textOnly = false;
                                const childName = child.nodeName;
                                const childObj = this.xmlNodeToObject(child);
                                if (obj[childName] === undefined) {
                                    obj[childName] = childObj;
                                } else {
                                    if (!Array.isArray(obj[childName])) obj[childName] = [obj[childName]];
                                    obj[childName].push(childObj);
                                }
                            }
                        }
                        if (textOnly && node.textContent.trim()) {
                            if (Object.keys(obj).length === 0) return node.textContent.trim();
                            obj['#text'] = node.textContent.trim();
                        }
                    }
                    return obj;
                },
                jsonToXml(json, indentLevel) {
                    const indent = '  '.repeat(indentLevel);
                    let xml = '';
                    for (const [key, val] of Object.entries(json)) {
                        if (Array.isArray(val)) {
                            for (const item of val) {
                                xml += `${indent}<${key}>\n${typeof item === 'object' ? this.jsonToXml(item, indentLevel + 1) : indent + '  ' + item + '\n'}${indent}</${key}>\n`;
                            }
                        } else if (typeof val === 'object' && val !== null) {
                            xml += `${indent}<${key}>\n${this.jsonToXml(val, indentLevel + 1)}${indent}</${key}>\n`;
                        } else {
                            xml += `${indent}<${key}>${val}</${key}>\n`;
                        }
                    }
                    return xml;
                },
                loadSample() {
                    if (this.direction === 'xml-to-json') {
                        this.input = `<note id="501">\n  <to>Tove</to>\n  <from>Jani</from>\n  <heading>Reminder</heading>\n  <body>Don't forget me this weekend!</body>\n</note>`;
                    } else {
                        this.input = JSON.stringify({
                            note: {
                                to: "Tove",
                                from: "Jani",
                                heading: "Reminder",
                                body: "Don't forget me this weekend!"
                            }
                        }, null, 2);
                    }
                    this.convert();
                },
                clearAll() {
                    this.input = '';
                    this.output = '';
                    this.error = '';
                },
                copyOutput() {
                    if (!this.output) return;
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
