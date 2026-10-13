<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="pinGeneratorApp()">
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
            <!-- Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">PIN Length</label>
                    <select x-model.number="length" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="4">4 Digits (Debit / ATM standard)</option>
                        <option value="6">6 Digits (iOS / Android Passcode)</option>
                        <option value="8">8 Digits (High Security)</option>
                        <option value="10">10 Digits</option>
                        <option value="12">12 Digits</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Quantity</label>
                    <select x-model.number="count" @change="generate()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-sm font-semibold text-slate-900 dark:text-slate-100 focus:outline-none">
                        <option value="1">1 PIN</option>
                        <option value="5">5 PINs</option>
                        <option value="10">10 PINs</option>
                        <option value="20">20 PINs</option>
                    </select>
                </div>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="noRepeat" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Avoid Repeating Digits (e.g. 77)</span>
                </label>
                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/40 cursor-pointer hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                    <input type="checkbox" x-model="noSequential" @change="generate()" class="w-4 h-4 rounded text-rose-600 focus:ring-rose-500">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Avoid Sequential Digits (e.g. 1234, 4321)</span>
                </label>
            </div>

            <div class="pt-2 flex justify-between items-center">
                <button type="button" @click="generate()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all flex items-center gap-2">
                    <span>Generate New PINs</span>
                </button>
                <button type="button" @click="copyAll()" class="text-xs text-rose-600 dark:text-rose-400 font-bold hover:underline" x-show="pins.length > 1">
                    Copy All PINs
                </button>
            </div>

            <!-- Generated PINs Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-4 border-t border-slate-200 dark:border-slate-800">
                <template x-for="(pin, index) in pins" :key="index">
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                        <span class="font-mono text-xl sm:text-2xl font-extrabold tracking-widest text-slate-900 dark:text-slate-100" x-text="pin"></span>
                        <button type="button" @click="copy(pin, index)" class="text-xs font-semibold px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-all">
                            <span x-text="copiedIndex === index ? 'Copied!' : 'Copy'"></span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function pinGeneratorApp() {
            return {
                length: 4,
                count: 5,
                noRepeat: true,
                noSequential: true,
                pins: [],
                copiedIndex: -1,

                init() {
                    this.generate();
                },

                generate() {
                    this.pins = [];
                    for (let i = 0; i < this.count; i++) {
                        let pin = this.makePin();
                        this.pins.push(pin);
                    }
                },

                makePin() {
                    let attempts = 0;
                    while (attempts < 100) {
                        attempts++;
                        const arr = new Uint8Array(this.length);
                        window.crypto.getRandomValues(arr);
                        let pin = '';
                        let valid = true;

                        for (let j = 0; j < this.length; j++) {
                            const digit = arr[j] % 10;
                            if (this.noRepeat && j > 0 && digit === parseInt(pin[j - 1])) {
                                valid = false;
                                break;
                            }
                            pin += digit;
                        }

                        if (!valid) continue;

                        if (this.noSequential) {
                            let isSeqUp = true;
                            let isSeqDown = true;
                            for (let k = 1; k < this.length; k++) {
                                if (parseInt(pin[k]) !== parseInt(pin[k - 1]) + 1) isSeqUp = false;
                                if (parseInt(pin[k]) !== parseInt(pin[k - 1]) - 1) isSeqDown = false;
                            }
                            if (isSeqUp || isSeqDown) continue;
                        }

                        return pin;
                    }
                    // Fallback
                    const arr = new Uint8Array(this.length);
                    window.crypto.getRandomValues(arr);
                    return Array.from(arr).map(b => b % 10).join('');
                },

                copy(text, index) {
                    navigator.clipboard.writeText(text);
                    this.copiedIndex = index;
                    setTimeout(() => { this.copiedIndex = -1; }, 2000);
                },

                copyAll() {
                    navigator.clipboard.writeText(this.pins.join('\n'));
                    alert('Copied all PINs to clipboard!');
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
