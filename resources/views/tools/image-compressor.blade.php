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
        <div x-data="imageCompressor()" class="space-y-6">
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
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Z" />
                    </svg>
                </div>

                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                    {{ __('tools.image_compressor.drop_label') }}
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
                <!-- Controls Bar -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                {{ __('tools.image_compressor.quality_label') }}
                            </span>
                            <div class="flex items-center gap-4 mt-2">
                                <input
                                    type="range"
                                    min="10"
                                    max="100"
                                    step="5"
                                    x-model="quality"
                                    @input="compress()"
                                    class="w-48 sm:w-64 accent-indigo-600 cursor-pointer"
                                >
                                <span class="text-sm font-black text-indigo-600 w-12" x-text="quality + '%'">75%</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="download()"
                                :disabled="!compressedUrl || isProcessing"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-xs sm:text-sm font-bold hover:bg-indigo-700 disabled:opacity-50 transition-colors shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.5V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span>{{ __('tools.image_compressor.download_compressed') }}</span>
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
                </div>

                <!-- Side by Side Comparison Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Original Preview Card -->
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                {{ __('tools.image_compressor.original_label') }}
                            </span>
                            <span class="text-xs font-bold text-slate-700" x-text="formatBytes(originalSize)"></span>
                        </div>

                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-100">
                            <img :src="originalUrl" alt="Original image preview" class="max-w-full max-h-full object-contain">
                        </div>

                        <div class="text-[11px] text-slate-400 truncate" x-text="fileName + ' (' + width + ' × ' + height + ' px)'"></div>
                    </div>

                    <!-- Compressed Preview Card -->
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                                {{ __('tools.image_compressor.compressed_label') }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-indigo-700" x-text="formatBytes(compressedSize)"></span>
                                <span
                                    x-show="savingsPercent > 0"
                                    class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800"
                                    x-text="'-' + savingsPercent + '%'"
                                ></span>
                            </div>
                        </div>

                        <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center border border-slate-100">
                            <div x-show="isProcessing" class="absolute inset-0 bg-white/70 backdrop-blur-xs flex items-center justify-center text-xs font-semibold text-indigo-600">
                                Processing...
                            </div>
                            <img :src="compressedUrl" alt="Compressed image preview" class="max-w-full max-h-full object-contain">
                        </div>

                        <div class="text-[11px] text-slate-500">
                            <span x-show="savingsPercent > 0" class="text-emerald-600 font-semibold">
                                {{ __('tools.image_compressor.size_saved') }}: <span x-text="savingsPercent + '%'"></span>
                            </span>
                            <span x-show="savingsPercent <= 0 && !isProcessing" class="text-slate-400">
                                {{ __('tools.image_compressor.size_larger') }}
                            </span>
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
                    {{ __('tools.image_compressor.how_to_title') }}
                </h2>
                <ul class="list-disc list-inside space-y-2 text-sm text-slate-600">
                    @foreach(__('tools.image_compressor.how_to_steps') as $step)
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
                        <span>{{ __('tools.image_compressor.limitations_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.image_compressor.limitations_text') }}
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <span>{{ __('tools.image_compressor.privacy_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.image_compressor.privacy_text') }}
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
