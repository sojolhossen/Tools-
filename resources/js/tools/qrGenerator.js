let qrCodeModule = null;

async function getQRCode() {
    if (!qrCodeModule) {
        qrCodeModule = (await import('qrcode')).default;
    }
    return qrCodeModule;
}

export default function qrGenerator() {
    return {
        text: 'https://example.com',
        size: 300,
        errorCorrectionLevel: 'M',
        isGenerated: false,
        errorMessage: '',

        init() {
            this.$nextTick(() => {
                this.generate();
            });
        },

        async generate() {
            if (!this.text.trim()) {
                this.isGenerated = false;
                this.errorMessage = 'Please enter a URL or text to generate a QR code.';
                return;
            }

            this.errorMessage = '';
            const canvas = this.$refs.qrCanvas;
            if (!canvas) return;

            try {
                const QRCode = await getQRCode();
                await QRCode.toCanvas(canvas, this.text.trim(), {
                    width: Number(this.size),
                    margin: 2,
                    errorCorrectionLevel: this.errorCorrectionLevel,
                    color: {
                        dark: '#0f172a',
                        light: '#ffffff'
                    }
                });
                this.isGenerated = true;
            } catch (err) {
                this.isGenerated = false;
                this.errorMessage = err.message || 'Failed to generate QR code.';
            }
        },

        downloadPng() {
            const canvas = this.$refs.qrCanvas;
            if (!canvas) return;
            const link = document.createElement('a');
            link.download = `qrcode-${Date.now()}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
        },

        async downloadSvg() {
            if (!this.text.trim()) return;
            try {
                const QRCode = await getQRCode();
                const svgString = await QRCode.toString(this.text.trim(), {
                    type: 'svg',
                    width: Number(this.size),
                    margin: 2,
                    errorCorrectionLevel: this.errorCorrectionLevel,
                    color: {
                        dark: '#0f172a',
                        light: '#ffffff'
                    }
                });
                const blob = new Blob([svgString], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.download = `qrcode-${Date.now()}.svg`;
                link.href = url;
                link.click();
                URL.revokeObjectURL(url);
            } catch (err) {
                console.error('Failed to generate SVG QR code', err);
            }
        },

        reset() {
            this.text = '';
            this.isGenerated = false;
            this.errorMessage = '';
            const canvas = this.$refs.qrCanvas;
            if (canvas) {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
        }
    };
}
