<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="hexBinaryTool()">
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
            <!-- Text Box -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">ASCII / UTF-8 Plain Text</span>
                    <button type="button" @click="copy(text)" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Copy</button>
                </div>
                <textarea x-model="text" @input="updateFromText()" placeholder="Type plain text here..." rows="3" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>
            </div>

            <!-- Hex Box -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Hexadecimal (Base 16)</span>
                    <button type="button" @click="copy(hex)" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Copy</button>
                </div>
                <textarea x-model="hex" @input="updateFromHex()" placeholder="Hex characters (e.g. 48 65 6c 6c 6f)..." rows="3" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>
            </div>

            <!-- Binary Box -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Binary (Base 2)</span>
                    <button type="button" @click="copy(binary)" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Copy</button>
                </div>
                <textarea x-model="binary" @input="updateFromBinary()" placeholder="Binary octets (e.g. 01001000 01100101)..." rows="3" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="sample()" class="px-3.5 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 transition-colors">
                    Sample: "Hello World!"
                </button>
                <button type="button" @click="clearAll()" class="px-3.5 py-1.5 rounded-lg text-xs font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                    Clear All
                </button>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="base64-encoder-decoder" />
    </div>

    @push('scripts')
    <script>
        function hexBinaryTool() {
            return {
                text: '',
                hex: '',
                binary: '',
                updateFromText() {
                    const enc = new TextEncoder();
                    const bytes = enc.encode(this.text);
                    this.hex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join(' ');
                    this.binary = Array.from(bytes).map(b => b.toString(2).padStart(8, '0')).join(' ');
                },
                updateFromHex() {
                    try {
                        const clean = this.hex.replace(/[^0-9a-fA-F]/g, '');
                        const bytes = [];
                        for (let i = 0; i < clean.length; i += 2) {
                            bytes.push(parseInt(clean.substr(i, 2), 16));
                        }
                        const u8 = new Uint8Array(bytes.filter(b => !isNaN(b)));
                        this.text = new TextDecoder().decode(u8);
                        this.binary = Array.from(u8).map(b => b.toString(2).padStart(8, '0')).join(' ');
                    } catch (e) {}
                },
                updateFromBinary() {
                    try {
                        const clean = this.binary.replace(/[^01]/g, '');
                        const bytes = [];
                        for (let i = 0; i < clean.length; i += 8) {
                            const chunk = clean.substr(i, 8);
                            if (chunk.length === 8) {
                                bytes.push(parseInt(chunk, 2));
                            }
                        }
                        const u8 = new Uint8Array(bytes);
                        this.text = new TextDecoder().decode(u8);
                        this.hex = Array.from(u8).map(b => b.toString(16).padStart(2, '0')).join(' ');
                    } catch (e) {}
                },
                sample() {
                    this.text = "Hello World!";
                    this.updateFromText();
                },
                clearAll() {
                    this.text = '';
                    this.hex = '';
                    this.binary = '';
                },
                copy(val) {
                    if (!val) return;
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
