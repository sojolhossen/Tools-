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

window.Alpine = Alpine;
Alpine.start();
