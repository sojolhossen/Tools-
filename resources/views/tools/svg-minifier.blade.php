<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="svgMinifier()">
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
            <!-- Options & Stats -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="stripComments" @change="minify()" class="rounded text-violet-600"> Strip Comments
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="stripMetadata" @change="minify()" class="rounded text-violet-600"> Remove Metadata & Doctype
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300">
                        <input type="checkbox" x-model="collapseWhitespace" @change="minify()" class="rounded text-violet-600"> Collapse Whitespace
                    </label>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-lg" x-text="savingsBadge"></span>
                    <button type="button" @click="loadSample()" class="text-xs text-violet-600 font-semibold hover:underline">Sample SVG</button>
                </div>
            </div>

            <!-- Editor Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Input -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 space-y-3">
                    <div class="flex justify-between items-center text-xs">
                        <label class="font-bold uppercase text-slate-600 dark:text-slate-400">Raw SVG Code</label>
                        <span class="text-slate-400" x-text="rawSize + ' bytes'"></span>
                    </div>
                    <textarea x-model="rawSvg" @input="minify()" rows="12" placeholder="Paste <svg> markup here..." class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-violet-500"></textarea>
                </div>

                <!-- Minified Output -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 space-y-3">
                    <div class="flex justify-between items-center text-xs">
                        <label class="font-bold uppercase text-slate-600 dark:text-slate-400">Minified SVG</label>
                        <span class="text-emerald-600 font-bold" x-text="minSize + ' bytes'"></span>
                    </div>
                    <textarea x-model="minifiedSvg" readonly rows="12" class="w-full font-mono text-xs bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 focus:outline-none"></textarea>
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="copy()" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-xs font-semibold rounded-xl text-slate-700 dark:text-slate-300">Copy Code</button>
                        <button type="button" @click="download()" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-xs font-semibold rounded-xl text-white">Download SVG</button>
                    </div>
                </div>
            </div>

            <!-- Live SVG Render Preview -->
            <template x-if="minifiedSvg">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 text-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 block">Render Preview</span>
                    <div class="max-h-60 overflow-hidden flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-950 rounded-xl" x-html="minifiedSvg"></div>
                </div>
            </template>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>

<script>
function svgMinifier() {
    return {
        rawSvg: '',
        minifiedSvg: '',
        rawSize: 0,
        minSize: 0,
        stripComments: true,
        stripMetadata: true,
        collapseWhitespace: true,
        savingsBadge: '0% Saved',
        minify() {
            let svg = this.rawSvg || '';
            this.rawSize = new Blob([svg]).size;
            if (!svg.trim()) {
                this.minifiedSvg = '';
                this.minSize = 0;
                this.savingsBadge = '0% Saved';
                return;
            }
            if (this.stripComments) svg = svg.replace(/<!--[\s\S]*?-->/g, '');
            if (this.stripMetadata) {
                svg = svg.replace(/<\?xml[\s\S]*?\?>/gi, '');
                svg = svg.replace(/<!DOCTYPE[\s\S]*?>/gi, '');
                svg = svg.replace(/<metadata[\s\S]*?<\/metadata>/gi, '');
            }
            if (this.collapseWhitespace) {
                svg = svg.replace(/>\s+</g, '><').replace(/\s{2,}/g, ' ').trim();
            }
            this.minifiedSvg = svg;
            this.minSize = new Blob([svg]).size;
            const diff = this.rawSize > 0 ? ((this.rawSize - this.minSize) / this.rawSize) * 100 : 0;
            this.savingsBadge = '-' + Math.max(0, diff).toFixed(1) + '% Saved';
        },
        loadSample() {
            this.rawSvg = `<!-- ToolZeno Sample Vector -->
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
    <metadata>Designed for Web</metadata>
    <circle cx="50" cy="50" r="40" fill="#7c3aed" stroke="#ffffff" stroke-width="4" />
    <polygon points="50,25 60,65 30,40 70,40 40,65" fill="#facc15" />
</svg>`;
            this.minify();
        },
        copy() {
            navigator.clipboard.writeText(this.minifiedSvg);
            alert('Minified SVG copied to clipboard!');
        },
        download() {
            const blob = new Blob([this.minifiedSvg], { type: 'image/svg+xml' });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'minified.svg';
            a.click();
        }
    };
}
</script>