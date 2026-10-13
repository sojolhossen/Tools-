<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="faviconGen()">
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
                    <x-icon name="globe-alt" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop logo to create Favicon Pack'"></h3>
                <p class="text-xs text-slate-500 mt-1">Generates 16x16, 32x32, 180x180 Apple Touch Icons & ready-to-paste HTML meta tags.</p>
            </div>

            <template x-if="favicons.length > 0">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="(f, idx) in favicons" :key="idx">
                            <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl text-center border border-slate-100 dark:border-slate-800">
                                <img :src="f.url" :style="'width:' + Math.min(64, f.size) + 'px; height:' + Math.min(64, f.size) + 'px;'" class="mx-auto rounded shadow-sm mb-2">
                                <span class="text-xs font-bold block" x-text="f.label"></span>
                                <span class="text-[10px] text-slate-400 block" x-text="f.size + 'x' + f.size + ' px'"></span>
                                <a :href="f.url" :download="f.filename" class="mt-2 text-xs text-violet-600 font-semibold hover:underline inline-block">Download</a>
                            </div>
                        </template>
                    </div>

                    <!-- HTML Snippet -->
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase text-slate-500">HTML Header Code</label>
                            <button type="button" @click="copyHtml()" class="text-xs font-semibold text-violet-600 hover:underline">Copy HTML Code</button>
                        </div>
                        <pre class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl text-xs font-mono text-slate-800 dark:text-slate-200 overflow-x-auto" x-text="htmlCode"></pre>
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function faviconGen() {
    return {
        originalFile: null, favicons: [],
        htmlCode: `<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">`,
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
            this.favicons = [];
            const specs = [
                { size: 16, label: 'Browser Tab', filename: 'favicon-16x16.png' },
                { size: 32, label: 'Desktop / Tab', filename: 'favicon-32x32.png' },
                { size: 180, label: 'Apple Touch Icon', filename: 'apple-touch-icon.png' },
                { size: 192, label: 'Android Chrome', filename: 'android-chrome-192x192.png' }
            ];
            specs.forEach(s => {
                const c = document.createElement('canvas');
                c.width = s.size; c.height = s.size;
                c.getContext('2d').drawImage(img, 0, 0, s.size, s.size);
                c.toBlob((b) => {
                    if (b) this.favicons.push({ ...s, url: URL.createObjectURL(b) });
                }, 'image/png');
            });
        },
        copyHtml() {
            navigator.clipboard.writeText(this.htmlCode);
            alert('HTML tags copied to clipboard!');
        }
    };
}
</script>