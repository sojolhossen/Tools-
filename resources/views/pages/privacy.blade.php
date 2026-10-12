<x-layouts.app
    :title="__('pages.privacy.meta_title')"
    :description="__('pages.privacy.meta_desc')"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-breadcrumbs :items="[
            __('pages.privacy.title') => null,
        ]" />

        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-10 space-y-8">
            <header class="border-b border-slate-100 pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                    {{ __('pages.privacy.title') }}
                </span>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
                    {{ __('pages.privacy.title') }}
                </h1>
                <p class="mt-2 text-xs text-slate-400">
                    {{ __('pages.privacy.last_updated') }}
                </p>
                <p class="mt-3 text-base text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.subtitle') }}
                </p>
            </header>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('pages.privacy.section1_title') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.section1_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('pages.privacy.section2_title') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.section2_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('pages.privacy.section3_title') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.section3_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('pages.privacy.section4_title') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.section4_text') }}
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-lg font-bold text-slate-900">
                    {{ __('pages.privacy.section5_title') }}
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ __('pages.privacy.section5_text') }}
                </p>
            </section>
        </article>
    </div>
</x-layouts.app>
