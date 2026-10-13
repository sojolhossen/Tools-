<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="metaTagGenApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-900/50">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {{ $tool->getName() }}
            </h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">
                {{ $tool->getDescription() }}
            </p>
        </div>


        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Inputs Column -->
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            <span>Page Title</span>
                            <span :class="title.length > 60 ? 'text-rose-500' : 'text-slate-400'" x-text="title.length + '/60'"></span>
                        </div>
                        <input type="text" x-model="title" @input="generate()" placeholder="High Quality Keyword Rich Title" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            <span>Meta Description</span>
                            <span :class="desc.length > 160 ? 'text-rose-500' : 'text-slate-400'" x-text="desc.length + '/160'"></span>
                        </div>
                        <textarea x-model="desc" @input="generate()" rows="3" placeholder="Engaging summary of page content for search engines" class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Robots</label>
                            <select x-model="robots" @change="generate()" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                                <option value="index, follow">index, follow</option>
                                <option value="noindex, follow">noindex, follow</option>
                                <option value="noindex, nofollow">noindex, nofollow</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Canonical URL</label>
                            <input type="text" x-model="canonical" @input="generate()" placeholder="https://example.com/page" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-xs">
                        </div>
                    </div>
                </div>

                <!-- Live Google SERP Snippet Preview -->
                <div class="space-y-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block">Google Search Preview</span>
                    <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 space-y-1">
                        <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                            <span class="w-4 h-4 rounded-full bg-slate-200 flex items-center justify-center text-[10px]">🌐</span>
                            <span class="truncate" x-text="canonical || 'https://example.com'"></span>
                        </div>
                        <h4 class="text-base text-blue-800 dark:text-blue-400 font-medium hover:underline cursor-pointer line-clamp-1" x-text="title || 'Page Title Goes Here'"></h4>
                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2" x-text="desc || 'Your meta description will appear here in Google search engine results.'"></p>
                    </div>

                    <!-- Meta Tags Code -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-500 uppercase">Generated HTML</span>
                            <button type="button" @click="copy(output)" class="px-2.5 py-1 text-xs rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Copy HTML</button>
                        </div>
                        <pre class="p-3 rounded-xl bg-slate-950 text-slate-200 font-mono text-[11px] h-36 overflow-y-auto border border-slate-800" x-text="output"></pre>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function metaTagGenApp() {
            return {
                title: 'ToolZeno — Free Developer & Webmaster Online Utilities',
                desc: 'Collection of 100+ free online webmaster, developer, and security utilities running completely in your browser.',
                robots: 'index, follow',
                canonical: 'https://toolzeno.site',
                output: '',
                init() {
                    this.generate();
                },
                generate() {
                    const lines = [
                        '<meta charset="UTF-8">',
                        '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
                        '<title>' + (this.title || '') + '</title>',
                        '<meta name="description" content="' + (this.desc || '') + '">',
                        '<meta name="robots" content="' + this.robots + '">'
                    ];
                    if (this.canonical.trim()) {
                        lines.push('<link rel="canonical" href="' + this.canonical.trim() + '">');
                    }
                    this.output = lines.join('\n');
                },
                copy(t) { if (navigator.clipboard) navigator.clipboard.writeText(t); }
            };
        }
        </script>


        <!-- Informational / SEO Section -->
        <div class="mt-12 space-y-8 border-t border-slate-200 dark:border-slate-800 pt-8">
            @if(!empty(__('tools.' . $tool->slug . '.how_to_guide.steps')))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('tools.' . $tool->slug . '.how_to_guide.title') }}
                </h2>
                <ol class="space-y-3 list-decimal list-inside text-sm text-slate-600 dark:text-slate-300">
                    @foreach(__('tools.' . $tool->slug . '.how_to_guide.steps') as $step)
                        <li class="leading-relaxed">{{ $step }}</li>
                    @endforeach
                </ol>
            </div>
            @endif

            @if(!empty(__('tools.' . $tool->slug . '.technical_specs.items')))
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ __('tools.' . $tool->slug . '.technical_specs.title') }}
                </h2>
                <ul class="space-y-2 list-disc list-inside text-sm text-slate-600 dark:text-slate-300">
                    @foreach(__('tools.' . $tool->slug . '.technical_specs.items') as $item)
                        <li class="leading-relaxed">{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty(__('tools.' . $tool->slug . '.faqs')))
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                    {{ __('common.faq.title') ?? 'Frequently Asked Questions' }}
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    @foreach(__('tools.' . $tool->slug . '.faqs') as $faq)
                    <div class="bg-white dark:bg-slate-900 rounded-xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
                        <h3 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-2">{{ $faq['question'] }}</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
