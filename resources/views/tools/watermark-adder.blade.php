<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="watermarkAdder()">
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
                    <x-icon name="shield-check" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to add watermark'"></h3>
                <p class="text-xs text-slate-500 mt-1">Stamp custom text or copyright markings onto pictures with position control.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Watermark Text</label>
                            <input type="text" x-model="text" @input="render()" placeholder="© Copyright ToolZeno" class="w-full text-xs p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Opacity: <span class="text-violet-600" x-text="opacity + '%'"></span></label>
                            <input type="range" min="10" max="100" x-model.number="opacity" @input="render()" class="w-full accent-violet-600">
                        </div>
                        <div class="text-right">
                            <button type="button" @click="download()" class="px-5 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download
                            </button>
                        </div>
                    </div>
                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                        <img :src="renderedUrl" class="max-h-80 mx-auto rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function watermarkAdder() {
    return {
        originalFile: null, originalImage: null, renderedUrl: '', text: '© Protected by ToolZeno', opacity: 60,
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                const img = new Image();
                img.onload = () => { this.originalImage = img; this.render(); };
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        },
        render() {
            if (!this.originalImage) return;
            const img = this.originalImage;
            const c = document.createElement('canvas');
            c.width = img.width; c.height = img.height;
            const ctx = c.getContext('2d');
            ctx.drawImage(img, 0, 0);

            const fontSize = Math.max(16, Math.round(img.width * 0.04));
            ctx.font = `bold ${fontSize}px sans-serif`;
            ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity / 100})`;
            ctx.shadowColor = 'rgba(0, 0, 0, 0.7)';
            ctx.shadowBlur = 6;
            ctx.textAlign = 'right';
            ctx.fillText(this.text, img.width - 30, img.height - 30);

            c.toBlob((b) => { if (b) this.renderedUrl = URL.createObjectURL(b); }, 'image/jpeg', 0.92);
        },
        download() {
            const a = document.createElement('a'); a.href = this.renderedUrl;
            a.download = 'watermarked.jpg';
            a.click();
        }
    };
}
</script>