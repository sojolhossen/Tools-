<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="aspectRatioCalc()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 dark:bg-violet-950/60 text-violet-700 dark:text-violet-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $tool->getName() }}</h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">{{ $tool->getDescription() }}</p>
        </div>

        <div class="space-y-6 mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Ratio W1</label>
                        <input type="number" x-model.number="w1" @input="calc()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Ratio H1</label>
                        <input type="number" x-model.number="h1" @input="calc()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Target Width W2</label>
                        <input type="number" x-model.number="w2" @input="calcFromW()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Target Height H2</label>
                        <input type="number" x-model.number="h2" @input="calcFromH()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-2">
                    <span class="text-xs font-bold text-slate-400">Presets:</span>
                    <button type="button" @click="setRatio(16, 9)" class="px-3 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">16:9 HD</button>
                    <button type="button" @click="setRatio(4, 3)" class="px-3 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">4:3 SD</button>
                    <button type="button" @click="setRatio(1, 1)" class="px-3 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">1:1 Square</button>
                    <button type="button" @click="setRatio(21, 9)" class="px-3 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">21:9 Ultrawide</button>
                    <button type="button" @click="setRatio(9, 16)" class="px-3 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">9:16 Vertical</button>
                </div>

                <!-- Visual Box -->
                <div class="p-8 bg-slate-50 dark:bg-slate-950 rounded-xl flex items-center justify-center min-h-60 border border-slate-100 dark:border-slate-800">
                    <div :style="'width:' + boxW + 'px; height:' + boxH + 'px;'" class="bg-violet-600/20 border-2 border-violet-600 rounded-lg flex items-center justify-center text-xs font-bold text-violet-700 dark:text-violet-300 transition-all">
                        <span x-text="w2 + ' x ' + h2 + ' px'"></span>
                    </div>
                </div>
            </div>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function aspectRatioCalc() {
    return {
        w1: 16, h1: 9, w2: 1920, h2: 1080, boxW: 240, boxH: 135,
        init() { this.calc(); },
        setRatio(rw, rh) { this.w1 = rw; this.h1 = rh; this.calcFromW(); },
        calcFromW() {
            if (this.w1 && this.h1 && this.w2) {
                this.h2 = Math.round((this.w2 * this.h1) / this.w1);
                this.updateBox();
            }
        },
        calcFromH() {
            if (this.w1 && this.h1 && this.h2) {
                this.w2 = Math.round((this.h2 * this.w1) / this.h1);
                this.updateBox();
            }
        },
        calc() { this.calcFromW(); },
        updateBox() {
            const max = 220;
            const aspect = this.w1 / this.h1;
            if (aspect >= 1) {
                this.boxW = max;
                this.boxH = Math.round(max / aspect);
            } else {
                this.boxH = max;
                this.boxW = Math.round(max * aspect);
            }
        }
    };
}
</script>