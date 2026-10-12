export default function jsonFormatter() {
    return {
        input: '',
        indent: 2,
        isValid: null,
        errorMessage: '',
        copied: false,

        format() {
            if (!this.input.trim()) {
                this.isValid = null;
                this.errorMessage = '';
                return;
            }

            try {
                const parsed = JSON.parse(this.input);
                const space = this.indent === 'tab' ? '\t' : Number(this.indent);
                this.input = JSON.stringify(parsed, null, space);
                this.isValid = true;
                this.errorMessage = '';
            } catch (err) {
                this.isValid = false;
                this.errorMessage = err.message || 'Invalid JSON syntax';
            }
        },

        minify() {
            if (!this.input.trim()) return;

            try {
                const parsed = JSON.parse(this.input);
                this.input = JSON.stringify(parsed);
                this.isValid = true;
                this.errorMessage = '';
            } catch (err) {
                this.isValid = false;
                this.errorMessage = err.message || 'Invalid JSON syntax';
            }
        },

        validate() {
            if (!this.input.trim()) {
                this.isValid = null;
                this.errorMessage = '';
                return;
            }

            try {
                JSON.parse(this.input);
                this.isValid = true;
                this.errorMessage = '';
            } catch (err) {
                this.isValid = false;
                this.errorMessage = err.message || 'Invalid JSON syntax';
            }
        },

        loadSample() {
            const sample = {
                platform: "ToolZeno",
                version: "1.0.0",
                features: ["Word Counter", "Case Converter", "JSON Formatter", "QR Code", "Image Tools"],
                international: true,
                languages: ["en", "bn"],
                privacy: {
                    serverUploads: false,
                    clientSideOnly: true
                }
            };
            const space = this.indent === 'tab' ? '\t' : Number(this.indent);
            this.input = JSON.stringify(sample, null, space);
            this.isValid = true;
            this.errorMessage = '';
        },

        clear() {
            this.input = '';
            this.isValid = null;
            this.errorMessage = '';
        },

        async copy() {
            if (!this.input) return;
            try {
                await navigator.clipboard.writeText(this.input);
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            } catch (e) {
                console.error('Copy failed', e);
            }
        }
    };
}
