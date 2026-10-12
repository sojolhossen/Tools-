<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name_en',
        'name_bn',
        'description_en',
        'description_bn',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class, 'category_slug', 'slug');
    }

    public function getName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->name_bn ?: $this->name_en) : $this->name_en;
    }

    public function getDescription(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->description_bn ?: $this->description_en) : $this->description_en;
    }

    public function getMetaTitle(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_title_bn ?: $this->meta_title_en) : $this->meta_title_en;
    }

    public function getMetaDescription(?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_description_bn ?: $this->meta_description_en) : $this->meta_description_en;
    }
}
