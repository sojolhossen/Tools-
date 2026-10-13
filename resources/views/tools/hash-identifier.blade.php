<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="hashIdentifierApp()">
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
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Raw Hash String</label>
                    <button type="button" @click="input = '$2a$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy'; analyze();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (Bcrypt)</button>
                </div>
                <input type="text" x-model="input" @input="analyze()" placeholder="Paste unknown hash string here (e.g. 5d41402abc4b2a76b9719d911017c592)..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-rose-500 focus:outline-none">
            </div>

            <!-- Stats Bar -->
            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 dark:text-slate-400">
                <span>Length: <strong class="text-slate-900 dark:text-slate-100" x-text="input.trim().length"></strong> characters</span>
                <span>Hex: <strong class="text-slate-900 dark:text-slate-100" x-text="isHex ? 'Yes' : 'No'"></strong></span>
                <span>Base64: <strong class="text-slate-900 dark:text-slate-100" x-text="isBase64 ? 'Yes' : 'No'"></strong></span>
            </div>

            <!-- Identified Candidates -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-3">Identified Hash Types</span>
                <template x-if="matches.length === 0">
                    <div class="text-xs text-slate-400 py-4 text-center">Paste a hash string above to detect matching algorithms.</div>
                </template>
                <div class="space-y-2.5">
                    <template x-for="(m, i) in matches" :key="i">
                        <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex items-center justify-between">
                            <div>
                                <span class="font-bold text-sm text-slate-900 dark:text-white" x-text="m.name"></span>
                                <span class="text-xs text-slate-500 block" x-text="m.desc"></span>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full" :class="m.badgeClass" x-text="m.confidence"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function hashIdentifierApp() {
            return {
                input: '5d41402abc4b2a76b9719d911017c592',
                isHex: false,
                isBase64: false,
                matches: [],

                init() {
                    this.analyze();
                },

                analyze() {
                    const h = this.input.trim();
                    this.matches = [];
                    if (!h) return;

                    this.isHex = /^[0-9a-fA-F]+$/.test(h);
                    this.isBase64 = /^[0-9a-zA-Z+/=_-]+$/.test(h);

                    // Bcrypt
                    if (/^\$2[aby]\$\d{2}\$[./A-Za-z0-9]{53}$/.test(h)) {
                        this.matches.push({ name: 'Bcrypt Password Hash', desc: 'Modular crypt format, 60 characters with salt and cost factor.', confidence: 'Exact Match (100%)', badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' });
                    }

                    // Argon2
                    if (/^\$argon2(id|i|d)\$v=\d+\$m=\d+/.test(h)) {
                        this.matches.push({ name: 'Argon2 Password Hash', desc: 'Standard PHC string with memory, time cost, and salt parameters.', confidence: 'Exact Match (100%)', badgeClass: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' });
                    }

                    // MD5 / NTLM (32 hex)
                    if (h.length === 32 && this.isHex) {
                        this.matches.push({ name: 'MD5 (Message Digest 5)', desc: '128-bit checksum. Most common 32-char hex hash.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'NTLM / MD4', desc: 'Windows NT password hash format.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-1 (40 hex)
                    if (h.length === 40 && this.isHex) {
                        this.matches.push({ name: 'SHA-1 (Secure Hash Algorithm 1)', desc: '160-bit hash. Used in Git commits and legacy SSL.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'RIPEMD-160', desc: '160-bit European cryptographic hash function.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-256 (64 hex)
                    if (h.length === 64 && this.isHex) {
                        this.matches.push({ name: 'SHA-256 (SHA-2 Family)', desc: '256-bit cryptographic digest. Bitcoin and standard TLS hash.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'SHA3-256 / Keccak-256', desc: 'Ethereum smart contract hash.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // SHA-512 (128 hex)
                    if (h.length === 128 && this.isHex) {
                        this.matches.push({ name: 'SHA-512 (SHA-2 Family)', desc: '512-bit high-security digest for 64-bit systems.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                        this.matches.push({ name: 'Whirlpool', desc: '512-bit hash based on AES design.', confidence: 'Possible', badgeClass: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' });
                    }

                    // CRC32 (8 hex)
                    if (h.length === 8 && this.isHex) {
                        this.matches.push({ name: 'CRC32 Checksum', desc: '32-bit cyclic redundancy check used in ZIP archives.', confidence: 'High Probability', badgeClass: 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400' });
                    }
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
