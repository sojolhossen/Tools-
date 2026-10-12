<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_en',
        'title_bn',
        'body_en',
        'body_bn',
        'meta_title_en',
        'meta_title_bn',
        'meta_description_en',
        'meta_description_bn',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function getTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->title_bn ?: $this->title_en) : $this->title_en;
    }

    public function getBody(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->body_bn ?: $this->body_en ?: '') : ($this->body_en ?: '');
    }

    public function getMetaTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_title_bn ?: $this->meta_title_en ?: $this->getTitle('bn')) : ($this->meta_title_en ?: $this->getTitle('en'));
    }

    public function getMetaDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'bn' ? ($this->meta_description_bn ?: $this->meta_description_en ?: '') : ($this->meta_description_en ?: '');
    }

    public function getPublicUrl(string $locale = 'en'): string
    {
        return match ($this->slug) {
            'about' => route('pages.about', ['locale' => $locale]),
            'contact' => route('pages.contact', ['locale' => $locale]),
            'privacy-policy' => route('pages.privacy', ['locale' => $locale]),
            'terms-of-service' => route('pages.terms', ['locale' => $locale]),
            'disclaimer' => route('pages.disclaimer', ['locale' => $locale]),
            default => route('pages.show', ['locale' => $locale, 'slug' => $this->slug]),
        };
    }
}
