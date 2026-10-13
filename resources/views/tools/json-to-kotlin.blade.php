<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="jsonToKotlin()">
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
                        <input type="checkbox" x-model="useSerialization" @change="generate()" class="rounded text-primary-600 focus:ring-0">
                        <span>@SerialName / kotlinx</span>
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
                    <textarea x-model="jsonInput" @input="generate()" placeholder='{"id": 1, "username": "alex", "is_active": true}' rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Kotlin Data Class</label>
                        <button type="button" @click="copyCode()" :disabled="!kotlinCode" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy Code'"></span>
                        </button>
                    </div>
                    <textarea x-model="kotlinCode" readonly rows="16" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="// Kotlin data class output will appear here..."></textarea>
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
        function jsonToKotlin() {
            return {
                className: 'UserResponse',
                useSerialization: true,
                jsonInput: '',
                kotlinCode: '',
                error: '',
                copied: false,
                init() {
                    this.loadSample();
                },
                generate() {
                    this.error = '';
                    if (!this.jsonInput.trim()) {
                        this.kotlinCode = '';
                        return;
                    }
                    try {
                        let parsed = JSON.parse(this.jsonInput);
                        if (Array.isArray(parsed)) {
                            parsed = parsed[0] || {};
                        }
                        const props = [];
                        for (const [key, val] of Object.entries(parsed)) {
                            let type = 'Any?';
                            if (val === null) type = 'String?';
                            else if (typeof val === 'string') type = 'String';
                            else if (typeof val === 'number') type = Number.isInteger(val) ? 'Int' : 'Double';
                            else if (typeof val === 'boolean') type = 'Boolean';
                            else if (Array.isArray(val)) type = 'List<Any>';
                            else if (typeof val === 'object') type = 'Map<String, Any>';

                            const camelKey = key.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
                            let propLine = '';
                            if (this.useSerialization) {
                                propLine += `    @SerialName("${key}")\n`;
                            }
                            propLine += `    val ${camelKey}: ${type}`;
                            props.push(propLine);
                        }

                        let header = this.useSerialization ? "import kotlinx.serialization.SerialName\nimport kotlinx.serialization.Serializable\n\n@Serializable\n" : "";
                        this.kotlinCode = `${header}data class ${this.className || 'Model'}(\n${props.join(',\n')}\n)`;
                    } catch (e) {
                        this.error = 'Invalid JSON: ' + e.message;
                    }
                },
                loadSample() {
                    this.jsonInput = JSON.stringify({
                        id: 101,
                        username: "john_doe",
                        email: "john@example.com",
                        is_active: true,
                        score: 98.5
                    }, null, 2);
                    this.generate();
                },
                clearAll() {
                    this.jsonInput = '';
                    this.kotlinCode = '';
                    this.error = '';
                },
                copyCode() {
                    if (!this.kotlinCode) return;
                    navigator.clipboard.writeText(this.kotlinCode).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
