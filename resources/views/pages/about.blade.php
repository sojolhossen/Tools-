<x-layouts.app
    :title="__('pages.about.meta_title')"
    :description="__('pages.about.meta_desc')"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-breadcrumbs :items="[
            __('pages.about.title') => null,
        ]" />

        <div class="space-y-12">
            <!-- Header -->
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    {{ __('pages.about.title') }}
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">
                    {{ __('pages.about.title') }}
                </h1>
                <p class="mt-3 text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('pages.about.subtitle') }}
                </p>
            </div>

            <!-- Mission -->
            <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    {{ __('pages.about.mission_title') }}
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('pages.about.mission_text') }}
                </p>
            </div>

            <!-- Values -->
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">
                    {{ __('pages.about.values_title') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
                            <x-icon name="shield-check" class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">
                            {{ __('pages.about.values.privacy.title') }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('pages.about.values.privacy.desc') }}
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
                            <x-icon name="bolt" class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">
                            {{ __('pages.about.values.performance.title') }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('pages.about.values.performance.desc') }}
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
                            <x-icon name="globe" class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">
                            {{ __('pages.about.values.accessibility.title') }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            {{ __('pages.about.values.accessibility.desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Technology -->
            <div class="p-6 rounded-2xl bg-slate-100/70 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">
                    {{ __('pages.about.tech_title') }}
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('pages.about.tech_text') }}
                </p>
            </div>
        </div>
    </div>
</x-layouts.app>
