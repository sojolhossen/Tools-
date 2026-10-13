<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="cronGenerator()">
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

        <!-- Main Display Box -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl border border-slate-700 p-6 sm:p-8 text-white shadow-xl mb-10 text-center">
            <div class="text-xs uppercase tracking-widest text-slate-400 font-semibold mb-2">Generated Cron Expression</div>
            <div class="text-3xl sm:text-5xl font-mono font-black text-emerald-400 tracking-wider my-3" x-text="expression"></div>
            <div class="text-sm sm:text-base text-slate-300 font-medium max-w-2xl mx-auto" x-text="humanDescription"></div>

            <div class="mt-6 flex justify-center gap-3">
                <button type="button" @click="copy()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs sm:text-sm transition-all flex items-center gap-2 shadow-lg">
                    <i class="fa-regular fa-copy"></i>
                    <span x-text="copied ? 'Copied!' : 'Copy Expression'"></span>
                </button>
            </div>
        </div>

        <!-- Presets -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 mb-8">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-4">Quick Common Presets</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <button type="button" @click="setPreset('* * * * *', 'Every minute')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Every minute</div>
                    <div class="font-mono text-slate-500 mt-1">* * * * *</div>
                </button>
                <button type="button" @click="setPreset('*/5 * * * *', 'Every 5 minutes')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Every 5 minutes</div>
                    <div class="font-mono text-slate-500 mt-1">*/5 * * * *</div>
                </button>
                <button type="button" @click="setPreset('0 * * * *', 'Every hour (at minute 0)')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Every hour</div>
                    <div class="font-mono text-slate-500 mt-1">0 * * * *</div>
                </button>
                <button type="button" @click="setPreset('0 0 * * *', 'Every day at midnight')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Every day at midnight</div>
                    <div class="font-mono text-slate-500 mt-1">0 0 * * *</div>
                </button>
                <button type="button" @click="setPreset('0 9 * * 1-5', 'Every weekday at 09:00 AM')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Weekdays 9 AM</div>
                    <div class="font-mono text-slate-500 mt-1">0 9 * * 1-5</div>
                </button>
                <button type="button" @click="setPreset('0 0 * * 0', 'Every Sunday at midnight')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Every Sunday</div>
                    <div class="font-mono text-slate-500 mt-1">0 0 * * 0</div>
                </button>
                <button type="button" @click="setPreset('0 0 1 * *', 'First day of every month at midnight')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Monthly (1st day)</div>
                    <div class="font-mono text-slate-500 mt-1">0 0 1 * *</div>
                </button>
                <button type="button" @click="setPreset('0 2 1 1 *', 'Every year on Jan 1st at 2 AM')" class="p-3 text-left rounded-xl border border-slate-200 dark:border-slate-800 hover:border-primary-500 text-xs transition-all">
                    <div class="font-bold text-slate-900 dark:text-white">Yearly (Jan 1st)</div>
                    <div class="font-mono text-slate-500 mt-1">0 2 1 1 *</div>
                </button>
            </div>
        </div>

        <!-- Custom Field Builder -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 mb-10">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-4">Custom Field Configuration</h2>
            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Minute (0 - 59)</label>
                    <input type="text" x-model="minute" @input="updateCustom()" class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-center">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Hour (0 - 23)</label>
                    <input type="text" x-model="hour" @input="updateCustom()" class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-center">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Day of Month (1 - 31)</label>
                    <input type="text" x-model="dayMonth" @input="updateCustom()" class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-center">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Month (1 - 12)</label>
                    <input type="text" x-model="month" @input="updateCustom()" class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-center">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">Day of Week (0 - 6)</label>
                    <input type="text" x-model="dayWeek" @input="updateCustom()" class="w-full text-sm font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-2.5 text-center">
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="chmod-calculator" />
    </div>

    @push('scripts')
    <script>
        function cronGenerator() {
            return {
                minute: '0',
                hour: '0',
                dayMonth: '*',
                month: '*',
                dayWeek: '*',
                expression: '0 0 * * *',
                humanDescription: 'At 00:00 every day',
                copied: false,
                updateCustom() {
                    this.expression = `${this.minute.trim() || '*'} ${this.hour.trim() || '*'} ${this.dayMonth.trim() || '*'} ${this.month.trim() || '*'} ${this.dayWeek.trim() || '*'}`;
                    this.humanDescription = `Runs according to: Minute: ${this.minute}, Hour: ${this.hour}, Day: ${this.dayMonth}, Month: ${this.month}, Day of Week: ${this.dayWeek}`;
                },
                setPreset(expr, desc) {
                    this.expression = expr;
                    this.humanDescription = desc;
                    const parts = expr.split(' ');
                    this.minute = parts[0];
                    this.hour = parts[1];
                    this.dayMonth = parts[2];
                    this.month = parts[3];
                    this.dayWeek = parts[4];
                },
                copy() {
                    navigator.clipboard.writeText(this.expression).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
