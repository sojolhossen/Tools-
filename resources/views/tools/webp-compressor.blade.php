<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="webpCompressor()">
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
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <div class="space-y-6 mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 hover:border-violet-500 p-8 text-center cursor-pointer transition-colors"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent=""
                 @drop.prevent="handleDrop($event)">
                <input type="file" x-ref="fileInput" @change="handleFile($event)" accept="image/webp,image/*" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="sparkles" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Choose or drop WebP photo to compress'"></h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">High-density modern WebP compression for blazing-fast web delivery.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                WebP Quality: <span class="font-bold text-violet-600 dark:text-violet-400" x-text="quality + '%'"></span>
                            </label>
                            <input type="range" min="5" max="95" step="5" x-model="quality" @input="compress()" class="w-full accent-violet-600">
                        </div>

                        <div class="flex items-center justify-around bg-slate-50 dark:bg-slate-950 p-4 rounded-xl text-center border border-slate-100 dark:border-slate-800/60">
                            <div>
                                <span class="text-xs text-slate-400 block">Original</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200" x-text="formatBytes(originalSize)"></span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-700">➔</span>
                            <div>
                                <span class="text-xs text-slate-400 block">WebP</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400" x-text="formatBytes(compressedSize)"></span>
                            </div>
                            <div class="px-2 py-1 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-lg" x-text="savingsText()"></div>
                        </div>

                        <div class="text-right">
                            <button type="button" @click="download()" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-semibold shadow-md transition-all">
                                <x-icon name="arrow-down-tray" class="w-4 h-4" />
                                <span>Download WebP</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="text-center">
                            <span class="text-xs font-semibold text-slate-500 mb-2 block">Original</span>
                            <div class="max-h-80 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-2">
                                <img :src="originalUrl" class="max-h-72 object-contain rounded-lg">
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="text-xs font-semibold text-slate-500 mb-2 block">Compressed WebP</span>
                            <div class="max-h-80 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 flex items-center justify-center p-2">
                                <img :src="compressedUrl" class="max-h-72 object-contain rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>

<script>
function webpCompressor() {
    return {
        originalFile: null,
        originalImage: null,
        originalUrl: '',
        compressedUrl: '',
        originalSize: 0,
        compressedSize: 0,
        quality: 75,
        handleDrop(e) { if (e.dataTransfer.files.length) this.loadFile(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.loadFile(e.target.files[0]); },
        loadFile(file) {
            this.originalFile = file;
            this.originalSize = file.size;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.originalUrl = e.target.result;
                const img = new Image();
                img.onload = () => { this.originalImage = img; this.compress(); };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        compress() {
            if (!this.originalImage) return;
            const canvas = document.createElement('canvas');
            canvas.width = this.originalImage.width;
            canvas.height = this.originalImage.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(this.originalImage, 0, 0);
            canvas.toBlob((blob) => {
                if (blob) {
                    this.compressedSize = blob.size;
                    this.compressedUrl = URL.createObjectURL(blob);
                }
            }, 'image/webp', this.quality / 100);
        },
        formatBytes(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (bytes / Math.pow(k, i)).toFixed(1) + ' ' + sizes[i];
        },
        savingsText() {
            if (!this.originalSize || !this.compressedSize) return '-0%';
            const diff = ((this.originalSize - this.compressedSize) / this.originalSize) * 100;
            return (diff > 0 ? '-' : '+') + Math.abs(diff).toFixed(0) + '%';
        },
        download() {
            if (!this.compressedUrl) return;
            const a = document.createElement('a');
            a.href = this.compressedUrl;
            a.download = 'compressed_' + (this.originalFile ? this.originalFile.name.replace(/\.[^/.]+$/, "") : 'image') + '.webp';
            a.click();
        }
    };
}
</script>