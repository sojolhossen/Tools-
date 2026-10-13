@props([
    'toolKey',
    'relatedSlug' => null,
    'relatedSlug2' => null,
])

@php
    $locale = app()->getLocale();
    $howToTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.how_to_title') 
        ? __('tools.' . $toolKey . '.how_to_title') 
        : ($locale === 'bn' ? 'ব্যবহার নির্দেশিকা' : 'How to Use this Tool');
    $howToSteps = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.how_to_steps') 
        ? __('tools.' . $toolKey . '.how_to_steps') 
        : [];
    $specsTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.specs_title') 
        ? __('tools.' . $toolKey . '.specs_title') 
        : ($locale === 'bn' ? 'প্রযুক্তিগত বৈশিষ্ট্য' : 'Technical Specifications');
    $specs = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.specs') 
        ? __('tools.' . $toolKey . '.specs') 
        : [];
    $examplesTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.examples_title') 
        ? __('tools.' . $toolKey . '.examples_title') 
        : ($locale === 'bn' ? 'বাস্তব উদাহরণ' : 'Usage Examples');
    $examples = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.examples') 
        ? __('tools.' . $toolKey . '.examples') 
        : [];
    $faqTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.faq_title') 
        ? __('tools.' . $toolKey . '.faq_title') 
        : ($locale === 'bn' ? 'সাধারণ প্রশ্নোত্তর' : 'Frequently Asked Questions');
    $faq = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.faq') 
        ? __('tools.' . $toolKey . '.faq') 
        : [];
    $relatedContext = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.related_context') 
        ? __('tools.' . $toolKey . '.related_context') 
        : '';

    $limitationsTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.limitations_title') 
        ? __('tools.' . $toolKey . '.limitations_title') 
        : (\Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.security_title') ? __('tools.' . $toolKey . '.security_title') : null);

    $limitationsText = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.limitations_text') 
        ? __('tools.' . $toolKey . '.limitations_text') 
        : (\Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.security_text') ? __('tools.' . $toolKey . '.security_text') : null);

    $privacyTitle = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.privacy_title') 
        ? __('tools.' . $toolKey . '.privacy_title') 
        : ($locale === 'bn' ? '১০০% ক্লায়েন্ট-সাইড প্রাইভেসি গ্যারান্টি' : '100% Client-Side Privacy Guarantee');

    $privacyText = \Illuminate\Support\Facades\Lang::has('tools.' . $toolKey . '.privacy_text') 
        ? __('tools.' . $toolKey . '.privacy_text') 
        : ($locale === 'bn' ? 'আপনার সমস্ত হিসাব-নিকাশ সম্পূর্ণভাবে আপনার ব্রাউজারের মেমোরিতে সম্পন্ন হয়। কোনো ডেটা আমাদের সার্ভারে পাঠানো বা সংরক্ষণ করা হয় না।' : 'All calculations run directly in your local browser memory. Zero data is ever transmitted, logged, or stored on remote servers.');

    $registry = app(\App\Support\Tools\ToolRegistry::class);
    $relatedTool1 = $relatedSlug ? $registry->find($relatedSlug) : null;
    $relatedTool2 = $relatedSlug2 ? $registry->find($relatedSlug2) : null;
@endphp

<div class="mt-14 pt-10 border-t border-slate-200/80 dark:border-slate-800 space-y-12">
    <!-- Step-by-Step Usage Instructions -->
    @if(is_array($howToSteps) && count($howToSteps) > 0)
        <section aria-labelledby="how-to-heading">
            <h2 id="how-to-heading" class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2.5">
                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-400 text-sm font-bold">1</span>
                <span>{{ $howToTitle }}</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($howToSteps as $idx => $step)
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs relative flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold mb-3">
                                {{ $idx + 1 }}
                            </span>
                            <p class="text-sm text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                                {{ $step }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Technical Specifications & Limits Table -->
    @if(is_array($specs) && count($specs) > 0)
        <section aria-labelledby="specs-heading">
            <h2 id="specs-heading" class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2.5">
                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-400 text-sm font-bold">2</span>
                <span>{{ $specsTitle }}</span>
            </h2>
            <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="py-3 px-4 sm:px-6 w-1/3">{{ $locale === 'bn' ? 'বৈশিষ্ট্য / প্যারামিটার' : 'Specification' }}</th>
                            <th scope="col" class="py-3 px-4 sm:px-6 w-2/3">{{ $locale === 'bn' ? 'মান ও নির্দেশিকা' : 'Details & Capabilities' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @foreach($specs as $label => $value)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-3 px-4 sm:px-6 font-semibold text-slate-900 dark:text-white bg-slate-50/30 dark:bg-slate-800/30">{{ $label }}</td>
                                <td class="py-3 px-4 sm:px-6 leading-relaxed">{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <!-- Realistic Worked Examples -->
    @if(is_array($examples) && count($examples) > 0)
        <section aria-labelledby="examples-heading">
            <h2 id="examples-heading" class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2.5">
                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-400 text-sm font-bold">3</span>
                <span>{{ $examplesTitle }}</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($examples as $ex)
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs space-y-3">
                        <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 tracking-wide uppercase">
                            {{ $ex['label'] ?? 'Example' }}
                        </span>
                        <div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $locale === 'bn' ? 'ইনপুট:' : 'Input:' }}</span>
                            <p class="text-sm font-mono bg-slate-50 dark:bg-slate-950 p-2.5 rounded-lg text-slate-800 dark:text-slate-300 break-words mt-1 border border-slate-200/50 dark:border-slate-800 whitespace-pre-wrap">
                                {{ $ex['input'] }}
                            </p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $locale === 'bn' ? 'ফলাফল:' : 'Result:' }}</span>
                            <p class="text-sm font-medium text-indigo-900 dark:text-indigo-300 bg-indigo-50/60 dark:bg-indigo-950/40 p-2.5 rounded-lg break-words mt-1 border border-indigo-100 dark:border-indigo-900/50 whitespace-pre-wrap">
                                {{ $ex['output'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Security, Limitations & Privacy Guarantees -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6" aria-labelledby="trust-heading">
        <h2 id="trust-heading" class="sr-only">{{ $locale === 'bn' ? 'গোপনীয়তা ও সীমাবদ্ধতা' : 'Trust and Privacy' }}</h2>
        
        @if($limitationsTitle && $limitationsText)
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ $limitationsTitle }}</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ $limitationsText }}
                </p>
            </div>
        @endif

        @if($privacyTitle && $privacyText)
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    <span>{{ $privacyTitle }}</span>
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ $privacyText }}
                </p>
            </div>
        @endif
    </section>

    <!-- Real User FAQs -->
    @if(is_array($faq) && count($faq) > 0)
        <section aria-labelledby="faq-heading" class="pt-4">
            <h2 id="faq-heading" class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2.5">
                <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-400 text-sm font-bold">4</span>
                <span>{{ $faqTitle }}</span>
            </h2>
            <div class="space-y-4">
                @foreach($faq as $item)
                    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2 flex items-start gap-2">
                            <span class="text-indigo-600 dark:text-indigo-400 font-extrabold text-base">Q.</span>
                            <span>{{ $item['q'] }}</span>
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed pl-6">
                            {{ $item['a'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Contextual Internal Linking Recommendation Callout -->
    @if($relatedTool1)
        <aside aria-label="Related Tool Recommendation" class="p-6 rounded-2xl bg-gradient-to-r from-indigo-50/80 via-white to-sky-50/80 dark:from-slate-900 dark:via-slate-900 dark:to-indigo-950/40 border border-indigo-100/90 dark:border-slate-800 shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400">
                    {{ $locale === 'bn' ? 'প্রাসঙ্গিক অন্যান্য ইউটিলিটি' : 'Related Workflow Utility' }}
                </span>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200 leading-relaxed">
                    @if(str_contains($relatedContext, ':link'))
                        {!! str_replace(
                            [':link', ':link2'], 
                            [
                                '<a href="' . route('tools.show', ['locale' => $locale, 'slug' => $relatedTool1->slug]) . '" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline underline-offset-2 decoration-indigo-300 dark:decoration-indigo-600">' . e($relatedTool1->getName($locale)) . '</a>',
                                $relatedTool2 ? '<a href="' . route('tools.show', ['locale' => $locale, 'slug' => $relatedTool2->slug]) . '" class="font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 underline underline-offset-2 decoration-indigo-300 dark:decoration-indigo-600">' . e($relatedTool2->getName($locale)) . '</a>' : ''
                            ],
                            e($relatedContext)
                        ) !!}
                    @else
                        {{ $relatedContext }}
                    @endif
                </p>
            </div>
            <a href="{{ route('tools.show', ['locale' => $locale, 'slug' => $relatedTool1->slug]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-2xs shrink-0">
                <span>{{ $locale === 'bn' ? 'টুলটি ব্যবহার করুন' : 'Open ' . $relatedTool1->getName($locale) }}</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </aside>
    @endif
</div>
