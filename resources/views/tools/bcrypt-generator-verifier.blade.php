<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="bcryptApp()">
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
            <!-- Tab Navigation -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
                <button type="button" @click="activeTab = 'generate'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="activeTab === 'generate' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Generate Bcrypt Hash</button>
                <button type="button" @click="activeTab = 'verify'" class="pb-3 text-sm font-bold border-b-2 transition-all" :class="activeTab === 'verify' ? 'border-rose-600 text-rose-600 dark:text-rose-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'">Verify Bcrypt Hash</button>
            </div>

            <!-- Tab 1: Generate -->
            <div x-show="activeTab === 'generate'" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Password</label>
                    <input type="text" x-model="genInput" placeholder="Enter password to hash with Bcrypt..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-600 dark:text-slate-400">Salt Rounds (Cost Factor):</label>
                        <select x-model.number="genRounds" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-bold p-2 text-slate-900 dark:text-slate-100">
                            <option value="8">8 (Fast)</option>
                            <option value="10">10 (Standard / Recommended)</option>
                            <option value="12">12 (High Security)</option>
                            <option value="14">14 (Very Slow)</option>
                        </select>
                    </div>

                    <button type="button" @click="generateBcrypt()" :disabled="loading" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all disabled:opacity-50">
                        <span x-text="loading ? 'Generating Salt...' : 'Generate Hash'"></span>
                    </button>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Resulting Bcrypt Hash ($2a$)</span>
                        <button type="button" @click="copy(genHash)" :disabled="!genHash" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="genHash || 'Click &quot;Generate Hash&quot; to calculate...'"></div>
                </div>
            </div>

            <!-- Tab 2: Verify -->
            <div x-show="activeTab === 'verify'" class="space-y-4" style="display:none;">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Plaintext Password</label>
                    <input type="text" x-model="verifyPassword" placeholder="Enter plaintext password..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Existing Bcrypt Hash String</label>
                    <input type="text" x-model="verifyHash" placeholder="Paste $2a$ or $2b$ hash to verify..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="checkMatch()" class="px-5 py-2.5 rounded-xl font-bold text-sm bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-all">
                        Verify Match
                    </button>
                    <template x-if="verifyResult !== null">
                        <div class="text-xs font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5" :class="verifyResult ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400'">
                            <span x-text="verifyResult ? '✓ MATCH: Password is correct!' : '✗ MISMATCH: Password does not match hash.'"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script src="{{ asset('js/vendor/bcrypt.min.js') }}"></script>
    <script>
        if (typeof dcodeIO === 'undefined') {
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/bcryptjs@2.4.3/dist/bcrypt.min.js';
            document.head.appendChild(s);
        }
    </script>
    <script>
        function bcryptApp() {
            return {
                activeTab: 'generate',
                genInput: 'MySecurePassword123!',
                genRounds: 10,
                genHash: '',
                loading: false,

                verifyPassword: 'MySecurePassword123!',
                verifyHash: '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy',
                verifyResult: null,

                init() {
                    this.generateBcrypt();
                },

                generateBcrypt() {
                    if (!this.genInput) return;
                    this.loading = true;
                    setTimeout(() => {
                        try {
                            if (window.dcodeIO && window.dcodeIO.bcrypt) {
                                const salt = window.dcodeIO.bcrypt.genSaltSync(this.genRounds);
                                this.genHash = window.dcodeIO.bcrypt.hashSync(this.genInput, salt);
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loading = false;
                        }
                    }, 50);
                },

                checkMatch() {
                    try {
                        if (window.dcodeIO && window.dcodeIO.bcrypt) {
                            this.verifyResult = window.dcodeIO.bcrypt.compareSync(this.verifyPassword, this.verifyHash);
                        }
                    } catch (e) {
                        this.verifyResult = false;
                    }
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
