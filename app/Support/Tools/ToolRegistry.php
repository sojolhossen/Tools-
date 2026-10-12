<?php

declare(strict_types=1);

namespace App\Support\Tools;

use Illuminate\Support\Collection;

final class ToolRegistry
{
    /**
     * @var Collection<string, ToolDefinition>|null
     */
    private ?Collection $tools = null;

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function all(): Collection
    {
        if ($this->tools === null) {
            $items = config('tools.items', []);
            $this->tools = collect($items)->mapWithKeys(function (array $data, string $key) {
                return [$key => ToolDefinition::fromArray($data)];
            });
        }

        return $this->tools;
    }

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function active(): Collection
    {
        return $this->all()->filter(fn (ToolDefinition $tool) => $tool->isActive());
    }

    public function find(string $slug): ?ToolDefinition
    {
        return $this->all()->get($slug);
    }

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function getByCategory(string $category): Collection
    {
        return $this->active()->filter(fn (ToolDefinition $tool) => $tool->category === $category);
    }

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function getFeatured(): Collection
    {
        return $this->active()->filter(fn (ToolDefinition $tool) => $tool->isFeatured);
    }

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function getPopular(): Collection
    {
        return $this->active()->filter(fn (ToolDefinition $tool) => $tool->isPopular);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getCategories(): array
    {
        return config('tools.categories', []);
    }

    /**
     * Get related tools based on category, excluding current tool.
     *
     * @return Collection<string, ToolDefinition>
     */
    public function getRelated(string $slug, int $limit = 3): Collection
    {
        $current = $this->find($slug);
        if ($current === null) {
            return collect();
        }

        // First find in same category
        $sameCategory = $this->active()
            ->filter(fn (ToolDefinition $t) => $t->slug !== $slug && $t->category === $current->category);

        // If not enough, fill with other active tools
        if ($sameCategory->count() < $limit) {
            $remaining = $this->active()
                ->filter(fn (ToolDefinition $t) => $t->slug !== $slug && $t->category !== $current->category);
            $combined = $sameCategory->merge($remaining);
        } else {
            $combined = $sameCategory;
        }

        return $combined->take($limit);
    }

    /**
     * Search tools by query (matching slug, name, description, tags).
     *
     * @return Collection<string, ToolDefinition>
     */
    public function search(string $query): Collection
    {
        $q = mb_strtolower(trim($query));
        if ($q === '') {
            return $this->active();
        }

        return $this->active()->filter(function (ToolDefinition $tool) use ($q) {
            if (str_contains(mb_strtolower($tool->slug), $q)) {
                return true;
            }
            if (str_contains(mb_strtolower($tool->getName()), $q)) {
                return true;
            }
            if (str_contains(mb_strtolower($tool->getDescription()), $q)) {
                return true;
            }
            foreach ($tool->tags as $tag) {
                if (str_contains(mb_strtolower($tag), $q)) {
                    return true;
                }
            }
            return false;
        });
    }
}
