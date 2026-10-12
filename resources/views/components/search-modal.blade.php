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
    @keydown.window.prevent.cmd.k="open = true"
    @keydown.window.prevent.ctrl.k="open = true"
    @open-search.window="open = true"
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
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        x-cloak
    ></div>

    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20"
        x-cloak
    >
        <div class="mx-auto max-w-xl transform divide-y divide-slate-100 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10 transition-all">
            <div class="relative flex items-center px-4">
                <svg class="pointer-events-none w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input
                    type="text"
                    x-model="query"
                    x-ref="searchInput"
                    x-effect="if (open) $nextTick(() => $refs.searchInput.focus())"
                    class="h-14 w-full border-0 bg-transparent pl-3 pr-4 text-slate-900 placeholder:text-slate-400 focus:ring-0 sm:text-sm focus:outline-none"
                    placeholder="{{ __('common.search.placeholder') }}"
                    @keydown.escape.window="open = false"
                >
                <button
                    type="button"
                    @click="open = false"
                    class="rounded-lg p-1 text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-600"
                    aria-label="{{ __('common.actions.close') }}"
                >
                    <span class="text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-mono">ESC</span>
                </button>
            </div>

            <ul class="max-h-96 scroll-py-2 overflow-y-auto p-2 text-sm text-slate-700" role="listbox">
                <template x-for="tool in filteredTools" :key="tool.slug">
                    <li class="cursor-pointer select-none rounded-xl p-3 hover:bg-indigo-50 hover:text-indigo-900 transition-colors">
                        <a :href="tool.url" class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-slate-900" x-text="tool.name"></div>
                                <div class="text-xs text-slate-500 mt-0.5 line-clamp-1" x-text="tool.description"></div>
                            </div>
                            <span class="ml-3 shrink-0 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-medium text-slate-600" x-text="tool.category"></span>
                        </a>
                    </li>
                </template>

                <div x-show="filteredTools.length === 0" class="p-6 text-center text-sm text-slate-500">
                    {{ __('common.search.no_results') }}
                </div>
            </ul>
        </div>
    </div>
</div>
