<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="appIconGen()">
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
                    <x-icon name="device-phone-mobile" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop 1024x1024 App Icon graphic'"></h3>
                <p class="text-xs text-slate-500 mt-1">Generates complete icon sets for iOS (iPhone/iPad/App Store) & Android (mdpi to xxxhdpi).</p>
            </div>

            <template x-if="icons.length > 0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-slate-500">Generated Icon Pack (<span x-text="icons.length"></span> sizes)</span>
                        <button type="button" @click="downloadAll()" class="px-5 py-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl text-xs">
                            Download All Icons
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                        <template x-for="(ico, idx) in icons" :key="idx">
                            <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl text-center border border-slate-100 dark:border-slate-800/80">
                                <img :src="ico.url" class="w-12 h-12 mx-auto rounded-lg shadow-sm mb-2">
                                <span class="text-xs font-bold block text-slate-800 dark:text-slate-200" x-text="ico.name"></span>
                                <span class="text-[10px] text-slate-400 block" x-text="ico.size + 'x' + ico.size"></span>
                                <button type="button" @click="downloadSingle(ico)" class="mt-2 text-[10px] font-semibold text-violet-600 hover:underline">Download</button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function appIconGen() {
    return {
        originalFile: null, icons: [],
        targets: [
            { name: 'App Store', size: 1024 },
            { name: 'Android xxxhdpi', size: 192 },
            { name: 'Android xxhdpi', size: 144 },
            { name: 'iPhone @3x', size: 180 },
            { name: 'iPhone @2x', size: 120 },
            { name: 'Android xhdpi', size: 96 },
            { name: 'iPad Pro', size: 167 },
            { name: 'iPad App', size: 152 },
            { name: 'Android hdpi', size: 72 },
            { name: 'iPad @1x', size: 76 },
            { name: 'Android mdpi', size: 48 },
            { name: 'Settings @3x', size: 87 }
        ],
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.originalFile = f;
            const r = new FileReader();
            r.onload = (e) => {
                const img = new Image();
                img.onload = () => this.generate(img);
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        },
        generate(img) {
            this.icons = [];
            this.targets.forEach(t => {
                const c = document.createElement('canvas');
                c.width = t.size; c.height = t.size;
                c.getContext('2d').drawImage(img, 0, 0, t.size, t.size);
                c.toBlob((b) => {
                    if (b) {
                        this.icons.push({
                            name: t.name,
                            size: t.size,
                            url: URL.createObjectURL(b)
                        });
                    }
                }, 'image/png');
            });
        },
        downloadSingle(ico) {
            const a = document.createElement('a'); a.href = ico.url;
            a.download = `icon_${ico.size}x${ico.size}.png`;
            a.click();
        },
        downloadAll() {
            this.icons.forEach((ico, idx) => {
                setTimeout(() => this.downloadSingle(ico), idx * 250);
            });
        }
    };
}
</script>