<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="svgToPng()">
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
            <div class="bg-white dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 hover:border-violet-500 p-8 text-center cursor-pointer transition-colors"
                 @click="$refs.fileInput.click()" @dragover.prevent="" @drop.prevent="handleDrop($event)">
                <input type="file" x-ref="fileInput" @change="handleFile($event)" accept=".svg,image/svg+xml" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="photo" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop SVG vector file'"></h3>
                <p class="text-xs text-slate-500 mt-1">Rasterize SVG vectors to crisp high-resolution transparent PNG images.</p>
            </div>

            <template x-if="svgDataUrl">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500 uppercase">Export Scale:</span>
                            <template x-for="s in [1, 2, 4, 8]" :key="s">
                                <button type="button" @click="scale = s; render()" :class="scale === s ? 'bg-violet-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300'" class="px-3 py-1.5 text-xs font-semibold rounded-lg" x-text="s + 'x'"></button>
                            </template>
                        </div>
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download PNG (<span x-text="width + 'x' + height"></span>)
                        </button>
                    </div>
                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#374151_1px,transparent_1px)] [background-size:16px_16px] rounded-xl p-4">
                        <img :src="pngUrl" class="max-h-80 mx-auto rounded shadow-sm">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function svgToPng() {
    return {
        originalFile: null, svgDataUrl: '', pngUrl: '', scale: 2, width: 0, height: 0,
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => { this.svgDataUrl = e.target.result; this.render(); };
            r.readAsDataURL(f);
        },
        render() {
            if (!this.svgDataUrl) return;
            const img = new Image();
            img.onload = () => {
                this.width = (img.width || 400) * this.scale;
                this.height = (img.height || 400) * this.scale;
                const c = document.createElement('canvas');
                c.width = this.width; c.height = this.height;
                const ctx = c.getContext('2d');
                ctx.drawImage(img, 0, 0, this.width, this.height);
                c.toBlob((b) => { if (b) this.pngUrl = URL.createObjectURL(b); }, 'image/png');
            };
            img.src = this.svgDataUrl;
        },
        download() {
            const a = document.createElement('a'); a.href = this.pngUrl;
            a.download = (this.originalFile ? this.originalFile.name.replace(/\.[^/.]+$/, "") : 'vector') + '@' + this.scale + 'x.png';
            a.click();
        }
    };
}
</script>