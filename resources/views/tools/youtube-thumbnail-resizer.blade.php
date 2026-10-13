<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ytThumbnailResizer()">
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
                    <x-icon name="video-camera" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to scale to 1280x720'"></h3>
                <p class="text-xs text-slate-500 mt-1">Scales image to YouTube standard 1280x720 HD with duration badge safe-zone guide.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-xs font-semibold cursor-pointer">
                            <input type="checkbox" x-model="showOverlay" class="rounded text-violet-600"> Show Safe-Zone Badge Guide
                        </label>
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download 1280x720 Thumbnail
                        </button>
                    </div>
                    <div class="relative max-w-2xl mx-auto rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm aspect-video bg-black flex items-center justify-center">
                        <img :src="renderedUrl" class="w-full h-full object-cover">
                        <!-- Simulated timestamp overlay -->
                        <div x-show="showOverlay" class="absolute bottom-3 right-3 px-2 py-1 bg-black/80 text-white text-[10px] font-bold rounded">
                            12:45 (Safe-Zone)
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function ytThumbnailResizer() {
    return {
        originalFile: null, originalImage: null, renderedUrl: '', showOverlay: true,
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
            const c = document.createElement('canvas');
            c.width = 1280; c.height = 720;
            const ctx = c.getContext('2d');
            ctx.drawImage(this.originalImage, 0, 0, 1280, 720);
            c.toBlob((b) => { if (b) this.renderedUrl = URL.createObjectURL(b); }, 'image/jpeg', 0.92);
        },
        download() {
            const a = document.createElement('a'); a.href = this.renderedUrl;
            a.download = 'youtube_thumbnail_1280x720.jpg';
            a.click();
        }
    };
}
</script>