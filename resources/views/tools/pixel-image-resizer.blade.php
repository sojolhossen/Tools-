<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="pixelResizer()">
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
                    <x-icon name="arrows-pointing-out" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to resize to exact pixels'"></h3>
                <p class="text-xs text-slate-500 mt-1">Set target pixel width and height with aspect ratio lock and high quality resampling.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Target Width (px)</label>
                            <input type="number" x-model.number="targetWidth" @input="onWidthChange()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Target Height (px)</label>
                            <input type="number" x-model.number="targetHeight" @input="onHeightChange()" class="w-full text-sm font-mono p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                        </div>
                        <div class="flex items-center justify-between md:justify-end gap-4 pt-4 md:pt-0">
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-600 cursor-pointer">
                                <input type="checkbox" x-model="lockAspect" class="rounded text-violet-600"> Lock Aspect
                            </label>
                            <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download
                            </button>
                        </div>
                    </div>
                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                        <img :src="resizedUrl" class="max-h-80 mx-auto rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function pixelResizer() {
    return {
        originalFile: null, originalImage: null, resizedUrl: '', targetWidth: 800, targetHeight: 600, lockAspect: true, aspectRatio: 1,
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    this.originalImage = img;
                    this.targetWidth = img.width;
                    this.targetHeight = img.height;
                    this.aspectRatio = img.width / img.height;
                    this.resize();
                };
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        },
        onWidthChange() {
            if (this.lockAspect && this.aspectRatio) this.targetHeight = Math.round(this.targetWidth / this.aspectRatio);
            this.resize();
        },
        onHeightChange() {
            if (this.lockAspect && this.aspectRatio) this.targetWidth = Math.round(this.targetHeight * this.aspectRatio);
            this.resize();
        },
        resize() {
            if (!this.originalImage || !this.targetWidth || !this.targetHeight) return;
            const c = document.createElement('canvas');
            c.width = this.targetWidth; c.height = this.targetHeight;
            c.getContext('2d').drawImage(this.originalImage, 0, 0, this.targetWidth, this.targetHeight);
            c.toBlob((b) => { if (b) this.resizedUrl = URL.createObjectURL(b); }, 'image/png');
        },
        download() {
            const a = document.createElement('a'); a.href = this.resizedUrl;
            a.download = 'resized_' + this.targetWidth + 'x' + this.targetHeight + '.png';
            a.click();
        }
    };
}
</script>