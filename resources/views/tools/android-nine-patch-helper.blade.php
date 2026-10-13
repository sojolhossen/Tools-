<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ninePatchHelper()">
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
                <input type="file" x-ref="fileInput" @change="handleFile($event)" accept="image/png" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="wrench-screwdriver" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop Android 9-Patch (.9.png)'"></h3>
                <p class="text-xs text-slate-500 mt-1">Preview stretchable areas and test 9-patch scaling in real-time.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-slate-500">Interactive Stretch Test</span>
                        <div class="flex items-center gap-3">
                            <label class="text-xs">Stretch Width: <input type="range" min="100" max="400" x-model.number="testW" class="accent-violet-600"></label>
                            <label class="text-xs">Stretch Height: <input type="range" min="50" max="250" x-model.number="testH" class="accent-violet-600"></label>
                        </div>
                    </div>
                    <div class="p-8 bg-slate-50 dark:bg-slate-950 rounded-xl flex items-center justify-center min-h-60">
                        <div :style="'width:' + testW + 'px; height:' + testH + 'px; background-image:url(' + originalUrl + '); background-size:100% 100%;'" class="rounded border border-dashed border-violet-400 flex items-center justify-center text-xs font-bold text-slate-400">
                            9-Patch Scaled Container
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function ninePatchHelper() {
    return {
        originalFile: null, originalImage: null, originalUrl: '', testW: 240, testH: 100,
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                this.originalUrl = e.target.result;
                const img = new Image();
                img.onload = () => { this.originalImage = img; };
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        }
    };
}
</script>