<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="dummyImageGen()">
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
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Width (px)</label>
                        <input type="number" x-model.number="width" @input="render()" class="w-full text-xs p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Height (px)</label>
                        <input type="number" x-model.number="height" @input="render()" class="w-full text-xs p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Background</label>
                        <input type="color" x-model="bgColor" @input="render()" class="w-full h-10 rounded border cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Text Color</label>
                        <input type="color" x-model="textColor" @input="render()" class="w-full h-10 rounded border cursor-pointer">
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-400">Presets:</span>
                        <button type="button" @click="setSize(300, 250)" class="px-2.5 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">300x250</button>
                        <button type="button" @click="setSize(728, 90)" class="px-2.5 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">728x90</button>
                        <button type="button" @click="setSize(800, 600)" class="px-2.5 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">800x600</button>
                        <button type="button" @click="setSize(1200, 630)" class="px-2.5 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800">1200x630</button>
                    </div>
                    <button type="button" @click="download()" class="px-6 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl text-xs">
                        Download Image (<span x-text="width + 'x' + height"></span>)
                    </button>
                </div>

                <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                    <img :src="renderedUrl" class="max-h-80 mx-auto rounded shadow-sm border border-slate-100 dark:border-slate-800">
                </div>
            </div>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function dummyImageGen() {
    return {
        width: 600, height: 400, bgColor: '#4f46e5', textColor: '#ffffff', renderedUrl: '',
        init() { this.render(); },
        setSize(w, h) { this.width = w; this.height = h; this.render(); },
        render() {
            const c = document.createElement('canvas');
            c.width = this.width; c.height = this.height;
            const ctx = c.getContext('2d');
            ctx.fillStyle = this.bgColor;
            ctx.fillRect(0, 0, c.width, c.height);

            const fontSize = Math.max(14, Math.round(Math.min(c.width, c.height) * 0.12));
            ctx.font = `bold ${fontSize}px sans-serif`;
            ctx.fillStyle = this.textColor;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(`${this.width} x ${this.height}`, c.width / 2, c.height / 2);

            c.toBlob((b) => { if (b) this.renderedUrl = URL.createObjectURL(b); }, 'image/png');
        },
        download() {
            const a = document.createElement('a'); a.href = this.renderedUrl;
            a.download = `placeholder_${this.width}x${this.height}.png`;
            a.click();
        }
    };
}
</script>