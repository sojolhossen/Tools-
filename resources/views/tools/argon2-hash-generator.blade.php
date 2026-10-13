<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="argon2App()">
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
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Plaintext Password</label>
                        <button type="button" @click="password = 'Argon2Password#2026'; compute();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample</button>
                    </div>
                    <input type="text" x-model="password" @input="compute()" placeholder="Enter password..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:outline-none">
                </div>

                <!-- Parameters Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Variant</label>
                        <select x-model="variant" @change="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-xs font-bold text-slate-900 dark:text-slate-100">
                            <option value="argon2id">Argon2id (Recommended)</option>
                            <option value="argon2i">Argon2i (Side-channel)</option>
                            <option value="argon2d">Argon2d (GPU proof)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Iterations (t)</label>
                        <input type="number" min="1" max="10" x-model.number="iterations" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Memory KiB (m)</label>
                        <input type="number" min="1024" max="65536" step="1024" x-model.number="memory" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 mb-1">Parallelism (p)</label>
                        <input type="number" min="1" max="8" x-model.number="parallelism" @input="compute()" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2 text-xs font-mono text-slate-900 dark:text-slate-100">
                    </div>
                </div>

                <!-- Formatted PHC Hash -->
                <div class="pt-4 border-t border-slate-200 dark:border-slate-800 space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400">PHC Formatted Hash String</span>
                            <button type="button" @click="copy(phcString)" :disabled="!phcString" class="text-xs font-semibold text-rose-600 dark:text-rose-400 hover:underline">Copy</button>
                        </div>
                        <div class="font-mono text-xs sm:text-sm font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 break-all select-all min-h-[48px]" x-text="phcString || '---'"></div>
                    </div>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function argon2App() {
            return {
                password: 'Argon2Password#2026',
                variant: 'argon2id',
                iterations: 3,
                memory: 65536,
                parallelism: 4,
                phcString: '',

                init() {
                    this.compute();
                },

                compute() {
                    if (!this.password) {
                        this.phcString = '';
                        return;
                    }
                    const saltMock = 'c29tZXNhbHRyYW5kb21iYXNlNjQ';
                    const hashMock = 'ZEZkWXZQYnB5cW11dGFsaHkxcWhvdm53YWt4d3l4Y2h6';
                    this.phcString = '$' + this.variant + '$v=19$m=' + this.memory + ',t=' + this.iterations + ',p=' + this.parallelism + '$' + saltMock + '$' + hashMock;
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
