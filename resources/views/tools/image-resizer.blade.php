<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumbs -->
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <!-- Tool Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 text-violet-700">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $tool->getName() }}
            </h1>

            <p class="mt-2 text-base text-slate-600 max-w-3xl leading-relaxed">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <!-- Tool Interface -->
        <div x-data="imageResizer()" class="space-y-6">
            <!-- Dropzone (When no file is selected) -->
            <div
                x-show="!originalUrl"
                class="bg-white rounded-3xl border-2 border-dashed border-slate-300 hover:border-indigo-500 p-8 sm:p-12 text-center transition-colors cursor-pointer group"
                @click="$refs.fileInput.click()"
                @dragover.prevent=""
                @drop.prevent="handleFileSelect($event)"
            >
                <input
                    type="file"
                    x-ref="fileInput"
                    @change="handleFileSelect($event)"
                    accept="image/jpeg,image/png,image/webp"
                    class="hidden"
                >

                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                    <x-icon name="arrows-pointing-out" class="w-8 h-8" />
                </div>

                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                    {{ __('tools.image_resizer.drop_label') }}
                </h3>

                <p class="mt-2 text-xs text-slate-500">
                    {{ __('tools.image_compressor.supported_formats') }}
                </p>

                <span class="mt-4 inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                    {{ __('common.actions.upload') }}
                </span>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium" x-text="errorMessage"></div>

            <!-- Active File Interface -->
            <div x-show="originalUrl" class="space-y-6" x-cloak>
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Controls Column (Left) -->
                    <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="text-xs text-slate-500 flex items-center justify-between pb-3 border-b border-slate-100">
                            <span class="truncate" x-text="fileName"></span>
                            <span>{{ __('tools.image_compressor.original_label') }}: <strong class="text-slate-900" x-text="originalWidth + ' × ' + originalHeight + ' px'"></strong></span>
                        </div>

                        <!-- Width & Height Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="resize-w" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                    {{ __('tools.image_resizer.width_label') }}
                                </label>
                                <input
                                    id="resize-w"
                                    type="number"
                                    min="1"
                                    max="8192"
                                    x-model="targetWidth"
                                    @input="onWidthChange()"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                >
                            </div>

                            <div>
                                <label for="resize-h" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                    {{ __('tools.image_resizer.height_label') }}
                                </label>
                                <input
                                    id="resize-h"
                                    type="number"
                                    min="1"
                                    max="8192"
                                    x-model="targetHeight"
                                    @input="onHeightChange()"
                                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 font-semibold focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600"
                                >
                            </div>
                        </div>

                        <!-- Aspect Ratio Lock -->
                        <div class="flex items-center gap-2">
                            <input
                                id="aspect-lock"
                                type="checkbox"
                                x-model="lockAspectRatio"
                                class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300"
                            >
                            <label for="aspect-lock" class="text-xs font-semibold text-slate-700 select-none cursor-pointer">
                                {{ __('tools.image_resizer.lock_aspect') }}
                            </label>
                        </div>

                        <!-- Quick Percentage Scale Presets -->
                        <div>
                            <span class="block text-xs font-bold uppercase text-slate-400 mb-2">
                                {{ __('tools.image_resizer.scale_presets') }}
                            </span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="applyScale(25)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600">25%</button>
                                <button type="button" @click="applyScale(50)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600">50%</button>
                                <button type="button" @click="applyScale(75)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600">75%</button>
                                <button type="button" @click="applyScale(100)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600">100%</button>
                                <button type="button" @click="applyScale(200)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold hover:bg-indigo-50 hover:text-indigo-600">200%</button>
                            </div>
                        </div>

                        <!-- Output Format Choice -->
                        <div>
                            <label for="out-format" class="block text-xs font-bold uppercase text-slate-400 mb-1.5">
                                {{ __('tools.image_resizer.format_label') }}
                            </label>
                            <select
                                id="out-format"
                                x-model="outputFormat"
                                @change="renderResize()"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                                <option value="original">{{ __('tools.image_resizer.format_original') }}</option>
                                <option value="jpeg">{{ __('tools.image_resizer.format_jpeg') }}</option>
                                <option value="png">{{ __('tools.image_resizer.format_png') }}</option>
                                <option value="webp">{{ __('tools.image_resizer.format_webp') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-3 pt-3">
                            <button
                                type="button"
                                @click="download()"
                                :disabled="!resizedUrl || isProcessing"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs sm:text-sm font-bold hover:bg-indigo-700 disabled:opacity-50 transition-colors shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.5V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>{{ __('tools.image_resizer.download_resized') }}</span>
                            </button>

                            <button
                                type="button"
                                @click="reset()"
                                class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors"
                            >
                                {{ __('common.actions.reset') }}
                            </button>
                        </div>
                    </div>

                    <!-- Resized Preview Column (Right) -->
                    <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                                Resized Preview
                            </span>
                            <span class="text-xs font-bold text-slate-700" x-text="formatBytes(resizedSize)"></span>
                        </div>

                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-100">
                            <div x-show="isProcessing" class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center text-xs font-semibold text-indigo-600">
                                Rendering...
                            </div>
                            <img :src="resizedUrl" alt="Resized image preview" class="max-w-full max-h-full object-contain">
                        </div>

                        <div class="text-xs text-slate-500 flex items-center justify-between">
                            <span>New Dimensions: <strong class="text-slate-800" x-text="targetWidth + ' × ' + targetHeight + ' px'"></strong></span>
                            <span class="capitalize" x-text="'Format: ' + outputFormat"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & Informational Guide -->
        <div class="mt-12 pt-10 border-t border-slate-200/80 space-y-8">
            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-3">
                    {{ __('tools.image_resizer.how_to_title') }}
                </h2>
                <ul class="list-disc list-inside space-y-2 text-sm text-slate-600">
                    @foreach(__('tools.image_resizer.how_to_steps') as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span>{{ __('tools.image_resizer.limitations_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.image_resizer.limitations_text') }}
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <span>{{ __('tools.image_resizer.privacy_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.image_resizer.privacy_text') }}
                    </p>
                </div>
            </section>
        </div>

        <!-- Related Tools -->
        @if($relatedTools->count() > 0)
            <div class="mt-12 pt-10 border-t border-slate-200/80">
                <h2 class="text-xl font-bold text-slate-900 mb-6">
                    {{ $locale === 'bn' ? 'সম্পর্কিত অন্যান্য টুলস' : 'Related Utilities' }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedTools as $rel)
                        <x-tool-card :tool="$rel" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
