<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="fileChecksumApp()">
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
            <!-- Drag & Drop Area -->
            <div class="border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-rose-500 dark:hover:border-rose-400 rounded-2xl p-8 text-center bg-slate-50/50 dark:bg-slate-950/50 cursor-pointer transition-all" @dragover.prevent @drop.prevent="handleDrop($event)" @click="$refs.fileInput.click()">
                <input type="file" x-ref="fileInput" @change="handleFile($event)" class="hidden">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <svg class="w-12 h-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Click to select a file or drag & drop here</span>
                    <span class="text-xs text-slate-500">100% processed locally in your browser memory — 0 bytes uploaded to any server</span>
                </div>
            </div>

            <!-- Active File Info -->
            <template x-if="fileName">
                <div class="p-4 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-sm text-slate-900 dark:text-white block" x-text="fileName"></span>
                        <span class="text-xs text-slate-500 font-mono" x-text="fileSize"></span>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400" x-text="statusText"></span>
                </div>
            </template>

            <!-- Hashes Grid -->
            <div class="space-y-3 pt-2">
                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">SHA-256 Checksum</span>
                        <button type="button" @click="copy(sha256)" :disabled="!sha256" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="sha256 || 'Waiting for file...'"></div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">MD5 Checksum</span>
                        <button type="button" @click="copy(md5)" :disabled="!md5" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="md5 || 'Waiting for file...'"></div>
                </div>

                <div>
                    <div class="flex justify-between text-xs font-bold mb-1">
                        <span class="text-slate-700 dark:text-slate-300">SHA-1 Checksum</span>
                        <button type="button" @click="copy(sha1)" :disabled="!sha1" class="text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                    </div>
                    <div class="font-mono text-xs bg-slate-50 dark:bg-slate-950 p-3 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all text-slate-900 dark:text-slate-100" x-text="sha1 || 'Waiting for file...'"></div>
                </div>
            </div>

            <!-- Checksum Comparator -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-800">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-2">Verify Against Expected Checksum</label>
                <input type="text" x-model="expected" @input="verifyMatch()" placeholder="Paste expected checksum here to compare..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none">
                <template x-if="matchResult !== null">
                    <div class="mt-2 text-xs font-bold flex items-center gap-1.5" :class="matchResult ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                        <span x-text="matchResult ? '✓ MATCH CONFIRMED: File is authentic!' : '✗ MISMATCH: File checksum does NOT match expected hash.'"></span>
                    </div>
                </template>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        function fileChecksumApp() {
            return {
                fileName: '',
                fileSize: '',
                statusText: 'Idle',
                sha256: '',
                md5: '',
                sha1: '',
                expected: '',
                matchResult: null,

                handleDrop(e) {
                    if (e.dataTransfer.files.length) {
                        this.processFile(e.dataTransfer.files[0]);
                    }
                },

                handleFile(e) {
                    if (e.target.files.length) {
                        this.processFile(e.target.files[0]);
                    }
                },

                async processFile(file) {
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    this.statusText = 'Calculating hashes...';

                    const buffer = await file.arrayBuffer();

                    // SHA-256
                    const d256 = await window.crypto.subtle.digest('SHA-256', buffer);
                    this.sha256 = Array.from(new Uint8Array(d256)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // SHA-1
                    const d1 = await window.crypto.subtle.digest('SHA-1', buffer);
                    this.sha1 = Array.from(new Uint8Array(d1)).map(b => b.toString(16).padStart(2, '0')).join('');

                    // MD5 via CryptoJS wordArray
                    if (window.CryptoJS) {
                        const wordArray = CryptoJS.lib.WordArray.create(buffer);
                        this.md5 = CryptoJS.MD5(wordArray).toString();
                    }

                    this.statusText = 'Completed';
                    this.verifyMatch();
                },

                verifyMatch() {
                    if (!this.expected.trim()) {
                        this.matchResult = null;
                        return;
                    }
                    const exp = this.expected.trim().toLowerCase();
                    this.matchResult = (exp === this.sha256 || exp === this.md5 || exp === this.sha1);
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
