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
        pxWidth: 1920,
        pxHeight: 1080,
        dpi: 300,
        inWidth: 0,
        inHeight: 0,
        cmWidth: 0,
        cmHeight: 0,
        mmWidth: 0,
        mmHeight: 0,
        calculate() {
            let w = parseFloat(this.pxWidth) || 0;
            let h = parseFloat(this.pxHeight) || 0;
            let d = parseFloat(this.dpi) || 300;

            if (d > 0) {
                this.inWidth = (w / d).toFixed(2);
                this.inHeight = (h / d).toFixed(2);
                this.cmWidth = ((w / d) * 2.54).toFixed(2);
                this.cmHeight = ((h / d) * 2.54).toFixed(2);
                this.mmWidth = Math.round((w / d) * 25.4);
                this.mmHeight = Math.round((h / d) * 25.4);
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Width (Pixels)</label>
                <input type="number" x-model="pxWidth" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Height (Pixels)</label>
                <input type="number" x-model="pxHeight" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-mono">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Resolution (DPI / PPI)</label>
                <select x-model="dpi" @change="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white font-medium">
                    <option value="72">72 DPI (Standard Web / Screen)</option>
                    <option value="96">96 DPI (Windows Default Screen)</option>
                    <option value="150">150 DPI (Medium Quality Print)</option>
                    <option value="300">300 DPI (High Quality Print standard)</option>
                    <option value="600">600 DPI (Ultra Fine Art Print)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-5 rounded-2xl bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-900">
                <span class="text-xs uppercase font-bold text-cyan-700 dark:text-cyan-400">Physical Size in Inches</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-2" x-text="inWidth + ' × ' + inHeight + ' in'"></div>
            </div>
            <div class="p-5 rounded-2xl bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-900">
                <span class="text-xs uppercase font-bold text-cyan-700 dark:text-cyan-400">Physical Size in Centimeters</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-2" x-text="cmWidth + ' × ' + cmHeight + ' cm'"></div>
            </div>
            <div class="p-5 rounded-2xl bg-cyan-50 dark:bg-cyan-950/20 border border-cyan-200 dark:border-cyan-900">
                <span class="text-xs uppercase font-bold text-cyan-700 dark:text-cyan-400">Physical Size in Millimeters</span>
                <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-2" x-text="mmWidth + ' × ' + mmHeight + ' mm'"></div>
            </div>
        </div>
    </div>


        <div class="mt-12">
            <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="length-converter" related-slug2="weight-converter" />
        </div>
    </div>
</x-layouts.app>
