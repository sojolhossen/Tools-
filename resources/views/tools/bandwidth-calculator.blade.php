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
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
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
        fileSize: 10,
        fileUnit: 'GB',
        speed: 100,
        speedUnit: 'Mbps',
        timeFormatted: '',
        calculate() {
            let sz = parseFloat(this.fileSize) || 0;
            let sp = parseFloat(this.speed) || 0;

            // Convert size to Megabytes
            let sizeMb = sz;
            if (this.fileUnit === 'KB') sizeMb = sz / 1024;
            else if (this.fileUnit === 'GB') sizeMb = sz * 1024;
            else if (this.fileUnit === 'TB') sizeMb = sz * 1024 * 1024;

            // Convert speed to Megabytes per sec (MB/s)
            let speedMbPerSec = sp;
            if (this.speedUnit === 'Kbps') speedMbPerSec = (sp / 8) / 1024;
            else if (this.speedUnit === 'Mbps') speedMbPerSec = (sp / 8);
            else if (this.speedUnit === 'Gbps') speedMbPerSec = (sp / 8) * 1024;
            else if (this.speedUnit === 'MB/s') speedMbPerSec = sp;

            if (speedMbPerSec > 0 && sizeMb > 0) {
                let sec = sizeMb / speedMbPerSec;
                let hrs = Math.floor(sec / 3600);
                let mins = Math.floor((sec % 3600) / 60);
                let remainingSec = Math.round(sec % 60);

                let parts = [];
                if (hrs > 0) parts.push(hrs + ' hours');
                if (mins > 0) parts.push(mins + ' minutes');
                parts.push(remainingSec + ' seconds');
                this.timeFormatted = parts.join(' ');
            } else {
                this.timeFormatted = '--';
            }
        }
    }" x-init="calculate()" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">File / Data Size</label>
                    <div class="flex gap-2">
                        <input type="number" step="0.1" x-model="fileSize" @input="calculate()" class="w-2/3 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <select x-model="fileUnit" @change="calculate()" class="w-1/3 px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="MB">MB</option>
                            <option value="GB">GB</option>
                            <option value="TB">TB</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Internet Speed / Bandwidth</label>
                    <div class="flex gap-2">
                        <input type="number" step="1" x-model="speed" @input="calculate()" class="w-2/3 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                        <select x-model="speedUnit" @change="calculate()" class="w-1/3 px-3 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="Mbps">Mbps (Megabits)</option>
                            <option value="MB/s">MB/s (Megabytes)</option>
                            <option value="Gbps">Gbps</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center items-center p-6 rounded-2xl bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-200 dark:border-indigo-900/50 text-center">
                <span class="text-xs uppercase tracking-wider font-semibold text-indigo-700 dark:text-indigo-400">Estimated Download Time</span>
                <span class="text-3xl sm:text-4xl font-black text-indigo-600 dark:text-indigo-400 my-3" x-text="timeFormatted"></span>
                <span class="text-xs text-slate-500">Based on theoretical 100% stable connection speed.</span>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="percentage-calculator" related-slug2="simple-calculator" />
    </div>
</div>
</x-layouts.app>
