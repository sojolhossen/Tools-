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
