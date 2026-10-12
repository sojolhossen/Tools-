@php
    $locale = app()->getLocale();
    $toolRegistry = app(\App\Support\Tools\ToolRegistry::class);
    $toolsList = $toolRegistry->active()->map(function ($tool) use ($locale) {
        return [
            'slug' => $tool->slug,
            'name' => $tool->getName(),
            'description' => $tool->getDescription(),
            'category' => __('common.categories.' . $tool->category),
            'url' => route('tools.show', ['locale' => $locale, 'slug' => $tool->slug]),
            'tags' => implode(' ', $tool->tags),
        ];
    })->values();
@endphp

<div
    x-data="{
        open: false,
        query: '',
        tools: {{ Js::from($toolsList) }},
        get filteredTools() {
            if (!this.query.trim()) return this.tools;
            const q = this.query.toLowerCase().trim();
            return this.tools.filter(t => 
                t.name.toLowerCase().includes(q) ||
                t.description.toLowerCase().includes(q) ||
                t.category.toLowerCase().includes(q) ||
                t.tags.toLowerCase().includes(q)
            );
        }
    }"
    @keydown.window="(($event.ctrlKey || $event.metaKey) && $event.key.toLowerCase() === 'k') ? ($event.preventDefault(), open = true) : null"
    @open-search.window="open = true; query = ''; $nextTick(() => { if ($refs.searchInput) $refs.searchInput.focus(); })"
    class="relative z-50"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('common.search.title') }}"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        x-cloak
    ></div>

    <!-- Modal Dialog -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 overflow-y-auto p-3 sm:p-6 md:p-20 flex items-start justify-center pt-12 sm:pt-20"
        x-cloak
    >
        <div class="w-full max-w-xl transform divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-slate-900/10 dark:ring-slate-800 transition-all">
            <!-- Search Header & Input -->
            <div class="relative flex items-center px-4 py-1">
                <svg class="pointer-events-none w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input
                    type="text"
                    x-model="query"
                    x-ref="searchInput"
                    class="h-14 w-full border-0 bg-transparent pl-3 pr-4 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:ring-0 text-sm sm:text-base focus:outline-none"
                    placeholder="{{ __('common.search.placeholder') }}"
                    @keydown.escape.window="open = false"
                >
                <button
                    type="button"
                    @click="open = false"
                    class="rounded-xl p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-colors shrink-0"
                    aria-label="{{ __('common.actions.close') }}"
                >
                    <svg class="w-5 h-5 sm:hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    <span class="hidden sm:inline-block text-xs px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-mono">ESC</span>
                </button>
            </div>

            <!-- Results List -->
            <ul class="max-h-96 scroll-py-2 overflow-y-auto p-2 text-sm text-slate-700 dark:text-slate-300" role="listbox">
                <template x-for="tool in filteredTools" :key="tool.slug">
                    <li class="cursor-pointer select-none rounded-xl p-3 hover:bg-indigo-50 dark:hover:bg-slate-800/80 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">
                        <a :href="tool.url" class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-slate-900 dark:text-white" x-text="tool.name"></div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1" x-text="tool.description"></div>
                            </div>
                            <span class="ml-3 shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-[11px] font-medium text-slate-600 dark:text-slate-400" x-text="tool.category"></span>
                        </a>
                    </li>
                </template>

                <div x-show="filteredTools.length === 0" class="p-6 text-center text-sm text-slate-500 dark:text-slate-400">
                    {{ __('common.search.no_results') }}
                </div>
            </ul>
        </div>
    </div>
</div>
