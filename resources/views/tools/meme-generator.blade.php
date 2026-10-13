<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="memeGen()">
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
                    <x-icon name="face-smile" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="originalFile ? originalFile.name : 'Select or drop image to create Meme'"></h3>
                <p class="text-xs text-slate-500 mt-1">Add classic Impact font top and bottom bold captions.</p>
            </div>

            <template x-if="originalImage">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Top Caption Text</label>
                            <input type="text" x-model="topText" @input="render()" placeholder="TOP TEXT" class="w-full text-xs font-bold uppercase p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Bottom Caption Text</label>
                            <input type="text" x-model="bottomText" @input="render()" placeholder="BOTTOM TEXT" class="w-full text-xs font-bold uppercase p-2.5 border rounded-xl bg-slate-50 dark:bg-slate-950">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download Meme
                        </button>
                    </div>
                    <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                        <img :src="renderedUrl" class="max-h-80 mx-auto rounded shadow-sm border border-slate-100 dark:border-slate-800">
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function memeGen() {
    return {
        originalFile: null, originalImage: null, renderedUrl: '', topText: 'WHEN THE CODE COMPILES', bottomText: 'ON THE FIRST TRY',
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

            const fontSize = Math.max(24, Math.round(img.width * 0.08));
            ctx.font = `900 ${fontSize}px Impact, sans-serif`;
            ctx.fillStyle = '#ffffff';
            ctx.strokeStyle = '#000000';
            ctx.lineWidth = Math.max(3, fontSize * 0.08);
            ctx.textAlign = 'center';

            if (this.topText) {
                ctx.strokeText(this.topText.toUpperCase(), c.width / 2, fontSize + 20);
                ctx.fillText(this.topText.toUpperCase(), c.width / 2, fontSize + 20);
            }
            if (this.bottomText) {
                ctx.strokeText(this.bottomText.toUpperCase(), c.width / 2, c.height - 20);
                ctx.fillText(this.bottomText.toUpperCase(), c.width / 2, c.height - 20);
            }

            c.toBlob((b) => { if (b) this.renderedUrl = URL.createObjectURL(b); }, 'image/jpeg', 0.92);
        },
        download() {
            const a = document.createElement('a'); a.href = this.renderedUrl;
            a.download = 'meme.jpg';
            a.click();
        }
    };
}
</script>