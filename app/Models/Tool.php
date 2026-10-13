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
        'tools.length-converter',
        'tools.weight-converter',
        'tools.temperature-converter',
        'tools.area-converter',
        'tools.volume-converter',
        'tools.data-storage-converter',
        'tools.data-transfer-rate-converter',
        'tools.pixel-to-rem-converter',
        'tools.dpi-ppi-converter',
        'tools.speed-converter',
        'tools.time-converter',
        'tools.acceleration-converter',
        'tools.pace-converter',
        'tools.pressure-converter',
        'tools.power-converter',
        'tools.energy-converter',
        'tools.force-converter',
        'tools.torque-converter',
        'tools.angle-converter',
        'tools.voltage-converter',
        'tools.electric-current-converter',
        'tools.electric-resistance-converter',
        'tools.electric-capacitance-converter',
        'tools.frequency-converter',
        'tools.fuel-economy-converter',
        'tools.flow-rate-converter',
        'tools.density-converter',
        'tools.viscosity-converter',
        'tools.cooking-volume-converter',
        'tools.cooking-weight-converter',
        'tools.currency-converter',
        'tools.crypto-converter',
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
        'tools.simple-calculator',
        'tools.scientific-calculator',
        'tools.fraction-calculator',
        'tools.lcm-gcd-calculator',
        'tools.average-calculator',
        'tools.permutation-combination-calculator',
        'tools.quadratic-equation-solver',
        'tools.matrix-calculator',
        'tools.logarithm-calculator',
        'tools.age-calculator',
        'tools.date-difference-calculator',
        'tools.date-add-subtract-calculator',
        'tools.working-hours-calculator',
        'tools.days-until-calculator',
        'tools.leap-year-checker',
        'tools.bmi-calculator',
        'tools.bmr-calculator',
        'tools.tdee-calculator',
        'tools.calorie-deficit-calculator',
        'tools.ideal-weight-calculator',
        'tools.body-fat-calculator',
        'tools.water-intake-calculator',
        'tools.pregnancy-due-date-calculator',
        'tools.emi-calculator',
        'tools.interest-calculator',
        'tools.discount-calculator',
        'tools.sales-tax-vat-calculator',
        'tools.tip-calculator',
        'tools.fuel-cost-calculator',
        'tools.gpa-calculator',
        'tools.final-grade-calculator',
        'tools.grade-percentage-converter',
        'tools.binary-calculator',
        'tools.subnet-ip-calculator',
        'tools.bandwidth-calculator',
        'tools.bitwise-calculator',
        'tools.loan-emi-calculator',
        'tools.home-loan-eligibility-calculator',
        'tools.car-loan-calculator',
        'tools.loan-prepayment-calculator',
        'tools.loan-comparison-tool',
        'tools.compound-interest-calculator',
        'tools.simple-interest-calculator',
        'tools.sip-calculator',
        'tools.lump-sum-investment-calculator',
        'tools.fd-dps-calculator',
        'tools.rule-of-72-calculator',
        'tools.future-value-calculator',
        'tools.retirement-calculator',
        'tools.inflation-calculator',
        'tools.target-savings-calculator',
        'tools.child-education-fund-calculator',
        'tools.profit-margin-calculator',
        'tools.break-even-calculator',
        'tools.discount-sales-price-calculator',
        'tools.roi-calculator',
        'tools.operating-cash-flow-calculator',
        'tools.vat-gst-calculator',
        'tools.salary-take-home-calculator',
        'tools.income-tax-estimator',
        'tools.stock-average-calculator',
        'tools.stock-profit-calculator',
        'tools.crypto-profit-calculator',
        'tools.risk-reward-calculator',
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
