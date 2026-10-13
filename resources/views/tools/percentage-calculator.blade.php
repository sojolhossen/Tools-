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
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300">
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
        <div x-data="percentageCalculator()" class="space-y-6">
            <!-- Mode Tabs -->
            <div class="flex border-b border-slate-200 dark:border-slate-700 gap-2 overflow-x-auto scrollbar-none">
                <button
                    type="button"
                    @click="tab = 'simple'"
                    :class="tab === 'simple' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:text-slate-100 hover:border-slate-300'"
                    class="px-4 py-3 border-b-2 font-bold text-xs sm:text-sm whitespace-nowrap transition-all focus:outline-none"
                >
                    {{ __('tools.percentage_calculator.tabs.simple') }}
                </button>

                <button
                    type="button"
                    @click="tab = 'change'"
                    :class="tab === 'change' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:text-slate-100 hover:border-slate-300'"
                    class="px-4 py-3 border-b-2 font-bold text-xs sm:text-sm whitespace-nowrap transition-all focus:outline-none"
                >
                    {{ __('tools.percentage_calculator.tabs.change') }}
                </button>

                <button
                    type="button"
                    @click="tab = 'discount'"
                    :class="tab === 'discount' ? 'border-indigo-600 text-indigo-600 bg-indigo-50/50' : 'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:text-slate-100 hover:border-slate-300'"
                    class="px-4 py-3 border-b-2 font-bold text-xs sm:text-sm whitespace-nowrap transition-all focus:outline-none"
                >
                    {{ __('tools.percentage_calculator.tabs.discount') }}
                </button>
            </div>

            <!-- TAB 1: Simple Percentage -->
            <div x-show="tab === 'simple'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                    {{ __('tools.percentage_calculator.simple.title') }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="simple-pct" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.simple.pct_label') }}
                        </label>
                        <div class="relative">
                            <input
                                id="simple-pct"
                                type="number"
                                step="any"
                                x-model="simplePct"
                                class="w-full pl-4 pr-8 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                            >
                            <span class="absolute right-3.5 top-3.5 text-sm font-bold text-slate-400">%</span>
                        </div>
                    </div>

                    <div>
                        <label for="simple-val" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.simple.val_label') }}
                        </label>
                        <input
                            id="simple-val"
                            type="number"
                            step="any"
                            x-model="simpleVal"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                        >
                    </div>
                </div>

                <!-- Result Box -->
                <div class="p-6 rounded-2xl bg-indigo-50/60 border border-indigo-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                            {{ __('tools.percentage_calculator.simple.result_label') }}
                        </span>
                        <div class="text-3xl sm:text-4xl font-black text-indigo-700 mt-1" x-text="simpleResult">0</div>
                    </div>

                    <div class="text-xs text-indigo-900/70 bg-white/80 p-3 rounded-xl border border-indigo-100 max-w-sm">
                        <span class="font-semibold">{{ __('tools.percentage_calculator.simple.formula') }}</span>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Percentage Increase / Decrease -->
            <div x-show="tab === 'change'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6" x-cloak>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                    {{ __('tools.percentage_calculator.change.title') }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="change-from" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.change.initial_label') }}
                        </label>
                        <input
                            id="change-from"
                            type="number"
                            step="any"
                            x-model="changeFrom"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                        >
                    </div>

                    <div>
                        <label for="change-to" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.change.final_label') }}
                        </label>
                        <input
                            id="change-to"
                            type="number"
                            step="any"
                            x-model="changeTo"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                        >
                    </div>
                </div>

                <!-- Division by zero warning -->
                <template x-if="changeResult.error">
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                        <span x-text="changeResult.error"></span>
                    </div>
                </template>

                <!-- Result Box -->
                <div x-show="!changeResult.error" class="p-6 rounded-2xl bg-indigo-50/60 border border-indigo-100 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                                {{ __('tools.percentage_calculator.change.result_label') }}
                            </span>
                            <div class="text-3xl sm:text-4xl font-black mt-1 flex items-center gap-2" :class="changeResult.direction === 'increase' ? 'text-emerald-600' : (changeResult.direction === 'decrease' ? 'text-rose-600' : 'text-slate-800')">
                                <span x-text="changeResult.change + '%'">0%</span>
                                <span class="text-sm font-semibold px-2.5 py-0.5 rounded-full" :class="changeResult.direction === 'increase' ? 'bg-emerald-100 text-emerald-800' : (changeResult.direction === 'decrease' ? 'bg-rose-100 text-rose-800' : 'bg-slate-200 text-slate-700 dark:text-slate-300')" x-text="changeResult.direction === 'increase' ? '{{ __('tools.percentage_calculator.change.direction_increase') }}' : (changeResult.direction === 'decrease' ? '{{ __('tools.percentage_calculator.change.direction_decrease') }}' : '{{ __('tools.percentage_calculator.change.direction_none') }}')"></span>
                            </div>
                        </div>

                        <div class="text-xs text-indigo-900/70 bg-white/80 p-3 rounded-xl border border-indigo-100 max-w-sm">
                            <span class="font-semibold">{{ __('tools.percentage_calculator.change.formula') }}</span>
                        </div>
                    </div>

                    <div class="text-xs text-slate-600 dark:text-slate-400 pt-2 border-t border-indigo-100">
                        {{ __('tools.percentage_calculator.change.difference_label') }}: <strong class="text-slate-900 dark:text-slate-100 font-semibold" x-text="changeResult.diff">0</strong>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Discount Calculator -->
            <div x-show="tab === 'discount'" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-700/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6" x-cloak>
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">
                    {{ __('tools.percentage_calculator.discount.title') }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="disc-price" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.discount.price_label') }}
                        </label>
                        <input
                            id="disc-price"
                            type="number"
                            step="any"
                            x-model="discPrice"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                        >
                    </div>

                    <div>
                        <label for="disc-pct" class="block text-xs font-bold uppercase text-slate-500 mb-1.5">
                            {{ __('tools.percentage_calculator.discount.discount_label') }}
                        </label>
                        <div class="relative">
                            <input
                                id="disc-pct"
                                type="number"
                                step="any"
                                x-model="discPct"
                                class="w-full pl-4 pr-8 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-600 text-base"
                            >
                            <span class="absolute right-3.5 top-3.5 text-sm font-bold text-slate-400">%</span>
                        </div>
                    </div>
                </div>

                <!-- Result Box -->
                <div class="p-6 rounded-2xl bg-emerald-50/70 border border-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-800">
                            {{ __('tools.percentage_calculator.discount.final_price') }}
                        </span>
                        <div class="text-3xl sm:text-4xl font-black text-emerald-700 mt-1" x-text="discountFinal">0</div>
                        <div class="mt-2 text-xs text-emerald-800 font-semibold">
                            {{ __('tools.percentage_calculator.discount.savings') }}: <strong class="text-emerald-900" x-text="discountSavings">0</strong>
                        </div>
                    </div>

                    <div class="text-xs text-emerald-900/70 bg-white/80 p-3 rounded-xl border border-emerald-100 max-w-sm">
                        <span class="font-semibold">{{ __('tools.percentage_calculator.discount.formula') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ad Slot -->
        <x-ad-slot slot="tool-bottom" />

        <!-- Educational & Informational Guide (SEO Optimized) -->
        <x-tool-guide tool-key="percentage_calculator" related-slug="qr-generator" related-slug2="word-counter" />

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
