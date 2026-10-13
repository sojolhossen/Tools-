<x-layouts.app :title="$tool->getMetaTitle()" :description="$tool->getMetaDescription()" :schema="$schema ?? null">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="morseApp()">
        <x-breadcrumbs :items="[
            __('common.nav.tools') => route('tools.index', ['locale' => $locale]),
            __('common.categories.' . $tool->category) => route('tools.category', ['locale' => $locale, 'category' => $tool->category]),
            $tool->getName() => null,
        ]" />

        <div class="mt-6 mb-8 text-center sm:text-left">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-900/50">
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

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6 mb-10">
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Text Message</label>
                        <button type="button" @click="text = 'SOS DISTRESS SIGNAL'; textToMorse();" class="text-xs text-rose-600 dark:text-rose-400 hover:underline">Sample (SOS)</button>
                    </div>
                    <textarea x-model="text" @input="textToMorse()" rows="3" placeholder="Type plain text to translate to Morse..." class="w-full font-mono text-sm bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-slate-900 dark:text-slate-100 focus:outline-none"></textarea>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Morse Code (. and -)</label>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="playAudio()" class="px-3 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                <span>Play Audio Beep</span>
                            </button>
                            <button type="button" @click="copy(morse)" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-bold">Copy</button>
                        </div>
                    </div>
                    <textarea x-model="morse" @input="morseToText()" rows="3" placeholder="Type Morse code (e.g. ... --- ...) to translate to text..." class="w-full font-mono text-base font-bold bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl p-3 text-rose-600 dark:text-rose-400 tracking-widest focus:outline-none"></textarea>
                </div>
            </div>

        </div>

        <x-ad-slot slot="tool-bottom" />
        <x-tool-guide :tool-key="str_replace('-', '_', $tool->slug)" />
    </div>

    @push('scripts')
    <script>
        function morseApp() {
            return {
                text: 'SOS DISTRESS SIGNAL',
                morse: '... --- ...   -.. .. ... - .-. . ... ...   ... .. --. -. .- .-..',
                audioCtx: null,

                morseMap: {
                    'A': '.-', 'B': '-...', 'C': '-.-.', 'D': '-..', 'E': '.', 'F': '..-.',
                    'G': '--.', 'H': '....', 'I': '..', 'J': '.---', 'K': '-.-', 'L': '.-..',
                    'M': '--', 'N': '-.', 'O': '---', 'P': '.--.', 'Q': '--.-', 'R': '.-.',
                    'S': '...', 'T': '-', 'U': '..-', 'V': '...-', 'W': '.--', 'X': '-..-',
                    'Y': '-.--', 'Z': '--..', '0': '-----', '1': '.----', '2': '..---',
                    '3': '...--', '4': '....-', '5': '.....', '6': '-....', '7': '--...',
                    '8': '---..', '9': '----.', ' ': '/'
                },

                textToMorse() {
                    const upper = this.text.toUpperCase();
                    let res = [];
                    for (let i = 0; i < upper.length; i++) {
                        const ch = upper[i];
                        res.push(this.morseMap[ch] || '');
                    }
                    this.morse = res.join(' ');
                },

                morseToText() {
                    const rev = {};
                    for (let k in this.morseMap) rev[this.morseMap[k]] = k;
                    const tokens = this.morse.trim().split(/\s+/);
                    this.text = tokens.map(t => rev[t] || (t === '/' ? ' ' : '')).join('');
                },

                async playAudio() {
                    if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const ctx = this.audioCtx;
                    const unit = 0.08;
                    let time = ctx.currentTime + 0.1;

                    for (let i = 0; i < this.morse.length; i++) {
                        const symbol = this.morse[i];
                        if (symbol === '.' || symbol === '-') {
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(600, time);
                            osc.connect(gain);
                            gain.connect(ctx.destination);

                            const duration = (symbol === '.') ? unit : (unit * 3);
                            osc.start(time);
                            osc.stop(time + duration);
                            time += duration + unit;
                        } else if (symbol === ' ') {
                            time += unit * 2;
                        }
                    }
                },

                copy(val) {
                    navigator.clipboard.writeText(val);
                }
            }
        }
    </script>

    @endpush
</x-layouts.app>
