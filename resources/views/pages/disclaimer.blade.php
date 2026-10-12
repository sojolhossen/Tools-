<x-layouts.app
    :title="__('pages.disclaimer.meta_title')"
    :description="__('pages.disclaimer.meta_desc')"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-breadcrumbs :items="[
            __('pages.disclaimer.title') => null,
        ]" />

        <article class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 sm:p-10 space-y-8">
            <header class="border-b border-slate-100 dark:border-slate-800 pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                    {{ __('pages.disclaimer.title') }}
                </span>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">
                    {{ __('pages.disclaimer.title') }}
                </h1>
                <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">
                    {{ __('pages.disclaimer.last_updated') }}
                </p>
                <p class="mt-3 text-base text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ __('pages.disclaimer.subtitle') }}
                </p>
            </header>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ __('pages.disclaimer.section1_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('pages.disclaimer.section1_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ __('pages.disclaimer.section2_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('pages.disclaimer.section2_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ __('pages.disclaimer.section3_title') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('pages.disclaimer.section3_text') }}
                </p>
            </section>
        </article>
    </div>
</x-layouts.app>
