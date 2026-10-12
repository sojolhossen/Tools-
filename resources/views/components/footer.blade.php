@php
    $locale = app()->getLocale();
    $targetLocale = $locale === 'en' ? 'bn' : 'en';
    $targetLanguageLabel = $locale === 'en' ? 'বাংলা সংস্করণ' : 'English Version';
    
    $currentRouteName = Route::currentRouteName() ?? 'home';
    $currentParameters = Route::current() ? Route::current()->parameters() : [];
    $targetParameters = array_merge($currentParameters, ['locale' => $targetLocale]);
    try {
        $switchUrl = route($currentRouteName, $targetParameters);
    } catch (\Throwable $e) {
        $switchUrl = url('/' . $targetLocale);
    }

    $toolRegistry = app(\App\Support\Tools\ToolRegistry::class);
    $categories = $toolRegistry->getCategories();
    $activeTools = $toolRegistry->active()->take(6);
@endphp

<footer class="mt-20 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-colors" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
            <!-- Brand Column -->
            <div class="lg:col-span-2 space-y-4">
                <a href="{{ route('home', ['locale' => $locale]) }}" class="inline-flex items-center group focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 rounded-lg p-0.5 transition-transform group-hover:scale-[1.02]">
                    <x-logo size="md" />
                </a>
                
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm">
                    {{ __('common.footer.about_text') }}
                </p>

                <div class="flex items-center gap-2 text-xs text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-800/60 rounded-lg px-3 py-2 w-fit">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>{{ __('common.footer.no_account_required') }}</span>
                </div>
            </div>

            <!-- Categories Column -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    {{ __('common.footer.categories_title') }}
                </h3>
                <ul class="space-y-2.5 text-sm">
                    @foreach($categories as $catKey => $catData)
                        <li>
                            <a href="{{ route('tools.category', ['locale' => $locale, 'category' => $catKey]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ __('common.categories.' . $catKey) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Featured Tools Column -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    {{ __('common.nav.tools') }}
                </h3>
                <ul class="space-y-2.5 text-sm">
                    @foreach($activeTools as $t)
                        <li>
                            <a href="{{ route('tools.show', ['locale' => $locale, 'slug' => $t->slug]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                {{ $t->getName() }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Legal & Information Column -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    {{ __('common.footer.legal_title') }}
                </h3>
                <ul class="space-y-2.5 text-sm">
                    <li>
                        <a href="{{ route('pages.about', ['locale' => $locale]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('common.footer.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.contact', ['locale' => $locale]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('common.footer.contact') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.privacy', ['locale' => $locale]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('common.footer.privacy') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.terms', ['locale' => $locale]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('common.footer.terms') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.disclaimer', ['locale' => $locale]) }}" class="text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {{ __('common.footer.disclaimer') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
            <p>
                &copy; {{ date('Y') }} {{ config('app.name', 'ToolZeno') }}. {{ __('common.footer.all_rights_reserved') }}
            </p>

            <div class="flex items-center gap-4">
                <a href="{{ $switchUrl }}" class="font-medium text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 underline transition-colors">
                    {{ $targetLanguageLabel }}
                </a>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <a href="{{ route('sitemap') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    Sitemap XML
                </a>
            </div>
        </div>
    </div>
</footer>
