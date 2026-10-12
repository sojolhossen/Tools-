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

    public function getName(): string
    {
        return __($this->nameKey);
    }

    public function getDescription(): string
    {
        return __($this->descriptionKey);
    }

    public function getMetaTitle(): string
    {
        return __($this->metaTitleKey);
    }

    public function getMetaDescription(): string
    {
        return __($this->metaDescriptionKey);
    }

    public function getUrl(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return route('tools.show', ['locale' => $locale, 'slug' => $this->slug]);
    }
}
