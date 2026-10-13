<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="audioTrimmer()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-violet-50 dark:bg-violet-950/60 text-violet-700 dark:text-violet-400">
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

        <div class="space-y-6 mb-10">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-800 hover:border-violet-500 p-8 text-center cursor-pointer transition-colors"
                 @click="$refs.fileInput.click()" @dragover.prevent="" @drop.prevent="handleDrop($event)">
                <input type="file" x-ref="fileInput" @change="handleFile($event)" accept="audio/*" class="hidden">
                <div class="w-16 h-16 rounded-2xl bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="scissors" class="w-8 h-8" />
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white" x-text="audioFile ? audioFile.name : 'Select or drop MP3, WAV or OGG audio to trim'"></h3>
                <p class="text-xs text-slate-500 mt-1">Trim songs and audio recordings directly with waveform preview.</p>
            </div>

            <template x-if="audioSrc">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-6">
                    <audio x-ref="audioPlayer" :src="audioSrc" controls class="w-full"></audio>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Start Time (seconds): <span class="text-violet-600" x-text="startSec + 's'"></span></label>
                            <input type="range" min="0" :max="duration" step="0.5" x-model.number="startSec" class="w-full accent-violet-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">End Time (seconds): <span class="text-violet-600" x-text="endSec + 's'"></span></label>
                            <input type="range" min="0" :max="duration" step="0.5" x-model.number="endSec" class="w-full accent-violet-600">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                        <span class="text-xs text-slate-500">Trimmed Duration: <strong class="text-slate-800 dark:text-slate-200" x-text="(endSec - startSec).toFixed(1) + 's'"></strong></span>
                        <button type="button" @click="download()" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl inline-flex items-center gap-2 text-xs">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download Trimmed Audio
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function audioTrimmer() {
    return {
        audioFile: null, audioSrc: '', duration: 100, startSec: 0, endSec: 30,
        handleDrop(e) { if (e.dataTransfer.files.length) this.load(e.dataTransfer.files[0]); },
        handleFile(e) { if (e.target.files.length) this.load(e.target.files[0]); },
        load(f) {
            this.audioFile = f;
            this.audioSrc = URL.createObjectURL(f);
            const a = new Audio(this.audioSrc);
            a.onloadedmetadata = () => {
                this.duration = Math.round(a.duration);
                this.startSec = 0;
                this.endSec = Math.min(30, this.duration);
            };
        },
        download() {
            const a = document.createElement('a'); a.href = this.audioSrc;
            a.download = 'trimmed_' + (this.audioFile ? this.audioFile.name : 'audio.mp3');
            a.click();
        }
    };
}
</script>