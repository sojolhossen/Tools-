<x-layouts.app
    :title="$tool->getMetaTitle()"
    :description="$tool->getMetaDescription()"
    :schema="$schema ?? null"
>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-breadcrumbs :items="[
        __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
        __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
        $tool->getName() => null,
    ]" />

    <div class="mt-6 mb-8 text-center">
        <div class="inline-flex p-3 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-purple-500/10 text-indigo-600 dark:text-indigo-400 mb-3 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl tracking-tight">{{ $tool->getName() }}</h1>
        <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
            {{ $tool->getDescription() }}
        </p>
        <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            {{ __('common.badges.browser_based') }}
        </div>
    </div>

    
    <div x-data="{
        distance: 350,
        mileage: 14,
        pricePerLiter: 125,
        fuelNeeded: 0,
        totalCost: 0,
        costPerKm: 0,
        calculate() {
            let dist = parseFloat(this.distance) || 0;
            let mil = parseFloat(this.mileage) || 1;
            let price = parseFloat(this.pricePerLiter) || 0;

            if (dist > 0 && mil > 0) {
                let fuel = dist / mil;
                let cost = fuel * price;
                this.fuelNeeded = fuel.toFixed(2);
                this.totalCost = Math.round(cost);
                this.costPerKm = (cost / dist).toFixed(2);
            } else {
                this.fuelNeeded = 0;
                this.totalCost = 0;
                this.costPerKm = 0;
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Trip Distance (km / miles)</label>
                    <input type="number" step="0.1" x-model="distance" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fuel Economy / Mileage (km/L or MPG)</label>
                    <input type="number" step="0.1" x-model="mileage" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Fuel Price per Liter / Gallon</label>
                    <input type="number" step="0.1" x-model="pricePerLiter" @input="calculate()" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col justify-between p-6 rounded-2xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50">
                <div class="text-center">
                    <span class="text-xs uppercase tracking-wider font-semibold text-amber-700 dark:text-amber-400">Total Estimated Trip Cost</span>
                    <div class="text-5xl font-black text-amber-600 dark:text-amber-400 my-3" x-text="totalCost.toLocaleString()"></div>
                </div>
                <div class="space-y-3 pt-6 border-t border-amber-200 dark:border-amber-800 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Fuel Needed:</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="fuelNeeded + ' Units'"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 dark:text-slate-400">Cost per km / mile:</span>
                        <span class="font-bold text-slate-900 dark:text-white" x-text="costPerKm"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
