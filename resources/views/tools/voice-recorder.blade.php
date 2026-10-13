<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="voiceRecorder()">
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
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 text-center space-y-6">
                <!-- Record Visualizer Circle -->
                <div class="relative w-28 h-28 mx-auto flex items-center justify-center">
                    <div :class="isRecording ? 'animate-ping bg-rose-400/30' : 'bg-transparent'" class="absolute inset-0 rounded-full"></div>
                    <button type="button" @click="toggleRecord()" :class="isRecording ? 'bg-rose-600 text-white' : 'bg-violet-600 text-white hover:bg-violet-700'" class="w-20 h-20 rounded-full flex items-center justify-center shadow-lg transition-transform active:scale-95">
                        <x-icon name="microphone" class="w-10 h-10" />
                    </button>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white" x-text="isRecording ? 'Recording Audio...' : 'Click Microphone to Start Recording'"></h3>
                    <p class="text-2xl font-mono font-bold text-violet-600 mt-1" x-text="formatTime(seconds)"></p>
                </div>

                <template x-if="audioUrl">
                    <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 space-y-4 max-w-lg mx-auto">
                        <audio :src="audioUrl" controls class="w-full"></audio>
                        <a :href="audioUrl" download="voice_recording.webm" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs inline-flex items-center gap-2">
                            <x-icon name="arrow-down-tray" class="w-4 h-4" /> Download Audio Recording
                        </a>
                    </div>
                </template>
            </div>
        </div>
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" related-slug="image-compressor" related-slug2="image-resizer" />
    </div>
</x-layouts.app>
<script>
function voiceRecorder() {
    return {
        isRecording: false, seconds: 0, timer: null, audioUrl: '', mediaRecorder: null, chunks: [],
        async toggleRecord() {
            if (this.isRecording) {
                this.stop();
            } else {
                await this.start();
            }
        },
        async start() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(stream);
                this.chunks = [];
                this.mediaRecorder.ondataavailable = (e) => this.chunks.push(e.data);
                this.mediaRecorder.onstop = () => {
                    const blob = new Blob(this.chunks, { type: 'audio/webm' });
                    this.audioUrl = URL.createObjectURL(blob);
                };
                this.mediaRecorder.start();
                this.isRecording = true;
                this.seconds = 0;
                this.timer = setInterval(() => this.seconds++, 1000);
            } catch (err) {
                alert('Microphone permission denied or not available.');
            }
        },
        stop() {
            if (this.mediaRecorder) this.mediaRecorder.stop();
            this.isRecording = false;
            clearInterval(this.timer);
        },
        formatTime(s) {
            const m = Math.floor(s / 60).toString().padStart(2, '0');
            const sec = (s % 60).toString().padStart(2, '0');
            return `${m}:${sec}`;
        }
    };
}
</script>