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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
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
        <div x-data="caseConverter()" class="space-y-6">
            <!-- Input Area -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <label for="case-input" class="text-sm font-bold text-slate-900">
                        {{ __('tools.case_converter.input_label') }}
                    </label>
                    <button
                        type="button"
                        @click="clear()"
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700"
                    >
                        {{ __('common.actions.clear') }}
                    </button>
                </div>

                <textarea
                    id="case-input"
                    x-model="input"
                    rows="6"
                    placeholder="{{ __('tools.case_converter.input_placeholder') }}"
                    class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-sm leading-relaxed"
                ></textarea>
            </div>

            <!-- Conversion Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    @click="toUpper()"
                    :class="activeMode === 'upper' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.upper') }}
                </button>

                <button
                    type="button"
                    @click="toLower()"
                    :class="activeMode === 'lower' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.lower') }}
                </button>

                <button
                    type="button"
                    @click="toTitle()"
                    :class="activeMode === 'title' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.title') }}
                </button>

                <button
                    type="button"
                    @click="toSentence()"
                    :class="activeMode === 'sentence' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.sentence') }}
                </button>

                <button
                    type="button"
                    @click="toCamel()"
                    :class="activeMode === 'camel' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.camel') }}
                </button>

                <button
                    type="button"
                    @click="toSnake()"
                    :class="activeMode === 'snake' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.snake') }}
                </button>

                <button
                    type="button"
                    @click="toKebab()"
                    :class="activeMode === 'kebab' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all focus:outline-none focus:ring-2 focus:ring-indigo-600"
                >
                    {{ __('tools.case_converter.modes.kebab') }}
                </button>
            </div>

            <!-- Output Result Area -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6 space-y-3">
                <div class="flex items-center justify-between">
                    <label for="case-output" class="text-sm font-bold text-slate-900">
                        {{ __('tools.case_converter.output_label') }}
                    </label>

                    <button
                        type="button"
                        @click="copy()"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-colors shadow-2xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9h-3.75c-.621 0-1.125.504-1.125 1.125v3.375m9.75 11.625h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125h9.75c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125Z" />
                        </svg>
                        <span x-text="copied ? '{{ __('common.actions.copied') }}' : '{{ __('common.actions.copy') }}'">{{ __('common.actions.copy') }}</span>
                    </button>
                </div>

                <textarea
                    id="case-output"
                    x-model="output"
                    rows="6"
                    readonly
                    class="w-full p-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 focus:outline-none text-sm leading-relaxed font-mono"
                ></textarea>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & Informational Guide -->
        <div class="mt-12 pt-10 border-t border-slate-200/80 space-y-8">
            <section>
                <h2 class="text-xl font-bold text-slate-900 mb-3">
                    {{ __('tools.case_converter.how_to_title') }}
                </h2>
                <ul class="list-disc list-inside space-y-2 text-sm text-slate-600">
                    @foreach(__('tools.case_converter.how_to_steps') as $step)
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
                        <span>{{ __('tools.case_converter.limitations_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.case_converter.limitations_text') }}
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200/80">
                    <h3 class="text-sm font-bold text-slate-900 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        <span>{{ __('tools.case_converter.privacy_title') }}</span>
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        {{ __('tools.case_converter.privacy_text') }}
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
