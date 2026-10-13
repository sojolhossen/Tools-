<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="hashGenerator()">
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
            <!-- Plain Text Input Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext String</label>
                    <button type="button" @click="input = 'password123'; generateHashes(); generateBcrypt();" class="text-xs text-primary-600 hover:underline">Sample</button>
                </div>
                <input type="text" x-model="input" @input="generateHashes()" placeholder="Type a password or text to hash..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all">
            </div>

            <!-- Bcrypt Generator Section -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-rose-200 dark:border-rose-900/40 shadow-sm p-5 sm:p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-700 dark:text-rose-400">Bcrypt Password Hash</span>
                    <div class="flex items-center gap-2">
                        <label class="text-xs text-slate-600 dark:text-slate-400 font-semibold">Rounds:</label>
                        <select x-model.number="rounds" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs px-2 py-1">
                            <option value="10">10 (Standard)</option>
                            <option value="12">12 (Higher Security)</option>
                            <option value="8">8 (Fast)</option>
                        </select>
                        <button type="button" @click="generateBcrypt()" :disabled="loadingBcrypt" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all disabled:opacity-50">
                            <span x-text="loadingBcrypt ? 'Hashing...' : 'Generate Bcrypt'"></span>
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 font-mono text-xs sm:text-sm break-all text-slate-900 dark:text-slate-100">
                    <span class="flex-1" x-text="bcryptHash || 'Click &quot;Generate Bcrypt&quot; to calculate salt & hash'"></span>
                    <button type="button" @click="copy(bcryptHash)" :disabled="!bcryptHash" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Copy</button>
                </div>
            </div>

            <!-- Hashes Grid -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6 space-y-4">
                <span class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Cryptographic Hashes</span>

                <!-- SHA-256 -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        <span>SHA-256</span>
                        <button type="button" @click="copy(sha256)" class="text-primary-600 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 break-all text-slate-800 dark:text-slate-200" x-text="sha256 || '---'"></div>
                </div>

                <!-- SHA-512 -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        <span>SHA-512</span>
                        <button type="button" @click="copy(sha512)" class="text-primary-600 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 break-all text-slate-800 dark:text-slate-200" x-text="sha512 || '---'"></div>
                </div>

                <!-- SHA-1 -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        <span>SHA-1</span>
                        <button type="button" @click="copy(sha1)" class="text-primary-600 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 break-all text-slate-800 dark:text-slate-200" x-text="sha1 || '---'"></div>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="base64-encoder-decoder" />
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bcryptjs@2.4.3/dist/bcrypt.min.js"></script>
    <script>
        function hashGenerator() {
            return {
                input: 'password123',
                rounds: 10,
                bcryptHash: '',
                loadingBcrypt: false,
                sha256: '',
                sha512: '',
                sha1: '',
                init() {
                    this.generateHashes();
                    this.generateBcrypt();
                },
                async generateHashes() {
                    if (!this.input) {
                        this.sha256 = '';
                        this.sha512 = '';
                        this.sha1 = '';
                        return;
                    }
                    const enc = new TextEncoder();
                    const data = enc.encode(this.input);

                    // SHA-256
                    const b256 = await crypto.subtle.digest('SHA-256', data);
                    this.sha256 = Array.from(new Uint8Array(b256)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // SHA-512
                    const b512 = await crypto.subtle.digest('SHA-512', data);
                    this.sha512 = Array.from(new Uint8Array(b512)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // SHA-1
                    const b1 = await crypto.subtle.digest('SHA-1', data);
                    this.sha1 = Array.from(new Uint8Array(b1)).map(b => b.toString(16).padStart(2, '0')).join('');
                },
                generateBcrypt() {
                    if (!this.input) return;
                    this.loadingBcrypt = true;
                    setTimeout(() => {
                        try {
                            if (window.dcodeIO && window.dcodeIO.bcrypt) {
                                const salt = window.dcodeIO.bcrypt.genSaltSync(this.rounds);
                                this.bcryptHash = window.dcodeIO.bcrypt.hashSync(this.input, salt);
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.loadingBcrypt = false;
                        }
                    }, 50);
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
