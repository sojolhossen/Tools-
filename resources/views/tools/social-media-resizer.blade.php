<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="socialResizer()">
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
                    <x-icon name="share" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image for social media'"></h3>
                <p class="text-xs text-slate-500 mt-1">One-click resize for Facebook, Instagram, Twitter/X, and LinkedIn.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                        <template x-for="p in presets" :key="p.name">
                            <button type="button" @click="selected = p; render()" :class="selected.name === p.name ? 'border-violet-600 bg-violet-50 dark:bg-violet-950/60' : 'border-slate-200 dark:border-slate-800'" class="p-3 rounded-xl border text-center transition-all">
                                <span class="text-xs font-bold block" x-text="p.name"></span>
                                <span class="text-[10px] text-slate-400 block" x-text="p.w + 'x' + p.h"></span>
                            </button>
                        </template>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-500" x-text="selected.name + ' (' + selected.w + 'x' + selected.h + ')'"></span>
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download
                        </button>
                    </div>

                    <div class="text-center pt-2">
                        <img :src="renderedUrl" class="max-h-80 mx-auto rounded-lg shadow-sm border border-slate-100 dark:border-slate-800">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function socialResizer() {
    return {
        originalFile: null, originalImage: null, renderedUrl: '',
        presets: [
            { name: 'IG Square', w: 1080, h: 1080 },
            { name: 'IG Story', w: 1080, h: 1920 },
            { name: 'FB Post', w: 1200, h: 630 },
            { name: 'FB Cover', w: 820, h: 312 },
            { name: 'X Header', w: 1500, h: 500 },
            { name: 'LinkedIn Banner', w: 1584, h: 396 }
        ],
        selected: null,
        init() { this.selected = this.presets[0]; },
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
            if (!this.originalImage || !this.selected) return;
            const c = document.createElement('canvas');
            c.width = this.selected.w; c.height = this.selected.h;
            c.getContext('2d').drawImage(this.originalImage, 0, 0, this.selected.w, this.selected.h);
            c.toBlob((b) => { if (b) this.renderedUrl = URL.createObjectURL(b); }, 'image/jpeg', 0.9);
        },
        download() {
            const a = document.createElement('a'); a.href = this.renderedUrl;
            a.download = this.selected.name.toLowerCase().replace(/\s+/g, '_') + '.jpg';
            a.click();
        }
    };
}
</script>