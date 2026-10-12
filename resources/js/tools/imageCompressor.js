export default function imageCompressor() {
    return {
        file: null,
        fileName: '',
        originalSize: 0,
        originalUrl: '',
        compressedSize: 0,
        compressedUrl: '',
        quality: 75,
        width: 0,
        height: 0,
        isProcessing: false,
        errorMessage: '',

        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        get savingsPercent() {
            if (!this.originalSize || !this.compressedSize) return 0;
            const diff = this.originalSize - this.compressedSize;
            return Math.round((diff / this.originalSize) * 100);
        },

        handleFileSelect(event) {
            const files = event.target.files || event.dataTransfer?.files;
            if (!files || !files.length) return;
            this.processSelectedFile(files[0]);
        },

        processSelectedFile(file) {
            if (!file.type.match(/^image\/(jpeg|png|webp)$/i)) {
                this.errorMessage = 'Unsupported format. Please select a JPEG, PNG, or WebP image.';
                return;
            }

            if (file.size > 25 * 1024 * 1024) {
                this.errorMessage = 'File too large. Maximum supported file size is 25 MB.';
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
                this.width = img.naturalWidth;
                this.height = img.naturalHeight;
                this.compress();
            };
            img.src = this.originalUrl;
        },

        compress() {
            if (!this.originalUrl) return;

            this.isProcessing = true;
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                // Use original type or JPEG for high compression
                const mimeType = this.file?.type === 'image/png' ? 'image/webp' : (this.file?.type || 'image/jpeg');
                const q = Math.max(0.05, Math.min(1.0, this.quality / 100));

                canvas.toBlob((blob) => {
                    if (!blob) {
                        this.errorMessage = 'Image compression failed.';
                        this.isProcessing = false;
                        return;
                    }

                    if (this.compressedUrl) URL.revokeObjectURL(this.compressedUrl);
                    this.compressedUrl = URL.createObjectURL(blob);
                    this.compressedSize = blob.size;
                    this.isProcessing = false;
                }, mimeType, q);
            };
            img.src = this.originalUrl;
        },

        download() {
            if (!this.compressedUrl) return;
            const link = document.createElement('a');
            const ext = this.file?.type === 'image/png' ? 'webp' : (this.file?.type === 'image/webp' ? 'webp' : 'jpg');
            const nameWithoutExt = this.fileName.replace(/\.[^/.]+$/, '');
            link.download = `${nameWithoutExt}-compressed.${ext}`;
            link.href = this.compressedUrl;
            link.click();
        },

        reset() {
            if (this.originalUrl) URL.revokeObjectURL(this.originalUrl);
            if (this.compressedUrl) URL.revokeObjectURL(this.compressedUrl);
            this.file = null;
            this.fileName = '';
            this.originalSize = 0;
            this.originalUrl = '';
            this.compressedSize = 0;
            this.compressedUrl = '';
            this.quality = 75;
            this.width = 0;
            this.height = 0;
            this.errorMessage = '';
        }
    };
}
