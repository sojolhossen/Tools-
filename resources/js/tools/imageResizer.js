export default function imageResizer() {
    return {
        file: null,
        fileName: '',
        originalUrl: '',
        originalWidth: 0,
        originalHeight: 0,
        originalSize: 0,
        targetWidth: 0,
        targetHeight: 0,
        lockAspectRatio: true,
        aspectRatio: 1,
        outputFormat: 'original',
        resizedUrl: '',
        resizedSize: 0,
        isProcessing: false,
        errorMessage: '',

        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        handleFileSelect(event) {
            const files = event.target.files || event.dataTransfer?.files;
            if (!files || !files.length) return;
            this.processFile(files[0]);
        },

        processFile(file) {
            if (!file.type.match(/^image\/(jpeg|png|webp)$/i)) {
                this.errorMessage = 'Unsupported format. Please select a JPEG, PNG, or WebP image.';
                return;
            }

            this.errorMessage = '';
            this.file = file;
            this.fileName = file.name;
            this.originalSize = file.size;

            if (this.originalUrl) URL.revokeObjectURL(this.originalUrl);
            this.originalUrl = URL.createObjectURL(file);

            const img = new Image();
            img.onload = () => {
                this.originalWidth = img.naturalWidth;
                this.originalHeight = img.naturalHeight;
                this.targetWidth = img.naturalWidth;
                this.targetHeight = img.naturalHeight;
                this.aspectRatio = img.naturalWidth / img.naturalHeight;
                this.renderResize();
            };
            img.src = this.originalUrl;
        },

        onWidthChange() {
            const w = parseInt(this.targetWidth, 10);
            if (isNaN(w) || w <= 0) return;
            if (this.lockAspectRatio && this.aspectRatio) {
                this.targetHeight = Math.round(w / this.aspectRatio);
            }
            this.renderResize();
        },

        onHeightChange() {
            const h = parseInt(this.targetHeight, 10);
            if (isNaN(h) || h <= 0) return;
            if (this.lockAspectRatio && this.aspectRatio) {
                this.targetWidth = Math.round(h * this.aspectRatio);
            }
            this.renderResize();
        },

        applyScale(pct) {
            if (!this.originalWidth || !this.originalHeight) return;
            const factor = pct / 100;
            this.targetWidth = Math.round(this.originalWidth * factor);
            this.targetHeight = Math.round(this.originalHeight * factor);
            this.renderResize();
        },

        renderResize() {
            if (!this.originalUrl) return;

            const w = parseInt(this.targetWidth, 10);
            const h = parseInt(this.targetHeight, 10);

            if (isNaN(w) || isNaN(h) || w <= 0 || h <= 0) {
                this.errorMessage = 'Dimensions must be positive integers.';
                return;
            }

            if (w > 8192 || h > 8192) {
                this.errorMessage = 'Dimensions cannot exceed 8,192 pixels for memory safety.';
                return;
            }

            this.errorMessage = '';
            this.isProcessing = true;

            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;

                const ctx = canvas.getContext('2d');
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';
                ctx.drawImage(img, 0, 0, w, h);

                let mime = this.file?.type || 'image/jpeg';
                if (this.outputFormat === 'jpeg') mime = 'image/jpeg';
                else if (this.outputFormat === 'png') mime = 'image/png';
                else if (this.outputFormat === 'webp') mime = 'image/webp';

                canvas.toBlob((blob) => {
                    if (!blob) {
                        this.errorMessage = 'Failed to generate resized image.';
                        this.isProcessing = false;
                        return;
                    }

                    if (this.resizedUrl) URL.revokeObjectURL(this.resizedUrl);
                    this.resizedUrl = URL.createObjectURL(blob);
                    this.resizedSize = blob.size;
                    this.isProcessing = false;
                }, mime, 0.92);
            };
            img.src = this.originalUrl;
        },

        download() {
            if (!this.resizedUrl) return;
            const link = document.createElement('a');
            let ext = 'jpg';
            if (this.outputFormat === 'png' || (this.outputFormat === 'original' && this.file?.type === 'image/png')) ext = 'png';
            else if (this.outputFormat === 'webp' || (this.outputFormat === 'original' && this.file?.type === 'image/webp')) ext = 'webp';

            const nameWithoutExt = this.fileName.replace(/\.[^/.]+$/, '');
            link.download = `${nameWithoutExt}-${this.targetWidth}x${this.targetHeight}.${ext}`;
            link.href = this.resizedUrl;
            link.click();
        },

        reset() {
            if (this.originalUrl) URL.revokeObjectURL(this.originalUrl);
            if (this.resizedUrl) URL.revokeObjectURL(this.resizedUrl);
            this.file = null;
            this.fileName = '';
            this.originalUrl = '';
            this.resizedUrl = '';
            this.originalWidth = 0;
            this.originalHeight = 0;
            this.targetWidth = 0;
            this.targetHeight = 0;
            this.errorMessage = '';
        }
    };
}
