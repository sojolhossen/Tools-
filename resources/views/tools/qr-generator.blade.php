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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700">
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
        <div x-data="qrGenerator()" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Controls Column (Left) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="qr-text" class="block text-sm font-bold text-slate-900 mb-2">
                            {{ __('tools.qr_generator.input_label') }}
                        </label>
                        <textarea
                            id="qr-text"
                            x-model="text"
                            @input="generate()"
                            rows="4"
                            placeholder="{{ __('tools.qr_generator.input_placeholder') }}"
                            class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-sm leading-relaxed"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="qr-size" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('tools.qr_generator.size_label') }}
                            </label>
                            <select
                                id="qr-size"
                                x-model="size"
                                @change="generate()"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                                <option value="150">{{ __('tools.qr_generator.size_small') }}</option>
                                <option value="300">{{ __('tools.qr_generator.size_medium') }}</option>
                                <option value="600">{{ __('tools.qr_generator.size_large') }}</option>
                            </select>
                        </div>

                        <div>
                            <label for="qr-error" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                                {{ __('tools.qr_generator.error_label') }}
                            </label>
                            <select
                                id="qr-error"
                                x-model="errorCorrectionLevel"
                                @change="generate()"
                                class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
                            >
                                <option value="L">{{ __('tools.qr_generator.error_l') }}</option>
                                <option value="M">{{ __('tools.qr_generator.error_m') }}</option>
                                <option value="Q">{{ __('tools.qr_generator.error_q') }}</option>
                                <option value="H">{{ __('tools.qr_generator.error_h') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="generate()"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs sm:text-sm font-bold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 shadow-2xs transition-colors"
                        >
                            {{ __('common.actions.generate') }}
                        </button>

                        <button
                            type="button"
                            @click="reset()"
                            class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors"
                        >
                            {{ __('common.actions.reset') }}
                        </button>
                    </div>

                    <!-- Warning Notice -->
                    <div class="p-4 rounded-xl bg-amber-50/80 border border-amber-200/80 text-xs text-amber-900 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span>{{ __('tools.qr_generator.testing_warning') }}</span>
                    </div>
                </div>

                <!-- Preview & Download Column (Right) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
                        QR Code Preview
                    </span>

                    <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-inner flex items-center justify-center min-w-[220px] min-h-[220px]">
                        <canvas x-ref="qrCanvas" role="img" aria-label="Generated QR Code Preview" class="max-w-full h-auto"></canvas>
                    </div>

                    <div x-show="errorMessage" role="alert" aria-live="polite" class="mt-4 text-xs text-rose-600 font-medium" x-text="errorMessage"></div>

                    <!-- Download Buttons -->
                    <div x-show="isGenerated" class="mt-6 flex flex-col sm:flex-row gap-3 w-full">
                        <button
                            type="button"
                            @click="downloadPng()"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-2xs transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.5V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>{{ __('tools.qr_generator.download_png') }}</span>
                        </button>

                        <button
                            type="button"
                            @click="downloadSvg()"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors"
                        >
                            <span>{{ __('tools.qr_generator.download_svg') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & On-Page SEO Guide -->
        <x-tool-guide tool-key="qr_generator" related-slug="image-compressor" related-slug2="image-resizer" />

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
