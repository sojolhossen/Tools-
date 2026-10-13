<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="chmodCalculator()">
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

        <!-- Big Results Summary Card -->
        <div class="bg-gradient-to-r from-slate-900 to-indigo-950 rounded-2xl border border-indigo-800/50 p-6 sm:p-8 text-white shadow-xl mb-10">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                <div>
                    <span class="text-xs uppercase tracking-widest text-indigo-300 font-semibold block mb-1">Octal Notation</span>
                    <span class="text-4xl sm:text-5xl font-mono font-black text-white" x-text="octal"></span>
                </div>
                <div>
                    <span class="text-xs uppercase tracking-widest text-indigo-300 font-semibold block mb-1">Symbolic Notation</span>
                    <span class="text-3xl sm:text-4xl font-mono font-bold text-emerald-400" x-text="symbolic"></span>
                </div>
                <div>
                    <span class="text-xs uppercase tracking-widest text-indigo-300 font-semibold block mb-1">CLI Command</span>
                    <div class="inline-flex items-center gap-2 bg-slate-900/80 px-3 py-1.5 rounded-lg border border-slate-700 mt-1">
                        <span class="font-mono text-sm text-cyan-300" x-text="'chmod ' + octal + ' file'"></span>
                        <button type="button" @click="copyCommand()" class="text-slate-400 hover:text-white text-xs">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Checkbox Matrix -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Owner -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-3">
                        <span class="font-bold text-sm text-slate-900 dark:text-white">Owner (User)</span>
                        <span class="font-mono text-xs font-bold text-primary-600 dark:text-primary-400" x-text="ownerScore"></span>
                    </div>
                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Read (r = 4)</span>
                            <input type="checkbox" x-model="ownerR" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Write (w = 2)</span>
                            <input type="checkbox" x-model="ownerW" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Execute (x = 1)</span>
                            <input type="checkbox" x-model="ownerX" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                    </div>
                </div>

                <!-- Group -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-3">
                        <span class="font-bold text-sm text-slate-900 dark:text-white">Group</span>
                        <span class="font-mono text-xs font-bold text-primary-600 dark:text-primary-400" x-text="groupScore"></span>
                    </div>
                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Read (r = 4)</span>
                            <input type="checkbox" x-model="groupR" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Write (w = 2)</span>
                            <input type="checkbox" x-model="groupW" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Execute (x = 1)</span>
                            <input type="checkbox" x-model="groupX" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                    </div>
                </div>

                <!-- Public (Others) -->
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800 mb-3">
                        <span class="font-bold text-sm text-slate-900 dark:text-white">Public (Others)</span>
                        <span class="font-mono text-xs font-bold text-primary-600 dark:text-primary-400" x-text="otherScore"></span>
                    </div>
                    <div class="space-y-2.5">
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Read (r = 4)</span>
                            <input type="checkbox" x-model="otherR" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Write (w = 2)</span>
                            <input type="checkbox" x-model="otherW" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                            <span>Execute (x = 1)</span>
                            <input type="checkbox" x-model="otherX" @change="calculate()" class="rounded text-primary-600 focus:ring-0">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presets -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 mb-10">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-4">Standard File & Directory Presets</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <button type="button" @click="applyPreset(7, 5, 5)" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">755 (Exec / Dir)</div>
                    <div class="text-slate-500 mt-1">rwxr-xr-x</div>
                </button>
                <button type="button" @click="applyPreset(6, 4, 4)" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">644 (Standard File)</div>
                    <div class="text-slate-500 mt-1">rw-r--r--</div>
                </button>
                <button type="button" @click="applyPreset(7, 7, 7)" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">777 (Full Access)</div>
                    <div class="text-slate-500 mt-1">rwxrwxrwx</div>
                </button>
                <button type="button" @click="applyPreset(6, 0, 0)" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">600 (Private Key)</div>
                    <div class="text-slate-500 mt-1">rw-------</div>
                </button>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="cron-generator" />
    </div>

    @push('scripts')
    <script>
        function chmodCalculator() {
            return {
                ownerR: true, ownerW: true, ownerX: true,
                groupR: true, groupW: false, groupX: true,
                otherR: true, otherW: false, otherX: true,
                octal: '755',
                symbolic: '-rwxr-xr-x',
                get ownerScore() { return (this.ownerR ? 4 : 0) + (this.ownerW ? 2 : 0) + (this.ownerX ? 1 : 0); },
                get groupScore() { return (this.groupR ? 4 : 0) + (this.groupW ? 2 : 0) + (this.groupX ? 1 : 0); },
                get otherScore() { return (this.otherR ? 4 : 0) + (this.otherW ? 2 : 0) + (this.otherX ? 1 : 0); },
                init() {
                    this.calculate();
                },
                calculate() {
                    const u = this.ownerScore;
                    const g = this.groupScore;
                    const o = this.otherScore;
                    this.octal = `${u}${g}${o}`;
                    const uS = `${this.ownerR ? 'r' : '-'}${this.ownerW ? 'w' : '-'}${this.ownerX ? 'x' : '-'}`;
                    const gS = `${this.groupR ? 'r' : '-'}${this.groupW ? 'w' : '-'}${this.groupX ? 'x' : '-'}`;
                    const oS = `${this.otherR ? 'r' : '-'}${this.otherW ? 'w' : '-'}${this.otherX ? 'x' : '-'}`;
                    this.symbolic = `-${uS}${gS}${oS}`;
                },
                applyPreset(u, g, o) {
                    this.ownerR = !!(u & 4); this.ownerW = !!(u & 2); this.ownerX = !!(u & 1);
                    this.groupR = !!(g & 4); this.groupW = !!(g & 2); this.groupX = !!(g & 1);
                    this.otherR = !!(o & 4); this.otherW = !!(o & 2); this.otherX = !!(o & 1);
                    this.calculate();
                },
                copyCommand() {
                    navigator.clipboard.writeText('chmod ' + this.octal + ' file');
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
