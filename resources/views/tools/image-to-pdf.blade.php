<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
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
        <div x-data="imageToPdf()" class="space-y-6">
            <!-- Upload Dropzone -->
            <div
                tabindex="0"
                role="button"
                aria-label="{{ __('tools.image_to_pdf.drop_label') }}"
                @keydown.enter.prevent="$refs.filesInput.click()"
                @keydown.space.prevent="$refs.filesInput.click()"
                class="bg-white rounded-3xl border-2 border-dashed border-slate-300 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 p-8 sm:p-10 text-center transition-colors cursor-pointer group"
                @click="$refs.filesInput.click()"
                @dragover.prevent=""
                @drop.prevent="handleFilesSelect($event)"
            >
                <input
                    type="file"
                    x-ref="filesInput"
                    @change="handleFilesSelect($event)"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    class="hidden"
                >

                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                    <x-icon name="document-duplicate" class="w-7 h-7" />
                </div>

                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                    {{ __('tools.image_to_pdf.drop_label') }}
                </h3>

                <p class="mt-1 text-xs text-slate-500">
                    {{ __('tools.image_compressor.supported_formats') }}
                </p>

                <span class="mt-3 inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                    {{ __('common.actions.upload') }}
                </span>
            </div>

            <!-- Error Banner -->
            <div x-show="errorMessage" class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium" x-text="errorMessage"></div>

            <!-- Processing / Active Images Section -->
            <div x-show="images.length > 0" class="space-y-6" x-cloak>
                <!-- PDF Settings Bar -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Page Size -->
                        <div>
                            <label for="pdf-page-size" class="block text-xs font-bold uppercase text-slate-400 mb-1.5">
                                {{ __('tools.image_to_pdf.page_size') }}
                            </label>
                            <select
                                id="pdf-page-size"
                                x-model="pageSize"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                                <option value="a4">{{ __('tools.image_to_pdf.size_a4') }}</option>
                                <option value="letter">{{ __('tools.image_to_pdf.size_letter') }}</option>
                                <option value="fit">{{ __('tools.image_to_pdf.size_fit') }}</option>
                            </select>
                        </div>

                        <!-- Orientation -->
                        <div>
                            <label for="pdf-orient" class="block text-xs font-bold uppercase text-slate-400 mb-1.5">
                                {{ __('tools.image_to_pdf.orientation') }}
                            </label>
                            <select
                                id="pdf-orient"
                                x-model="orientation"
                                :disabled="pageSize === 'fit'"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600 disabled:opacity-50"
                            >
                                <option value="portrait">{{ __('tools.image_to_pdf.orient_portrait') }}</option>
                                <option value="landscape">{{ __('tools.image_to_pdf.orient_landscape') }}</option>
                                <option value="auto">Auto (Match Image)</option>
                            </select>
                        </div>

                        <!-- Margin -->
                        <div>
                            <label for="pdf-margin" class="block text-xs font-bold uppercase text-slate-400 mb-1.5">
                                {{ __('tools.image_to_pdf.margin_label') }}
                            </label>
                            <select
                                id="pdf-margin"
                                x-model="margin"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                                <option value="0">{{ __('tools.image_to_pdf.margin_none') }}</option>
                                <option value="10">{{ __('tools.image_to_pdf.margin_small') }}</option>
                                <option value="20">{{ __('tools.image_to_pdf.margin_normal') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="text-xs text-slate-600">
                            Total Images: <strong class="text-slate-900 font-bold" x-text="images.length">0</strong>
                            <span class="text-slate-300 mx-2">•</span>
                            <span class="text-slate-500">{{ __('tools.image_to_pdf.reorder_hint') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="generatePdf()"
                                :disabled="isGenerating || images.length === 0"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-xs sm:text-sm font-bold hover:bg-indigo-700 disabled:opacity-50 transition-colors shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <span x-text="isGenerating ? progressText : '{{ __('tools.image_to_pdf.btn_generate') }}'">{{ __('tools.image_to_pdf.btn_generate') }}</span>
                            </button>

                            <button
                                type="button"
                                @click="clearAll()"
                                class="px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors"
                            >
                                {{ __('common.actions.clear') }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ordered Image List -->
                <div class="space-y-3">
                    <template x-for="(img, idx) in images" :key="img.id">
                        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold flex items-center justify-center shrink-0" x-text="idx + 1">1</span>

                                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                                    <img :src="img.url" :alt="img.name" class="w-full h-full object-cover">
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-slate-900 truncate" x-text="img.name"></div>
                                    <div class="text-xs text-slate-500" x-text="formatBytes(img.size)"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <button
                                    type="button"
                                    @click="moveUp(idx)"
                                    :disabled="idx === 0"
                                    class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 disabled:opacity-30 disabled:hover:bg-transparent transition-colors"
                                    title="{{ __('tools.image_to_pdf.move_up') }}"
                                    aria-label="{{ __('tools.image_to_pdf.move_up') }}"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    @click="moveDown(idx)"
                                    :disabled="idx === images.length - 1"
                                    class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 disabled:opacity-30 disabled:hover:bg-transparent transition-colors"
                                    title="{{ __('tools.image_to_pdf.move_down') }}"
                                    aria-label="{{ __('tools.image_to_pdf.move_down') }}"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    @click="remove(idx)"
                                    class="p-2 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors ml-1"
                                    title="{{ __('tools.image_to_pdf.remove') }}"
                                    aria-label="{{ __('tools.image_to_pdf.remove') }}"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & On-Page SEO Guide -->
        <x-tool-guide tool-key="image_to_pdf" related-slug="image-compressor" related-slug2="image-resizer" />

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
