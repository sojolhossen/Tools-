<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="svgPathVisualizer()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
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
            <!-- Path Input & Presets -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 sm:p-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">SVG Path "d" Attribute Data</label>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-400">Presets:</span>
                        <button type="button" @click="loadPreset('heart')" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300">Heart</button>
                        <button type="button" @click="loadPreset('star')" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300">Star</button>
                        <button type="button" @click="loadPreset('check')" class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-300">Check</button>
                    </div>
                </div>
                <textarea x-model="dPath" placeholder="Paste SVG path (e.g. M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z)..." rows="4" class="w-full font-mono text-xs sm:text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-primary-500 focus:outline-none transition-all"></textarea>
            </div>

            <!-- Controls and Canvas Preview -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                <!-- Visualizer Controls -->
                <div class="md:col-span-5 space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Fill Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="fillColor" class="w-8 h-8 rounded border border-slate-200 cursor-pointer">
                            <input type="text" x-model="fillColor" class="font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Stroke Color:</label>
                        <div class="flex items-center gap-2">
                            <input type="color" x-model="strokeColor" class="w-8 h-8 rounded border border-slate-200 cursor-pointer">
                            <input type="text" x-model="strokeColor" class="font-mono bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg px-2.5 py-1 text-slate-900 dark:text-slate-100">
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">Stroke Width: <span x-text="strokeWidth"></span>px</label>
                        <input type="range" x-model.number="strokeWidth" min="0" max="10" step="0.5" class="w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 dark:text-slate-400 mb-1">ViewBox Size (Square): <span x-text="viewBoxSize"></span>px</label>
                        <input type="range" x-model.number="viewBoxSize" min="24" max="256" step="8" class="w-full">
                    </div>
                </div>

                <!-- Preview Canvas -->
                <div class="md:col-span-7 flex flex-col items-center justify-center p-8 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 min-h-[300px]">
                    <svg :viewBox="'0 0 ' + viewBoxSize + ' ' + viewBoxSize" class="w-48 h-48 drop-shadow-md transition-all">
                        <path :d="dPath" :fill="fillColor" :stroke="strokeColor" :stroke-width="strokeWidth" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mt-4 text-xs font-mono text-slate-400" x-text="'ViewBox: 0 0 ' + viewBoxSize + ' ' + viewBoxSize"></span>
                </div>
            </div>
        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="html-css-js-beautifier" />
    </div>

    @push('scripts')
    <script>
        function svgPathVisualizer() {
            return {
                dPath: 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z',
                fillColor: '#ef4444',
                strokeColor: '#b91c1c',
                strokeWidth: 1,
                viewBoxSize: 24,
                loadPreset(name) {
                    if (name === 'heart') {
                        this.dPath = 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z';
                        this.fillColor = '#ef4444';
                        this.viewBoxSize = 24;
                    } else if (name === 'star') {
                        this.dPath = 'M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z';
                        this.fillColor = '#f59e0b';
                        this.viewBoxSize = 24;
                    } else if (name === 'check') {
                        this.dPath = 'M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z';
                        this.fillColor = '#10b981';
                        this.viewBoxSize = 24;
                    }
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
