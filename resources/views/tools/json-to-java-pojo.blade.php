<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jsonToJavaPojo()">
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
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-1.5">Class Name:</label>
                        <input type="text" x-model="className" @input="generate()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100 font-mono">
                    </div>
                    <label class="inline-flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="useLombok" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>Use Lombok (@Data)</span>
                    </label>
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
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Input JSON</label>
                    <textarea x-model="jsonInput" @input="generate()" placeholder='{"id": 1, "product_name": "Keyboard", "price": 49.99}' rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Java POJO Class</label>
                        <button type="button" @click="copyCode()" :disabled="!javaCode" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                        </button>
                    </div>
                    <textarea x-model="javaCode" readonly rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="// Java POJO output will appear here..."></textarea>
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
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="json-formatter" />
    </div>

    @push('scripts')
    <script>
        function jsonToJavaPojo() {
            return {
                className: 'ProductPojo',
                useLombok: false,
                jsonInput: '',
                javaCode: '',
                error: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                generate() {
                    this.error = '';
                    if (!this.jsonInput.trim()) {
                        this.javaCode = '';
                        return;
                    }
                    try {
                        let parsed = JSON.parse(this.jsonInput);
                        if (Array.isArray(parsed)) parsed = parsed[0] || {};
                        const fields = [];
                        const methods = [];

                        for (const [key, val] of Object.entries(parsed)) {
                            let type = 'Object';
                            if (val === null) type = 'String';
                            else if (typeof val === 'string') type = 'String';
                            else if (typeof val === 'number') type = Number.isInteger(val) ? 'Integer' : 'Double';
                            else if (typeof val === 'boolean') type = 'Boolean';
                            else if (Array.isArray(val)) type = 'List<Object>';
                            else if (typeof val === 'object') type = 'Map<String, Object>';

                            const camelKey = key.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
                            const capitalizedKey = camelKey.charAt(0).toUpperCase() + camelKey.slice(1);

                            fields.push(`    @JsonProperty("${key}")\n    private ${type} ${camelKey};`);

                            if (!this.useLombok) {
                                // Getter
                                methods.push(`    public ${type} get${capitalizedKey}() {\n        return this.${camelKey};\n    }`);
                                // Setter
                                methods.push(`    public void set${capitalizedKey}(${type} ${camelKey}) {\n        this.${camelKey} = ${camelKey};\n    }`);
                            }
                        }

                        let code = "import com.fasterxml.jackson.annotation.JsonProperty;\n";
                        if (this.useLombok) {
                            code += "import lombok.Data;\n\n@Data\n";
                        } else {
                            code += "\n";
                        }
                        code += `public class ${this.className || 'Response'} {\n\n`;
                        code += fields.join('\n\n');
                        if (!this.useLombok && methods.length > 0) {
                            code += '\n\n' + methods.join('\n\n');
                        }
                        code += '\n}';
                        this.javaCode = code;
                    } catch (e) {
                        this.error = 'Invalid JSON: ' + e.message;
                    }
                },
                loadSample() {
                    this.jsonInput = JSON.stringify({
                        id: 42,
                        product_name: "Wireless Mechanical Keyboard",
                        price: 89.99,
                        in_stock: true,
                        tags: ["electronics", "gaming"]
                    }, null, 2);
                    this.generate();
                },
                clearAll() {
                    this.jsonInput = '';
                    this.javaCode = '';
                    this.error = '';
                },
                copyCode() {
                    if (!this.javaCode) return;
                    navigator.clipboard.writeText(this.javaCode).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
