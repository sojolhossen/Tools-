<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="base64Tool()">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
            <div class="p-4 sm:p-5 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button" @click="mode = 'encode'; process()" :class="mode === 'encode' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Encode
                    </button>
                    <button type="button" @click="mode = 'decode'; process()" :class="mode === 'decode' ? 'bg-primary-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">
                        Decode
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors">
                        <i class="fa-solid fa-file-arrow-up mr-1"></i> File to Base64
                        <input type="file" @change="handleFile($event)" class="hidden">
                    </label>
                    <button type="button" @click="clearAll()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                        <i class="fa-solid fa-trash-can mr-1"></i> Clear
                    </button>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">
                        <span x-text="mode === 'encode' ? 'Plain Text / Input' : 'Base64 Encoded String'"></span>
                    </label>
                    <textarea x-model="input" @input="process()" :placeholder="mode === 'encode' ? 'Enter text to encode...' : 'Enter base64 to decode...'" rows="14" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all resize-y"></textarea>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                            <span x-text="mode === 'encode' ? 'Base64 Output' : 'Decoded Plain Text'"></span>
                        </label>
                        <button type="button" @click="copyOutput()" :disabled="!output" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1 disabled:opacity-40">
                            <i class="fa-regular fa-copy"></i>
                            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                    <textarea x-model="output" readonly rows="14" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none transition-all resize-y" placeholder="Output will appear here..."></textarea>
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
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="url-encoder-decoder" />
    </div>

    @push('scripts')
    <script>
        function base64Tool() {
            return {
                mode: 'encode',
                input: '',
                output: '',
                error: '',
                copied: false,
                process() {
                    this.error = '';
                    if (!this.input) {
                        this.output = '';
                        return;
                    }
                    try {
                        if (this.mode === 'encode') {
                            // Support utf8 encoding
                            const bytes = new TextEncoder().encode(this.input);
                            const binString = Array.from(bytes, (byte) => String.fromCharCode(byte)).join("");
                            this.output = btoa(binString);
                        } else {
                            const binString = atob(this.input.trim());
                            const bytes = Uint8Array.from(binString, (m) => m.charCodeAt(0));
                            this.output = new TextDecoder().decode(bytes);
                        }
                    } catch (e) {
                        this.error = 'Invalid Base64 or input string: ' + e.message;
                    }
                },
                handleFile(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = () => {
                        this.mode = 'encode';
                        this.input = `[File: ${file.name} (${file.size} bytes)]`;
                        this.output = reader.result;
                    };
                    reader.readAsDataURL(file);
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
