<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="bulkImageCompressor()">
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
            <!-- Dropzone -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 hover:border-violet-500 p-8 text-center cursor-pointer transition-colors"
                 @click="$refs.fileInput.click()"
                 @dragover.prevent=""
                 @drop.prevent="handleDrop($event)">
                <input type="file" x-ref="fileInput" @change="handleFiles($event)" multiple accept="image/*" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="rectangle-stack" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Drop multiple images here or click to browse</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Batch process JPG, PNG, and WebP photos directly in memory.</p>
            </div>

            <template x-if="files.length > 0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <label class="text-xs font-semibold uppercase text-slate-500">Quality: <span class="font-bold text-violet-600" x-text="quality + '%'"></span></label>
                            <input type="range" min="10" max="90" step="10" x-model="quality" @change="reprocessAll()" class="w-36 accent-violet-600">
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-500">Total Saved: <strong class="text-emerald-600" x-text="totalSavingsText()"></strong></span>
                            <button type="button" @click="downloadAll()" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-xl">
                                Download All
                            </button>
                        </div>
                    </div>

                    <!-- Queue Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left">
                            <thead class="text-slate-400 uppercase bg-slate-50 dark:bg-slate-950/60">
                                <tr>
                                    <th class="p-3">File</th>
                                    <th class="p-3">Original</th>
                                    <th class="p-3">Compressed</th>
                                    <th class="p-3">Savings</th>
                                    <th class="p-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <template x-for="(item, idx) in files" :key="idx">
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="p-3 font-medium text-slate-900 dark:text-slate-200 truncate max-w-xs" x-text="item.name"></td>
                                        <td class="p-3 text-slate-500" x-text="formatBytes(item.origSize)"></td>
                                        <td class="p-3 font-semibold text-emerald-600" x-text="formatBytes(item.compSize)"></td>
                                        <td class="p-3">
                                            <span class="px-2 py-0.5 rounded text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 font-semibold" x-text="item.savings"></span>
                                        </td>
                                        <td class="p-3 text-right">
                                            <button type="button" @click="downloadItem(item)" class="text-violet-600 hover:text-violet-800 font-bold">Download</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>

<script>
function bulkImageCompressor() {
    return {
        files: [],
        quality: 70,
        handleDrop(e) { if (e.dataTransfer.files.length) this.addFiles(e.dataTransfer.files); },
        handleFiles(e) { if (e.target.files.length) this.addFiles(e.target.files); },
        addFiles(fileList) {
            for (let f of fileList) {
                if (!f.type.startsWith('image/')) continue;
                this.processFile(f);
            }
        },
        processFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    canvas.toBlob((blob) => {
                        const compSize = blob ? blob.size : file.size;
                        const diff = ((file.size - compSize) / file.size) * 100;
                        const item = {
                            name: file.name,
                            origSize: file.size,
                            compSize: compSize,
                            savings: '-' + Math.max(0, diff).toFixed(0) + '%',
                            url: blob ? URL.createObjectURL(blob) : e.target.result,
                            rawImg: img
                        };
                        this.files.push(item);
                    }, 'image/jpeg', this.quality / 100);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        reprocessAll() {
            this.files.forEach(item => {
                const canvas = document.createElement('canvas');
                canvas.width = item.rawImg.width;
                canvas.height = item.rawImg.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(item.rawImg, 0, 0);
                canvas.toBlob((blob) => {
                    if (blob) {
                        item.compSize = blob.size;
                        const diff = ((item.origSize - blob.size) / item.origSize) * 100;
                        item.savings = '-' + Math.max(0, diff).toFixed(0) + '%';
                        item.url = URL.createObjectURL(blob);
                    }
                }, 'image/jpeg', this.quality / 100);
            });
        },
        formatBytes(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (bytes / Math.pow(k, i)).toFixed(1) + ' ' + sizes[i];
        },
        totalSavingsText() {
            let orig = 0, comp = 0;
            this.files.forEach(f => { orig += f.origSize; comp += f.compSize; });
            if (!orig) return '0%';
            return '-' + (((orig - comp) / orig) * 100).toFixed(0) + '%';
        },
        downloadItem(item) {
            const a = document.createElement('a');
            a.href = item.url;
            a.download = 'compressed_' + item.name;
            a.click();
        },
        downloadAll() {
            this.files.forEach((item, idx) => {
                setTimeout(() => this.downloadItem(item), idx * 250);
            });
        }
    };
}
</script>