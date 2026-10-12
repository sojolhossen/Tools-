export default function wordCounter() {
    return {
        text: '',
        copiedText: false,
        copiedStats: false,

        get words() {
            if (!this.text.trim()) return 0;
            const matches = this.text.match(/[\p{L}\p{M}\p{N}_'-]+/gu);
            return matches ? matches.length : 0;
        },

        get characters() {
            return this.text.length;
        },

        get charactersNoSpaces() {
            return this.text.replace(/\s+/gu, '').length;
        },

        get sentences() {
            const trimmed = this.text.trim();
            if (!trimmed) return 0;
            // Supports standard punctuation and Bengali dari (\u0964)
            const parts = trimmed.split(/[.!?\u0964]+(?:\s+|$)/u).filter(Boolean);
            return parts.length;
        },

        get paragraphs() {
            const trimmed = this.text.trim();
            if (!trimmed) return 0;
            const parts = trimmed.split(/\n+/).filter(Boolean);
            return parts.length;
        },

        get readingTime() {
            const w = this.words;
            if (w === 0) return '0s';
            const totalSec = Math.ceil((w / 200) * 60);
            const min = Math.floor(totalSec / 60);
            const sec = totalSec % 60;
            return min > 0 ? `${min}m ${sec}s` : `${sec}s`;
        },

        get speakingTime() {
            const w = this.words;
            if (w === 0) return '0s';
            const totalSec = Math.ceil((w / 130) * 60);
            const min = Math.floor(totalSec / 60);
            const sec = totalSec % 60;
            return min > 0 ? `${min}m ${sec}s` : `${sec}s`;
        },

        clear() {
            this.text = '';
        },

        async copyText() {
            if (!this.text) return;
            try {
                await navigator.clipboard.writeText(this.text);
                this.copiedText = true;
                setTimeout(() => { this.copiedText = false; }, 2000);
            } catch (e) {
                console.error('Clipboard copy failed', e);
            }
        },

        async copyStats() {
            const stats = `Words: ${this.words}\nCharacters: ${this.characters}\nCharacters (no spaces): ${this.charactersNoSpaces}\nSentences: ${this.sentences}\nParagraphs: ${this.paragraphs}\nReading Time: ${this.readingTime}`;
            try {
                await navigator.clipboard.writeText(stats);
                this.copiedStats = true;
                setTimeout(() => { this.copiedStats = false; }, 2000);
            } catch (e) {
                console.error('Clipboard copy failed', e);
            }
        }
    };
}
