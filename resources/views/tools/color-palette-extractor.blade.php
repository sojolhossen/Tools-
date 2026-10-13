<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="colorPaletteExtractor()">
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
                <input type="file" x-ref="fileInput" @change="handleFile($event)" accept="image/*" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="swatch" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to extract color palette'"></h3>
                <p class="text-xs text-slate-500 mt-1">Extracts dominant colors with HEX, RGB values, and one-click copying.</p>
            </div>

            <template x-if="palette.length > 0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-slate-500">Extracted Dominant Palette</span>
                        <button type="button" @click="copyAllCss()" class="text-xs font-semibold text-violet-600 hover:underline">Copy CSS Variables</button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                        <template x-for="(c, idx) in palette" :key="idx">
                            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm cursor-pointer group" @click="copyColor(c)">
                                <div :style="'background-color:' + c.hex" class="h-20 w-full group-hover:scale-105 transition-transform"></div>
                                <div class="p-2.5 bg-slate-50 dark:bg-slate-950 text-center">
                                    <span class="text-xs font-mono font-bold block" x-text="c.hex"></span>
                                    <span class="text-[10px] text-slate-400 block" x-text="c.rgb"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                        <img :src="originalUrl" class="max-h-64 mx-auto rounded-lg shadow-sm">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function colorPaletteExtractor() {
    return {
        originalFile: null, originalUrl: '', palette: [],
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                this.originalUrl = e.target.result;
                const img = new Image();
                img.onload = () => this.extract(img);
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        },
        extract(img) {
            const c = document.createElement('canvas');
            c.width = 100; c.height = 100;
            const ctx = c.getContext('2d');
            ctx.drawImage(img, 0, 0, 100, 100);
            const data = ctx.getImageData(0, 0, 100, 100).data;
            const colorCounts = {};
            for (let i = 0; i < data.length; i += 16) {
                const r = Math.round(data[i] / 32) * 32;
                const g = Math.round(data[i+1] / 32) * 32;
                const b = Math.round(data[i+2] / 32) * 32;
                const key = `${r},${g},${b}`;
                colorCounts[key] = (colorCounts[key] || 0) + 1;
            }
            const sorted = Object.entries(colorCounts).sort((a,b) => b[1] - a[1]).slice(0, 6);
            this.palette = sorted.map(([k]) => {
                const [r, g, b] = k.split(',').map(Number);
                const hex = '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
                return { hex: hex.toUpperCase(), rgb: `rgb(${r}, ${g}, ${b})` };
            });
        },
        copyColor(c) {
            navigator.clipboard.writeText(c.hex);
            alert(`Copied ${c.hex} to clipboard!`);
        },
        copyAllCss() {
            const css = ':root {\n' + this.palette.map((c, i) => `  --color-${i+1}: ${c.hex};`).join('\n') + '\n}';
            navigator.clipboard.writeText(css);
            alert('CSS Variables copied!');
        }
    };
}
</script>