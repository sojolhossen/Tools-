<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="regexTester()">
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
            <!-- Pattern Input Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Regular Expression</label>
                <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2">
                    <span class="text-slate-400 font-mono text-lg font-bold">/</span>
                    <input type="text" x-model="pattern" @input="evaluate()" placeholder="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" class="w-full bg-transparent font-mono text-sm sm:text-base text-slate-900 dark:text-slate-100 focus:outline-none">
                    <span class="text-slate-400 font-mono text-lg font-bold">/</span>
                    <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200 dark:border-slate-800">
                        <label class="inline-flex items-center text-xs font-mono text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="flagG" @change="evaluate()" class="rounded text-primary-600 focus:ring-0 mr-0.5">g
                        </label>
                        <label class="inline-flex items-center text-xs font-mono text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="flagI" @change="evaluate()" class="rounded text-primary-600 focus:ring-0 mr-0.5">i
                        </label>
                        <label class="inline-flex items-center text-xs font-mono text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="flagM" @change="evaluate()" class="rounded text-primary-600 focus:ring-0 mr-0.5">m
                        </label>
                        <label class="inline-flex items-center text-xs font-mono text-slate-600 dark:text-slate-400 cursor-pointer">
                            <input type="checkbox" x-model="flagS" @change="evaluate()" class="rounded text-primary-600 focus:ring-0 mr-0.5">s
                        </label>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    <span class="text-slate-500 py-1">Presets:</span>
                    <button type="button" @click="loadPreset('email')" class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">Email</button>
                    <button type="button" @click="loadPreset('url')" class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">URL</button>
                    <button type="button" @click="loadPreset('phone')" class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">Phone</button>
                    <button type="button" @click="loadPreset('ipv4')" class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">IPv4</button>
                </div>
            </div>

            <!-- Test String & Highlight Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Test String</label>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full" :class="matches.length > 0 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400'" x-text="matches.length + ' matches found'"></span>
                </div>
                <textarea x-model="testString" @input="evaluate()" placeholder="Type text to test against the pattern..." rows="6" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>

                <!-- Match Results Display -->
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Highlighted Matches</span>
                    <div class="p-4 rounded-xl font-mono text-xs sm:text-sm bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 whitespace-pre-wrap break-all min-h-[60px]" x-html="highlightedText || '<span class=\'text-slate-400\'>Matches will highlight here...</span>'"></div>
                </div>
            </div>

            <template x-if="error">
                <div class="p-4 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 text-xs font-mono flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span x-text="error"></span>
                </div>
            </template>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-css-js-beautifier" />
    </div>

    @push('scripts')
    <script>
        function regexTester() {
            return {
                pattern: '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}',
                flagG: true,
                flagI: true,
                flagM: false,
                flagS: false,
                testString: 'Contact us at support@example.com or sales@toolzeno.site for inquiries.',
                matches: [],
                highlightedText: '',
                error: '',
                init() {
                    this.evaluate();
                },
                evaluate() {
                    this.error = '';
                    this.matches = [];
                    this.highlightedText = '';

                    if (!this.pattern || !this.testString) return;

                    let flags = '';
                    if (this.flagG) flags += 'g';
                    if (this.flagI) flags += 'i';
                    if (this.flagM) flags += 'm';
                    if (this.flagS) flags += 's';

                    try {
                        const regex = new RegExp(this.pattern, flags);
                        let m;
                        if (this.flagG) {
                            while ((m = regex.exec(this.testString)) !== null) {
                                if (m.index === regex.lastIndex) regex.lastIndex++;
                                this.matches.push(m[0]);
                            }
                        } else {
                            m = regex.exec(this.testString);
                            if (m) this.matches.push(m[0]);
                        }

                        // Escape test string for HTML safety
                        const escapeHtml = (str) => str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        let escaped = escapeHtml(this.testString);
                        const safeRegex = new RegExp(this.pattern, flags);
                        this.highlightedText = escaped.replace(safeRegex, (match) => `<mark class="bg-amber-300 dark:bg-amber-500/40 text-slate-900 dark:text-white px-1 py-0.5 rounded font-bold">${escapeHtml(match)}</mark>`);
                    } catch (e) {
                        this.error = 'Regex syntax error: ' + e.message;
                    }
                },
                loadPreset(type) {
                    if (type === 'email') {
                        this.pattern = '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}';
                        this.testString = 'Hello john@example.com, alice.smith@work.org and invalid-email@.';
                    } else if (type === 'url') {
                        this.pattern = 'https?:\\/\\/[\\w\\-\\.]+(?::[0-9]+)?(?:\\/[\\w\\/\\._%#\\-\\?=]*)?';
                        this.testString = 'Check https://toolzeno.site/tools and http://localhost:8000 for details.';
                    } else if (type === 'phone') {
                        this.pattern = '\\+?[0-9]{1,3}?[-.\\s]?\\(?[0-9]{2,4}?\\)?[-.\\s]?[0-9]{3,4}[-.\\s]?[0-9]{3,4}';
                        this.testString = 'Call +1 (555) 123-4567 or +880 1712-345678 today.';
                    } else if (type === 'ipv4') {
                        this.pattern = '\\b(?:[0-9]{1,3}\\.){3}[0-9]{1,3}\\b';
                        this.testString = 'Server IP: 192.168.1.1 and public gateway 10.0.0.254.';
                    }
                    this.evaluate();
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
