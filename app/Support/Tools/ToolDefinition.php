<?php

declare(strict_types=1);

namespace App\Support\Tools;

final class ToolDefinition
{
    /**
     * @param array<int, string> $supportedLocales
     * @param array<int, string> $tags
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $nameKey,
        public readonly string $descriptionKey,
        public readonly string $category,
        public readonly string $icon,
        public readonly string $routeName,
        public readonly string $status,
        public readonly array $supportedLocales,
        public readonly string $view,
        public readonly bool $isFeatured,
        public readonly bool $isPopular,
        public readonly string $metaTitleKey,
        public readonly string $metaDescriptionKey,
        public readonly array $tags = [],
        public readonly ?string $nameEn = null,
        public readonly ?string $nameBn = null,
        public readonly ?string $descriptionEn = null,
        public readonly ?string $descriptionBn = null,
        public readonly ?string $metaTitleEn = null,
        public readonly ?string $metaTitleBn = null,
        public readonly ?string $metaDescriptionEn = null,
        public readonly ?string $metaDescriptionBn = null,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            slug: (string) ($data['slug'] ?? ''),
            nameKey: (string) ($data['name_key'] ?? ''),
            descriptionKey: (string) ($data['description_key'] ?? ''),
            category: (string) ($data['category'] ?? 'other'),
            icon: (string) ($data['icon'] ?? 'wrench'),
            routeName: (string) ($data['route_name'] ?? 'tools.show'),
            status: (string) ($data['status'] ?? 'active'),
            supportedLocales: (array) ($data['supported_locales'] ?? ['en', 'bn']),
            view: (string) ($data['view'] ?? ''),
            isFeatured: (bool) ($data['is_featured'] ?? false),
            isPopular: (bool) ($data['is_popular'] ?? false),
            metaTitleKey: (string) ($data['meta_title_key'] ?? ''),
            metaDescriptionKey: (string) ($data['meta_description_key'] ?? ''),
            tags: (array) ($data['tags'] ?? []),
            nameEn: isset($data['name_en']) ? (string) $data['name_en'] : null,
            nameBn: isset($data['name_bn']) ? (string) $data['name_bn'] : null,
            descriptionEn: isset($data['description_en']) ? (string) $data['description_en'] : null,
            descriptionBn: isset($data['description_bn']) ? (string) $data['description_bn'] : null,
            metaTitleEn: isset($data['meta_title_en']) ? (string) $data['meta_title_en'] : null,
            metaTitleBn: isset($data['meta_title_bn']) ? (string) $data['meta_title_bn'] : null,
            metaDescriptionEn: isset($data['meta_description_en']) ? (string) $data['meta_description_en'] : null,
            metaDescriptionBn: isset($data['meta_description_bn']) ? (string) $data['meta_description_bn'] : null,
        );
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function supportsLocale(string $locale): bool
    {
        return in_array($locale, $this->supportedLocales, true);
    }

    public function getName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'bn' && !empty($this->nameBn)) {
            return $this->nameBn;
        }
        if ($locale === 'en' && !empty($this->nameEn)) {
            return $this->nameEn;
        }
        return __($this->nameKey);
    }

    public function getDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'bn' && !empty($this->descriptionBn)) {
            return $this->descriptionBn;
        }
        if ($locale === 'en' && !empty($this->descriptionEn)) {
            return $this->descriptionEn;
        }
        return __($this->descriptionKey);
    }

    public function getShortDescription(?string $locale = null): string
    {
        return $this->getDescription($locale);
    }

    public function getMetaTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'bn' && !empty($this->metaTitleBn)) {
            return $this->metaTitleBn;
        }
        if ($locale === 'en' && !empty($this->metaTitleEn)) {
            return $this->metaTitleEn;
        }
        return __($this->metaTitleKey);
    }

    public function getMetaDescription(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        if ($locale === 'bn' && !empty($this->metaDescriptionBn)) {
            return $this->metaDescriptionBn;
        }
        if ($locale === 'en' && !empty($this->metaDescriptionEn)) {
            return $this->metaDescriptionEn;
        }
        return __($this->metaDescriptionKey);
    }

    public function getUrl(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return route('tools.show', ['locale' => $locale, 'slug' => $this->slug]);
    }
}
