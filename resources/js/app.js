import './bootstrap';
import Alpine from 'alpinejs';

import wordCounter from './tools/wordCounter';
import caseConverter from './tools/caseConverter';
import percentageCalculator from './tools/percentageCalculator';
import jsonFormatter from './tools/jsonFormatter';
import qrGenerator from './tools/qrGenerator';
import imageCompressor from './tools/imageCompressor';
import imageResizer from './tools/imageResizer';
import imageToPdf from './tools/imageToPdf';

Alpine.data('wordCounter', wordCounter);
Alpine.data('caseConverter', caseConverter);
Alpine.data('percentageCalculator', percentageCalculator);
Alpine.data('jsonFormatter', jsonFormatter);
Alpine.data('qrGenerator', qrGenerator);
Alpine.data('imageCompressor', imageCompressor);
Alpine.data('imageResizer', imageResizer);
Alpine.data('imageToPdf', imageToPdf);
Alpine.data('categoryTicker', () => ({
    timer: null,
    isHovered: false,
    init() {
        this.$nextTick(() => this.checkAndScroll());
        window.addEventListener('resize', () => this.checkAndScroll());
        if (this.$data && typeof this.$data.expanded !== 'undefined') {
            this.$watch('expanded', () => {
                this.$nextTick(() => setTimeout(() => this.checkAndScroll(), 120));
            });
        }
    },
    destroy() {
        if (this.timer) clearTimeout(this.timer);
    },
    checkAndScroll() {
        const el = this.$refs.ticker;
        if (!el) return;
        if (this.timer) clearTimeout(this.timer);

        setTimeout(() => {
            if (!this.$refs.ticker) return;
            const max = el.scrollWidth - el.clientWidth;
            if (max <= 2) {
                el.scrollLeft = 0;
                return;
            }

            let dir = 1;
            const tick = () => {
                if (!this.$refs.ticker) return;
                if (this.isHovered) {
                    this.timer = setTimeout(tick, 200);
                    return;
                }
                if (dir === 1) {
                    if (el.scrollLeft < max) {
                        el.scrollLeft += 1;
                        this.timer = setTimeout(tick, 35);
                    } else {
                        dir = -1;
                        this.timer = setTimeout(tick, 2000);
                    }
                } else {
                    if (el.scrollLeft > 0) {
                        el.scrollLeft -= 1;
                        this.timer = setTimeout(tick, 35);
                    } else {
                        dir = 1;
                        this.timer = setTimeout(tick, 2000);
                    }
                }
            };
            this.timer = setTimeout(tick, 1500);
        }, 150);
    }
}));

window.Alpine = Alpine;
Alpine.start();
