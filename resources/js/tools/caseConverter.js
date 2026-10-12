export default function caseConverter() {
    return {
        input: '',
        output: '',
        activeMode: '',
        copied: false,

        toUpper() {
            this.activeMode = 'upper';
            this.output = this.input.toUpperCase();
        },

        toLower() {
            this.activeMode = 'lower';
            this.output = this.input.toLowerCase();
        },

        toTitle() {
            this.activeMode = 'title';
            this.output = this.input.toLowerCase().replace(/(^|\s)\S/g, (char) => char.toUpperCase());
        },

        toSentence() {
            this.activeMode = 'sentence';
            const lower = this.input.toLowerCase();
            this.output = lower.replace(/(^|[.!?\u0964]\s+)(\S)/gu, (match, prefix, char) => {
                return prefix + char.toUpperCase();
            });
        },

        toCamel() {
            this.activeMode = 'camel';
            const words = this.input.trim().split(/[_\-\s]+/u).filter(Boolean);
            if (!words.length) {
                this.output = '';
                return;
            }
            const first = words.shift().toLowerCase();
            const rest = words.map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase());
            this.output = first + rest.join('');
        },

        toSnake() {
            this.activeMode = 'snake';
            this.output = this.input.trim()
                .replace(/([a-z])([A-Z])/g, '$1_$2')
                .replace(/[_\-\s]+/g, '_')
                .toLowerCase();
        },

        toKebab() {
            this.activeMode = 'kebab';
            this.output = this.input.trim()
                .replace(/([a-z])([A-Z])/g, '$1-$2')
                .replace(/[_\-\s]+/g, '-')
                .toLowerCase();
        },

        clear() {
            this.input = '';
            this.output = '';
            this.activeMode = '';
        },

        async copy() {
            const target = this.output || this.input;
            if (!target) return;
            try {
                await navigator.clipboard.writeText(target);
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            } catch (e) {
                console.error('Clipboard copy failed', e);
            }
        }
    };
}
