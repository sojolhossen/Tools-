<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="imageCropper()">
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
                    <x-icon name="viewfinder-circle" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to crop'"></h3>
                <p class="text-xs text-slate-500 mt-1">Crop photos to exact aspect ratios (1:1 Square, 4:3, 16:9 Cinema, 9:16 Story).</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-500 uppercase">Ratio Preset:</span>
                            <button type="button" @click="ratio = '1:1'; crop()" :class="ratio === '1:1' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 text-xs font-semibold rounded-lg">1:1 Square</button>
                            <button type="button" @click="ratio = '16:9'; crop()" :class="ratio === '16:9' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 text-xs font-semibold rounded-lg">16:9 Landscape</button>
                            <button type="button" @click="ratio = '4:3'; crop()" :class="ratio === '4:3' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 text-xs font-semibold rounded-lg">4:3 Photo</button>
                            <button type="button" @click="ratio = '9:16'; crop()" :class="ratio === '9:16' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-700'" class="px-3 py-1.5 text-xs font-semibold rounded-lg">9:16 Story</button>
                        </div>
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download Cropped Image
                        </button>
                    </div>
                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                        <img :src="croppedUrl" class="max-h-80 mx-auto rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function imageCropper() {
    return {
        originalFile: null, originalImage: null, croppedUrl: '', ratio: '1:1',
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                const img = new Image();
                img.onload = () => { this.originalImage = img; this.crop(); };
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        },
        crop() {
            if (!this.originalImage) return;
            const img = this.originalImage;
            let targetAspect = 1;
            if (this.ratio === '16:9') targetAspect = 16 / 9;
            else if (this.ratio === '4:3') targetAspect = 4 / 3;
            else if (this.ratio === '9:16') targetAspect = 9 / 16;

            let cropW = img.width, cropH = img.height;
            const currentAspect = img.width / img.height;

            if (currentAspect > targetAspect) {
                cropW = Math.round(img.height * targetAspect);
            } else {
                cropH = Math.round(img.width / targetAspect);
            }

            const startX = Math.round((img.width - cropW) / 2);
            const startY = Math.round((img.height - cropH) / 2);

            const c = document.createElement('canvas');
            c.width = cropW; c.height = cropH;
            c.getContext('2d').drawImage(img, startX, startY, cropW, cropH, 0, 0, cropW, cropH);
            c.toBlob((b) => { if (b) this.croppedUrl = URL.createObjectURL(b); }, 'image/png');
        },
        download() {
            const a = document.createElement('a'); a.href = this.croppedUrl;
            a.download = 'cropped_' + this.ratio.replace(':', '_') + '.png';
            a.click();
        }
    };
}
</script>