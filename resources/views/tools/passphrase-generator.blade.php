<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="passphraseGeneratorApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 mb-10">
            <!-- Output Display -->
            <div class="relative flex items-center bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5">
                <div class="flex-1 font-mono text-lg sm:text-2xl font-bold tracking-wide text-slate-900 dark:text-slate-100 break-all select-all pr-24" x-text="passphrase"></div>
                <div class="absolute right-4 flex items-center gap-2">
                    <button type="button" @click="generate()" title="Regenerate" class="p-2.5 rounded-xl bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button type="button" @click="copy()" class="px-4 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-1.5">
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </button>
                </div>
            </div>

            <!-- Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-800">
                <!-- Words Count -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Number of Words (<span x-text="wordCount"></span>)</label>
                    <input type="range" min="3" max="8" x-model.number="wordCount" @input="generate()" class="w-full accent-rose-600 cursor-pointer">
                    <div class="flex justify-between text-xs text-slate-400 mt-1">
                        <span>3 words</span>
                        <span>4 (Standard)</span>
                        <span>6</span>
                        <span>8 (Paranoid)</span>
                    </div>
                </div>

                <!-- Separator -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Word Separator</label>
                    <select x-model="separator" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="-">Hyphen (-)</option>
                        <option value="_">Underscore (_)</option>
                        <option value=".">Period (.)</option>
                        <option value=" ">Space ( )</option>
                        <option value="/">Slash (/)</option>
                    </select>
                </div>
            </div>

            <!-- Toggles -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="capitalize" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Capitalize Words</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="appendNumber" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Append Random Number</span>
                </label>

                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="appendSymbol" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Append Symbol</span>
                </label>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function passphraseGeneratorApp() {
            return {
                passphrase: '',
                wordCount: 4,
                separator: '-',
                capitalize: false,
                appendNumber: true,
                appendSymbol: false,
                copied: false,
                wordlist: [
                    'apple', 'beach', 'cabin', 'delta', 'eagle', 'flame', 'giant', 'haven', 'ivory', 'jolly',
                    'karma', 'lemon', 'magic', 'noble', 'ocean', 'pilot', 'quest', 'river', 'solar', 'tiger',
                    'ultra', 'vivid', 'whale', 'yield', 'zebra', 'amber', 'breeze', 'castle', 'dragon', 'ember',
                    'falcon', 'galaxy', 'harbor', 'island', 'jungle', 'knight', 'legend', 'meadow', 'nebula', 'orbit',
                    'phoenix', 'quartz', 'rebel', 'shadow', 'thunder', 'unique', 'velvet', 'wizard', 'zenith', 'anchor',
                    'beacon', 'canyon', 'dawn', 'echo', 'forest', 'glacier', 'horizon', 'infinity', 'jupiter', 'kinetic',
                    'lunar', 'monarch', 'nature', 'oasis', 'prism', 'quasar', 'radiant', 'safari', 'titan', 'voyage',
                    'willow', 'apex', 'blaze', 'crater', 'dynasty', 'element', 'frontier', 'gravity', 'hybrid', 'impact',
                    'legacy', 'matrix', 'nova', 'odyssey', 'pioneer', 'quantum', 'rocket', 'summit', 'triumph', 'vertex'
                ],

                init() {
                    this.generate();
                },

                generate() {
                    const values = new Uint32Array(this.wordCount);
                    window.crypto.getRandomValues(values);

                    let words = [];
                    for (let i = 0; i < this.wordCount; i++) {
                        let w = this.wordlist[values[i] % this.wordlist.length];
                        if (this.capitalize) {
                            w = w.charAt(0).toUpperCase() + w.slice(1);
                        }
                        words.push(w);
                    }

                    let res = words.join(this.separator);

                    if (this.appendNumber) {
                        const numVal = new Uint32Array(1);
                        window.crypto.getRandomValues(numVal);
                        res += this.separator + ((numVal[0] % 90) + 10);
                    }

                    if (this.appendSymbol) {
                        const symbols = ['!', '@', '#', '$', '%', '&', '*'];
                        const symVal = new Uint32Array(1);
                        window.crypto.getRandomValues(symVal);
                        res += symbols[symVal[0] % symbols.length];
                    }

                    this.passphrase = res;
                },

                copy() {
                    if (!this.passphrase) return;
                    navigator.clipboard.writeText(this.passphrase);
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2000);
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
