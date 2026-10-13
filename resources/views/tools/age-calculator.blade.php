<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="ageCalc()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <x-icon :name="$tool->icon" class="w-3.5 h-3.5" />
                    <span>{{ __('common.categories.' . $tool->category) }}</span>
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ __('common.badges.browser_based') }}</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $tool->getName() }}</h1>
            <p class="mt-2 text-base text-slate-600 dark:text-slate-400 max-w-3xl">{{ $tool->getDescription() }}</p>
        </div>

        <div class="max-w-xl mx-auto mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Date of Birth</label>
                    <input type="date" x-model="dob" @change="calc()" class="w-full text-sm font-medium p-3 border rounded-xl bg-slate-50 dark:bg-slate-950">
                </div>

                <div class="p-6 bg-emerald-50 dark:bg-emerald-950/60 rounded-2xl border border-emerald-100 dark:border-emerald-800 text-center">
                    <span class="text-xs uppercase font-bold text-emerald-800 dark:text-emerald-300 block mb-1">Your Exact Age</span>
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 tracking-tight" x-text="ageStr"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-center">
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Months</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-slate-200 block" x-text="totalMonths"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Days</span>
                        <span class="text-lg font-bold text-slate-800 dark:text-slate-200 block" x-text="totalDays"></span>
                    </div>
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800 col-span-2 sm:col-span-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Next Birthday</span>
                        <span class="text-sm font-bold text-emerald-600 block" x-text="nextBirthday"></span>
                    </div>
                </div>
            </div>
        </div>

        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="date-difference-calculator" related-slug2="percentage-calculator" />
    </div>
</x-layouts.app>
<script>
function ageCalc() {
    return {
        dob: '2000-01-01', ageStr: '26 Years, 3 Months, 12 Days', totalMonths: '315', totalDays: '9600', nextBirthday: 'in 258 days',
        init() { this.calc(); },
        calc() {
            if (!this.dob) return;
            const b = new Date(this.dob), now = new Date();
            let years = now.getFullYear() - b.getFullYear();
            let months = now.getMonth() - b.getMonth();
            let days = now.getDate() - b.getDate();
            if (days < 0) {
                months--;
                days += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
            }
            if (months < 0) { years--; months += 12; }
            this.ageStr = `${years} Years, ${months} Months, ${days} Days`;
            this.totalMonths = years * 12 + months;
            this.totalDays = Math.floor((now - b) / (1000 * 60 * 60 * 24));

            let nextB = new Date(now.getFullYear(), b.getMonth(), b.getDate());
            if (nextB < now) nextB = new Date(now.getFullYear() + 1, b.getMonth(), b.getDate());
            const daysTo = Math.ceil((nextB - now) / (1000 * 60 * 60 * 24));
            this.nextBirthday = `in ${daysTo} days`;
        }
    };
}
</script>