let jsPDFClass = null;

async function getJsPDF() {
    if (!jsPDFClass) {
        const mod = await import('jspdf');
        jsPDFClass = mod.jsPDF;
    }
    return jsPDFClass;
}

export default function imageToPdf() {
    return {
        images: [],
        pageSize: 'a4', // 'a4' | 'letter' | 'fit'
        orientation: 'portrait', // 'portrait' | 'landscape' | 'auto'
        margin: '10', // '0' | '10' | '20'
        isGenerating: false,
        progressText: '',
        errorMessage: '',

        formatBytes(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        handleFilesSelect(event) {
            const fileList = event.target.files || event.dataTransfer?.files;
            if (!fileList || !fileList.length) return;

            for (let i = 0; i < fileList.length; i++) {
                const file = fileList[i];
                if (!file.type.match(/^image\/(jpeg|png|webp)$/i)) {
                    continue;
                }

                const url = URL.createObjectURL(file);
                this.images.push({
                    id: Math.random().toString(36).substring(2, 9),
                    file: file,
                    name: file.name,
                    size: file.size,
                    url: url,
                });
            }

            if (event.target && event.target.value) {
                event.target.value = '';
            }
        },

        moveUp(index) {
            if (index <= 0) return;
            const item = this.images.splice(index, 1)[0];
            this.images.splice(index - 1, 0, item);
        },

        moveDown(index) {
            if (index >= this.images.length - 1) return;
            const item = this.images.splice(index, 1)[0];
            this.images.splice(index + 1, 0, item);
        },

        remove(index) {
            const removed = this.images.splice(index, 1)[0];
            if (removed && removed.url) {
                URL.revokeObjectURL(removed.url);
            }
        },

        clearAll() {
            this.images.forEach(img => {
                if (img.url) URL.revokeObjectURL(img.url);
            });
            this.images = [];
            this.errorMessage = '';
        },

        async generatePdf() {
            if (!this.images.length) {
                this.errorMessage = 'Please select at least one image.';
                return;
            }

            this.isGenerating = true;
            this.errorMessage = '';
            this.progressText = 'Preparing images...';

            try {
                // Dimensions in mm
                const pageDims = {
                    a4: { w: 210, h: 297 },
                    letter: { w: 215.9, h: 279.4 }
                };

                let doc = null;
                const jsPDF = await getJsPDF();
                const marginMm = parseInt(this.margin, 10);

                for (let i = 0; i < this.images.length; i++) {
                    const item = this.images[i];
                    this.progressText = `Compiling page ${i + 1} of ${this.images.length}...`;

                    const imgData = await this.loadImageDetails(item.url);

                    let chosenOrient = this.orientation;
                    if (chosenOrient === 'auto') {
                        chosenOrient = imgData.width > imgData.height ? 'landscape' : 'portrait';
                    }

                    let pageW, pageH;
                    if (this.pageSize === 'fit') {
                        // Fit page to image aspect ratio at standard 72/96 dpi approx in mm
                        const scale = 0.264583; // 1px approx 0.264583 mm
                        pageW = imgData.width * scale + (marginMm * 2);
                        pageH = imgData.height * scale + (marginMm * 2);
                        chosenOrient = pageW > pageH ? 'landscape' : 'portrait';
                    } else {
                        const standard = pageDims[this.pageSize] || pageDims.a4;
                        pageW = chosenOrient === 'landscape' ? Math.max(standard.w, standard.h) : Math.min(standard.w, standard.h);
                        pageH = chosenOrient === 'landscape' ? Math.min(standard.w, standard.h) : Math.max(standard.w, standard.h);
                    }

                    if (i === 0) {
                        doc = new jsPDF({
                            orientation: chosenOrient,
                            unit: 'mm',
                            format: this.pageSize === 'fit' ? [pageW, pageH] : this.pageSize
                        });
                    } else {
                        doc.addPage(this.pageSize === 'fit' ? [pageW, pageH] : this.pageSize, chosenOrient);
                    }

                    // Calculate available box inside margins
                    const availW = Math.max(10, pageW - (marginMm * 2));
                    const availH = Math.max(10, pageH - (marginMm * 2));

                    const imgRatio = imgData.width / imgData.height;
                    const availRatio = availW / availH;

                    let finalW, finalH;
                    if (imgRatio > availRatio) {
                        finalW = availW;
                        finalH = availW / imgRatio;
                    } else {
                        finalH = availH;
                        finalW = availH * imgRatio;
                    }

                    // Center on page
                    const posX = marginMm + (availW - finalW) / 2;
                    const posY = marginMm + (availH - finalH) / 2;

                    doc.addImage(imgData.dataUrl, 'JPEG', posX, posY, finalW, finalH, undefined, 'FAST');
                }

                this.progressText = 'Finalizing PDF...';
                doc.save(`compiled-documents-${Date.now()}.pdf`);
            } catch (err) {
                console.error('PDF generation error', err);
                this.errorMessage = 'Failed to generate PDF. Please ensure all images are valid.';
            } finally {
                this.isGenerating = false;
                this.progressText = '';
            }
        },

        loadImageDetails(url) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.naturalWidth;
                    canvas.height = img.naturalHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    resolve({
                        width: img.naturalWidth,
                        height: img.naturalHeight,
                        dataUrl: canvas.toDataURL('image/jpeg', 0.9)
                    });
                };
                img.onerror = reject;
                img.src = url;
            });
        }
    };
}
