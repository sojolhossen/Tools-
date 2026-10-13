<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tool extends Model
{
    use HasFactory;

    /**
     * Strictly allowlisted blade views implemented in the codebase.
     * Administrators cannot introduce arbitrary templates or executable scripts.
     */
    public const ALLOWLISTED_VIEWS = [
        'tools.word-counter',
        'tools.case-converter',
        'tools.percentage-calculator',
        'tools.json-formatter',
        'tools.qr-generator',
        'tools.image-compressor',
        'tools.image-resizer',
        'tools.image-to-pdf',
        'tools.html-css-js-beautifier',
        'tools.sql-formatter',
        'tools.xml-formatter',
        'tools.yaml-json-converter',
        'tools.css-minifier',
        'tools.js-minifier',
        'tools.html-minifier',
        'tools.base64-encoder-decoder',
        'tools.url-encoder-decoder',
        'tools.html-entities-encoder',
        'tools.jwt-decoder',
        'tools.hex-binary-text-converter',
        'tools.regex-tester',
        'tools.cron-generator',
        'tools.chmod-calculator',
        'tools.curl-converter',
        'tools.uuid-generator',
        'tools.nanoid-generator',
        'tools.dummy-json-generator',
        'tools.bcrypt-hash-generator',
        'tools.code-diff-checker',
        'tools.string-escape-unescape',
        'tools.json-to-kotlin',
        'tools.json-to-java-pojo',
        'tools.json-to-dart',
        'tools.json-to-typescript',
        'tools.http-status-lookup',
        'tools.query-string-json-converter',
        'tools.api-payload-tester',
        'tools.url-path-normalizer',
        'tools.htaccess-generator',
        'tools.nginx-config-generator',
        'tools.gitignore-generator',
        'tools.hmac-generator',
        'tools.xml-json-converter',
        'tools.csv-to-sql',
        'tools.markdown-table-generator',
        'tools.html-table-converter',
        'tools.svg-path-visualizer',
        'tools.jpg-compressor',
        'tools.png-compressor',
        'tools.webp-compressor',
        'tools.bulk-image-compressor',
        'tools.svg-minifier',
        'tools.png-to-jpg',
        'tools.jpg-to-png',
        'tools.image-to-webp',
        'tools.webp-to-jpg-png',
        'tools.heic-to-jpg',
        'tools.svg-to-png',
        'tools.png-to-svg',
        'tools.image-to-ico',
        'tools.bmp-to-jpg-png',
        'tools.tiff-to-jpg-png',
        'tools.gif-to-mp4',
        'tools.video-to-gif',
        'tools.gif-to-webp',
        'tools.pixel-image-resizer',
        'tools.percentage-image-resizer',
        'tools.image-cropper',
        'tools.circle-image-cropper',
        'tools.image-rotate-flip',
        'tools.image-canvas-padder',
        'tools.app-icon-generator',
        'tools.favicon-generator',
        'tools.youtube-thumbnail-resizer',
        'tools.social-media-resizer',
        'tools.android-nine-patch-helper',
        'tools.color-palette-extractor',
        'tools.grayscale-image-converter',
        'tools.image-inverter',
        'tools.brightness-contrast-adjuster',
        'tools.image-blur-pixelate',
        'tools.image-duotone-filter',
        'tools.watermark-adder',
        'tools.meme-generator',
        'tools.dummy-image-generator',
        'tools.image-to-ascii',
        'tools.photo-frame-maker',
        'tools.exif-data-viewer',
        'tools.exif-stripper',
        'tools.image-to-base64',
        'tools.base64-to-image',
        'tools.image-dpi-converter',
        'tools.audio-trimmer',
        'tools.audio-converter',
        'tools.aspect-ratio-calculator',
        'tools.video-frame-grabber',
        'tools.voice-recorder',
    ];

    protected $fillable = [
        'slug',
        'category_slug',
        'name_en',
        'name_bn',
        'description_en',
        'description_bn',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'icon',
        'view_handler',
        'route_name',
        'status',
        'is_featured',
        'is_popular',
        'sort_order',
        'tags',
        'supported_locales',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
            'sort_order' => 'integer',
            'tags' => 'array',
            'supported_locales' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_slug', 'slug');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && in_array($this->view_handler, self::ALLOWLISTED_VIEWS, true);
    }

    public function getName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->name_bn ?: $this->name_en) : $this->name_en;
    }

    public function getDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->description_bn ?: $this->description_en) : ($this->description_en ?: '');
    }

    public function getShortDescription(?string $locale = null): string
    {
        return $this->getDescription($locale);
    }

    public function getMetaTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_title_bn ?: $this->meta_title_en ?: $this->getName('bn')) : ($this->meta_title_en ?: $this->getName('en'));
    }

    public function getMetaDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_description_bn ?: $this->meta_description_en ?: $this->getDescription('bn')) : ($this->meta_description_en ?: $this->getDescription('en'));
    }

    public function getUrl(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return route('tools.show', ['locale' => $locale, 'slug' => $this->slug]);
    }
}
