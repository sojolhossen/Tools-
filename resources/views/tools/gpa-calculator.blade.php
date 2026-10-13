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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
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
        courses: [
            { name: 'Course 1', credits: 3, grade: '4.00' },
            { name: 'Course 2', credits: 3, grade: '3.75' },
            { name: 'Course 3', credits: 4, grade: '3.50' },
            { name: 'Course 4', credits: 3, grade: '4.00' }
        ],
        gpa: 0,
        totalCredits: 0,
        addCourse() {
            this.courses.push({ name: 'Course ' + (this.courses.length + 1), credits: 3, grade: '4.00' });
            this.calculate();
        },
        removeCourse(index) {
            if (this.courses.length > 1) {
                this.courses.splice(index, 1);
                this.calculate();
            }
        },
        calculate() {
            let totalPts = 0;
            let credits = 0;
            this.courses.forEach(c => {
                let cr = parseFloat(c.credits) || 0;
                let gr = parseFloat(c.grade) || 0;
                totalPts += (cr * gr);
                credits += cr;
            });
            this.totalCredits = credits;
            this.gpa = credits > 0 ? (totalPts / credits).toFixed(2) : 0;
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="space-y-4">
            <template x-for="(course, index) in courses" :key="index">
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200 dark:border-slate-600">
                    <input type="text" x-model="course.name" placeholder="Course Name" class="flex-1 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    <div class="w-28">
                        <input type="number" step="0.5" x-model="course.credits" @input="calculate()" placeholder="Credits" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                    </div>
                    <div class="w-36">
                        <select x-model="course.grade" @change="calculate()" class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white text-sm">
                            <option value="4.00">A / A+ (4.00)</option>
                            <option value="3.75">A- (3.75)</option>
                            <option value="3.50">B+ (3.50)</option>
                            <option value="3.25">B (3.25)</option>
                            <option value="3.00">B- (3.00)</option>
                            <option value="2.75">C+ (2.75)</option>
                            <option value="2.50">C (2.50)</option>
                            <option value="2.25">D (2.25)</option>
                            <option value="2.00">D- (2.00)</option>
                            <option value="0.00">F (0.00)</option>
                        </select>
                    </div>
                    <button type="button" @click="removeCourse(index)" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </template>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <button type="button" @click="addCourse()" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 font-semibold text-sm rounded-xl hover:bg-indigo-100 transition">
                + Add Another Course
            </button>
        </div>

        <div class="mt-8 p-6 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 border border-indigo-200 dark:border-indigo-900 flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4">
            <div>
                <span class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-bold">Total Credits</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white" x-text="totalCredits"></div>
            </div>
            <div class="text-center sm:text-right">
                <span class="text-xs uppercase tracking-wider text-indigo-600 dark:text-indigo-400 font-bold">Calculated GPA / CGPA</span>
                <div class="text-5xl font-black text-indigo-600 dark:text-indigo-400" x-text="gpa"></div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
