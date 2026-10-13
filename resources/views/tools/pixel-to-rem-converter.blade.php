<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-50 dark:bg-cyan-950/60 text-cyan-700 dark:text-cyan-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed">
                {{ $tool->getDescription() }}
            </p>
        </div>

        
    <div x-data="{
        baseSize: 16,
        px: 24,
        rem: 1.5,
        em: 1.5,
        convert(from) {
            let base = parseFloat(this.baseSize) || 16;
            if (from === 'px') {
                let p = parseFloat(this.px);
                this.rem = !isNaN(p) ? (p / base).toFixed(4).replace(/\.?0+$/, '') : '';
                this.em = this.rem;
            } else if (from === 'rem') {
                let r = parseFloat(this.rem);
                this.px = !isNaN(r) ? (r * base).toFixed(2).replace(/\.?0+$/, '') : '';
                this.em = this.rem;
            }
        }
    }" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="mb-6 flex items-center gap-3">
            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Base Font Size (Default 16px):</label>
            <div class="flex items-center gap-1 w-28">
                <input type="number" x-model="baseSize" @input="convert('px')" class="w-full px-3 py-1.5 text-center font-mono rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                <span class="text-xs text-slate-400">px</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Pixels (px)</label>
                <input type="number" step="any" x-model="px" @input="convert('px')" class="w-full px-4 py-3 text-2xl font-mono font-bold rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">REM / EM (rem)</label>
                <input type="number" step="any" x-model="rem" @input="convert('rem')" class="w-full px-4 py-3 text-2xl font-mono font-bold rounded-2xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
            <h4 class="text-xs font-bold uppercase text-slate-400 mb-3">Quick CSS Typography Cheat Sheet (Based on <span x-text="baseSize + 'px'"></span>)</h4>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 text-center text-xs font-mono">
                <template x-for="p in [12, 14, 16, 18, 20, 24, 32, 40, 48, 64]">
                    <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 cursor-pointer hover:border-cyan-500" @click="px = p; convert('px')">
                        <div class="font-bold text-slate-900 dark:text-white" x-text="p + 'px'"></div>
                        <div class="text-cyan-600 dark:text-cyan-400 mt-0.5" x-text="(p / baseSize).toFixed(3).replace(/\.?0+$/, '') + 'rem'"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
