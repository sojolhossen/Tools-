<x-layouts.app
    :title="$page->getMetaTitle($locale)"
    :description="$page->getMetaDescription($locale)"
>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <x-breadcrumbs :items="[
            $page->getTitle($locale) => null,
        ]" />

        <article class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-10 space-y-6">
            <header class="border-b border-slate-100 pb-6">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    {{ $page->getTitle($locale) }}
                </h1>
                @if($page->published_at)
                    <p class="mt-2 text-xs text-slate-500 font-medium">
                        Published on {{ $page->published_at->format('F j, Y') }} &middot; Last updated {{ $page->updated_at->diffForHumans() }}
                    </p>
                @endif
            </header>

            <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed space-y-4">
                {!! $renderedContent !!}
            </div>
        </article>
    </div>
</x-layouts.app>
