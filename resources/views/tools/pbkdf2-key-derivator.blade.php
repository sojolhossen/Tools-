<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="pbkdf2App()">
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
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Password / Passphrase</label>
                        <input type="text" x-model="password" @input="derive()" placeholder="Enter password to derive key..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Salt (String or Hex)</label>
                            <button type="button" @click="genSalt()" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Random Salt</button>
                        </div>
                        <input type="text" x-model="salt" @input="derive()" placeholder="Enter salt string..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Hash Algorithm</label>
                        <select x-model="hashAlg" @change="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="SHA-256">SHA-256 (Standard)</option>
                            <option value="SHA-512">SHA-512 (High Security)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Iterations</label>
                        <input type="number" min="1000" max="600000" step="10000" x-model.number="iterations" @input="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Derived Key Size</label>
                        <select x-model.number="keyBits" @change="derive()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="128">128-bit (16 bytes)</option>
                            <option value="256">256-bit (32 bytes - AES-256)</option>
                            <option value="512">512-bit (64 bytes)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">Derived Key (Hexadecimal)</span>
                            <button type="button" @click="copy(keyHex)" :disabled="!keyHex" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Hex</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="keyHex || '---'"></div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Derived Key (Base64)</span>
                            <button type="button" @click="copy(keyB64)" :disabled="!keyB64" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy Base64</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="keyB64 || '---'"></div>
                    </div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function pbkdf2App() {
            return {
                password: 'MasterSecretKey@2026',
                salt: 'salt1234salt5678',
                hashAlg: 'SHA-256',
                iterations: 100000,
                keyBits: 256,
                keyHex: '',
                keyB64: '',

                init() {
                    this.derive();
                },

                genSalt() {
                    const arr = new Uint8Array(16);
                    window.crypto.getRandomValues(arr);
                    this.salt = Array.from(arr).map(b => b.toString(16).padStart(2, '0')).join('');
                    this.derive();
                },

                async derive() {
                    if (!this.password || !this.salt) return;
                    try {
                        const enc = new TextEncoder();
                        const keyMaterial = await window.crypto.subtle.importKey(
                            'raw',
                            enc.encode(this.password),
                            'PBKDF2',
                            false,
                            ['deriveBits']
                        );

                        const derived = await window.crypto.subtle.deriveBits(
                            {
                                name: 'PBKDF2',
                                salt: enc.encode(this.salt),
                                iterations: this.iterations,
                                hash: this.hashAlg
                            },
                            keyMaterial,
                            this.keyBits
                        );

                        const bytes = new Uint8Array(derived);
                        this.keyHex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0')).join('');
                        let bin = '';
                        bytes.forEach(b => bin += String.fromCharCode(b));
                        this.keyB64 = btoa(bin);
                    } catch (e) {
                        console.error(e);
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
