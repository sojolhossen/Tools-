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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
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
        <div x-data="jsonFormatter()" class="space-y-6">
            <!-- Toolbar -->
            <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <button
                        type="button"
                        @click="format()"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs sm:text-sm font-bold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-colors shadow-2xs"
                    >
                        {{ __('common.actions.format') }}
                    </button>

                    <button
                        type="button"
                        @click="minify()"
                        class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs sm:text-sm font-semibold hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-400 transition-colors"
                    >
                        {{ __('common.actions.minify') }}
                    </button>

                    <div class="flex items-center gap-1 text-xs text-slate-600 pl-2">
                        <label for="indent-select" class="font-medium">
                            {{ __('tools.json_formatter.indent_label') }}
                        </label>
                        <select
                            id="indent-select"
                            x-model="indent"
                            @change="format()"
                            class="px-2 py-1 rounded-lg border border-slate-200 bg-slate-50 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-600"
                        >
                            <option value="2">{{ __('tools.json_formatter.indent_2') }}</option>
                            <option value="4">{{ __('tools.json_formatter.indent_4') }}</option>
                            <option value="tab">{{ __('tools.json_formatter.indent_tab') }}</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button
                        type="button"
                        @click="loadSample()"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors"
                    >
                        {{ __('tools.json_formatter.load_sample') }}
                    </button>

                    <button
                        type="button"
                        @click="copy()"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors"
                    >
                        <span x-text="copied ? '{{ __('common.actions.copied') }}' : '{{ __('common.actions.copy') }}'">{{ __('common.actions.copy') }}</span>
                    </button>

                    <button
                        type="button"
                        @click="clear()"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors"
                    >
                        {{ __('common.actions.clear') }}
                    </button>
                </div>
            </div>

            <!-- Validation Status Banner -->
            <div x-show="isValid !== null" class="transition-all" role="status" aria-live="polite" x-cloak>
                <div x-show="isValid === true" class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>{{ __('tools.json_formatter.status_valid') }}</span>
                </div>

                <div x-show="isValid === false" class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 font-medium">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span class="font-bold">{{ __('tools.json_formatter.status_invalid') }}:</span>
                        <span class="font-mono" x-text="errorMessage"></span>
                    </div>
                </div>
            </div>

            <!-- JSON Editor Textarea -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6 space-y-2">
                <label for="json-input" class="sr-only">
                    {{ __('tools.json_formatter.input_label') }}
                </label>
                <textarea
                    id="json-input"
                    x-model="input"
                    @input="validate()"
                    rows="16"
                    placeholder="{{ __('tools.json_formatter.input_placeholder') }}"
                    class="w-full p-4 rounded-xl border border-slate-200 bg-slate-900 text-emerald-400 placeholder:text-slate-500 font-mono text-xs sm:text-sm leading-relaxed focus:outline-none focus:ring-2 focus:ring-indigo-600 resize-y"
                    spellcheck="false"
                ></textarea>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & Informational Guide (SEO Optimized) -->
        <x-tool-guide tool-key="json_formatter" related-slug="case-converter" />

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
