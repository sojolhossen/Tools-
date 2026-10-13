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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>

            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl leading-relaxed">
                {{ $tool->getDescription() }}
            </p>
        </div>

        <!-- Tool Interface -->
        <div x-data="wordCounter()" class="space-y-6">
            <!-- Metrics Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4" aria-live="polite">
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        {{ __('tools.word_counter.metrics.words') }}
                    </span>
                    <div class="mt-1 text-2xl sm:text-3xl font-black text-indigo-600" x-text="words">0</div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        {{ __('tools.word_counter.metrics.characters') }}
                    </span>
                    <div class="mt-1 text-2xl sm:text-3xl font-black text-slate-900 dark:text-white" x-text="characters">0</div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        {{ __('tools.word_counter.metrics.sentences') }}
                    </span>
                    <div class="mt-1 text-2xl sm:text-3xl font-black text-slate-900 dark:text-white" x-text="sentences">0</div>
                </div>

                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-2xs">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                        {{ __('tools.word_counter.metrics.paragraphs') }}
                    </span>
                    <div class="mt-1 text-2xl sm:text-3xl font-black text-slate-900 dark:text-white" x-text="paragraphs">0</div>
                </div>
            </div>

            <!-- Reading & Speaking Time Strip -->
            <div class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 flex flex-wrap items-center justify-between gap-4 text-xs sm:text-sm text-indigo-900">
                <div class="flex items-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="font-medium text-slate-600 dark:text-slate-400">{{ __('tools.word_counter.metrics.reading_time') }}:</span>
                        <strong class="font-bold text-indigo-700" x-text="readingTime">0s</strong>
                    </div>

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75Z" />
                        </svg>
                        <span class="font-medium text-slate-600 dark:text-slate-400">{{ __('tools.word_counter.metrics.speaking_time') }}:</span>
                        <strong class="font-bold text-indigo-700" x-text="speakingTime">0s</strong>
                    </div>
                </div>

                <div class="text-[11px] text-slate-500">
                    {{ __('tools.word_counter.metrics.characters_no_spaces') }}: <strong class="font-semibold text-slate-800" x-text="charactersNoSpaces">0</strong>
                </div>
            </div>

            <!-- Main Input Area -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-sm p-4 sm:p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <label for="text-input" class="text-sm font-bold text-slate-900 dark:text-slate-100">
                        {{ __('tools.word_counter.input_label') }}
                    </label>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            @click="copyStats()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 dark:text-slate-300 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-colors"
                        >
                            <span x-text="copiedStats ? '{{ __('common.actions.copied') }}' : 'Copy Stats'">Copy Stats</span>
                        </button>

                        <button
                            type="button"
                            @click="copyText()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 dark:text-slate-300 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-colors"
                        >
                            <span x-text="copiedText ? '{{ __('common.actions.copied') }}' : '{{ __('common.actions.copy') }}'">{{ __('common.actions.copy') }}</span>
                        </button>

                        <button
                            type="button"
                            @click="clear()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-colors"
                        >
                            {{ __('common.actions.clear') }}
                        </button>
                    </div>
                </div>

                <textarea
                    id="text-input"
                    x-model="text"
                    rows="12"
                    placeholder="{{ __('tools.word_counter.input_placeholder') }}"
                    class="w-full p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950/50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-slate-950 focus:outline-none focus:ring-2 focus:ring-indigo-600 text-sm sm:text-base leading-relaxed resize-y font-normal"
                ></textarea>

                <p class="text-xs text-slate-500 leading-normal">
                    {{ __('tools.word_counter.wpm_note') }}
                </p>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & Informational Guide (SEO Optimized) -->
        <x-tool-guide tool-key="word_counter" related-slug="case-converter" />

        <!-- Related Tools -->
        @if($relatedTools->count() > 0)
            <div class="mt-12 pt-10 border-t border-slate-200 dark:border-slate-700/80">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">
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
