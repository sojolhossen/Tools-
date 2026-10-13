<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jsonToDart()">
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
                        <label class="font-bold text-slate-700 dark:text-slate-300 mr-1.5">Model Name:</label>
                        <input type="text" x-model="modelName" @input="generate()" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100 font-mono">
                    </div>
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
                    <textarea x-model="jsonInput" @input="generate()" placeholder='{"id": 1, "name": "Flutter app"}' rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Dart Model Class</label>
                        <button type="button" @click="copyCode()" :disabled="!dartCode" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                        </button>
                    </div>
                    <textarea x-model="dartCode" readonly rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="// Dart class output will appear here..."></textarea>
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
        function jsonToDart() {
            return {
                modelName: 'UserModel',
                jsonInput: '',
                dartCode: '',
                error: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                generate() {
                    this.error = '';
                    if (!this.jsonInput.trim()) {
                        this.dartCode = '';
                        return;
                    }
                    try {
                        let parsed = JSON.parse(this.jsonInput);
                        if (Array.isArray(parsed)) parsed = parsed[0] || {};

                        const fields = [];
                        const constructorParams = [];
                        const fromJsonAssignments = [];
                        const toJsonPairs = [];

                        for (const [key, val] of Object.entries(parsed)) {
                            let type = 'dynamic';
                            if (val === null) type = 'String?';
                            else if (typeof val === 'string') type = 'String';
                            else if (typeof val === 'number') type = Number.isInteger(val) ? 'int' : 'double';
                            else if (typeof val === 'boolean') type = 'bool';
                            else if (Array.isArray(val)) type = 'List<dynamic>';
                            else if (typeof val === 'object') type = 'Map<String, dynamic>';

                            const camelKey = key.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
                            fields.push(`  final ${type} ${camelKey};`);
                            constructorParams.push(`    required this.${camelKey},`);
                            fromJsonAssignments.push(`      ${camelKey}: json['${key}'],`);
                            toJsonPairs.push(`      '${key}': ${camelKey},`);
                        }

                        const name = this.modelName || 'Model';
                        let code = `class ${name} {\n`;
                        code += fields.join('\n') + '\n\n';
                        code += `  ${name}({\n${constructorParams.join('\n')}\n  });\n\n`;
                        code += `  factory ${name}.fromJson(Map<String, dynamic> json) {\n    return ${name}(\n${fromJsonAssignments.join('\n')}\n    );\n  }\n\n`;
                        code += `  Map<String, dynamic> toJson() {\n    return {\n${toJsonPairs.join('\n')}\n    };\n  }\n`;
                        code += `}`;
                        this.dartCode = code;
                    } catch (e) {
                        this.error = 'Invalid JSON: ' + e.message;
                    }
                },
                loadSample() {
                    this.jsonInput = JSON.stringify({
                        id: 1,
                        full_name: "Sarah Connor",
                        is_verified: true,
                        follower_count: 1420
                    }, null, 2);
                    this.generate();
                },
                clearAll() {
                    this.jsonInput = '';
                    this.dartCode = '';
                    this.error = '';
                },
                copyCode() {
                    if (!this.dartCode) return;
                    navigator.clipboard.writeText(this.dartCode).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
