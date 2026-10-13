<?php

declare(strict_types=1);

namespace App\Support\Tools;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

final class ToolRegistry
{
    /**
     * @var Collection<string, ToolDefinition>|null
     */
    private ?Collection $tools = null;

    /**
     * Clear cached registry instance (used on admin updates and testing).
     */
    public function refresh(): void
    {
        $this->tools = null;
    }

    /**
     * @return Collection<string, ToolDefinition>
     */
    public function all(): Collection
    {
        if ($this->tools === null) {
            $configItems = config('tools.items', []);
            $dbTools = collect();

            try {
                if (Schema::hasTable('tools')) {
                    $existingSlugs = Tool::pluck('slug')->all();
                    $order = count($existingSlugs);

                    foreach ($configItems as $slug => $data) {
                        if (!in_array($slug, $existingSlugs, true)) {
                            try {
                                Tool::create([
                                    'slug' => $slug,
                                    'category_slug' => $data['category'],
                                    'name_en' => __($data['name_key'], [], 'en'),
                                    'name_bn' => __($data['name_key'], [], 'bn'),
                                    'description_en' => __($data['description_key'], [], 'en'),
                                    'description_bn' => __($data['description_key'], [], 'bn'),
                                    'meta_title_en' => __($data['meta_title_key'], [], 'en'),
                                    'meta_title_bn' => __($data['meta_title_key'], [], 'bn'),
                                    'meta_description_en' => __($data['meta_description_key'], [], 'en'),
                                    'meta_description_bn' => __($data['meta_description_key'], [], 'bn'),
                                    'icon' => $data['icon'] ?? 'code-bracket',
                                    'view_handler' => $data['view'],
                                    'route_name' => $data['route_name'] ?? 'tools.show',
                                    'status' => $data['status'] ?? 'active',
                                    'is_featured' => $data['is_featured'] ?? false,
                                    'is_popular' => $data['is_popular'] ?? false,
                                    'sort_order' => ++$order,
                                    'tags' => $data['tags'] ?? [],
                                    'supported_locales' => $data['supported_locales'] ?? ['en', 'bn'],
                                ]);
                            } catch (\Throwable $ignored) {
                                // Ignore concurrency collisions
                            }
                        }
                    }

                    $dbTools = Tool::all()->keyBy('slug');
                }
            } catch (\Throwable $e) {
                // Graceful fallback to config file if DB is not available yet
            }

            $merged = collect($configItems)->mapWithKeys(function (array $data, string $key) use ($dbTools) {
                /** @var Tool|null $dbRecord */
                $dbRecord = $dbTools->get($key);

                if ($dbRecord !== null) {
                    // Only apply if view_handler is an allowlisted implementation
                    if (in_array($dbRecord->view_handler, Tool::ALLOWLISTED_VIEWS, true)) {
                        $data['status'] = $dbRecord->status;
                        $data['is_featured'] = $dbRecord->is_featured;
                        $data['is_popular'] = $dbRecord->is_popular;
                        $data['sort_order'] = $dbRecord->sort_order;
                        $data['category'] = $dbRecord->category_slug;
                        $data['icon'] = $dbRecord->icon;
                        $data['name_en'] = $dbRecord->name_en;
                        $data['name_bn'] = $dbRecord->name_bn;
                        $data['description_en'] = $dbRecord->description_en;
                        $data['description_bn'] = $dbRecord->description_bn;
                        $data['meta_title_en'] = $dbRecord->meta_title_en;
                        $data['meta_title_bn'] = $dbRecord->meta_title_bn;
                        $data['meta_description_en'] = $dbRecord->meta_description_en;
                        $data['meta_description_bn'] = $dbRecord->meta_description_bn;
                        if (!empty($dbRecord->tags)) {
                            $data['tags'] = $dbRecord->tags;
                        }
                    }
                }

                $data['slug'] = $data['slug'] ?? $key;

                return [$key => ToolDefinition::fromArray($data)];
            });

            $this->tools = $merged;
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
        $configCategories = config('tools.categories', []);

        try {
            if (Schema::hasTable('categories')) {
                // Ensure all configured categories exist in the database table
                $existingSlugs = Category::pluck('slug')->all();
                $order = count($existingSlugs);

                foreach ($configCategories as $slug => $data) {
                    if (!in_array($slug, $existingSlugs, true)) {
                        try {
                            Category::create([
                                'slug' => $slug,
                                'name_en' => __('common.categories.' . $slug, [], 'en'),
                                'name_bn' => __('common.categories.' . $slug, [], 'bn'),
                                'description_en' => __('common.category_descriptions.' . $slug, [], 'en'),
                                'description_bn' => __('common.category_descriptions.' . $slug, [], 'bn'),
                                'icon' => $data['icon'] ?? 'squares-2x2',
                                'color' => $data['color'] ?? 'indigo',
                                'sort_order' => ++$order,
                                'is_active' => true,
                            ]);
                        } catch (\Throwable $ignored) {
                            // Suppress unique constraint / concurrency collisions
                        }
                    }
                }

                $dbCategories = Category::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

                if ($dbCategories->isNotEmpty()) {
                    $result = [];
                    foreach ($dbCategories as $cat) {
                        $result[$cat->slug] = [
                            'icon' => $cat->icon,
                            'color' => $cat->color,
                            'name_en' => $cat->name_en,
                            'name_bn' => $cat->name_bn,
                        ];
                    }

                    // Guarantee all categories from config are present
                    foreach ($configCategories as $slug => $data) {
                        if (!isset($result[$slug])) {
                            $result[$slug] = [
                                'icon' => $data['icon'] ?? 'squares-2x2',
                                'color' => $data['color'] ?? 'indigo',
                                'name_en' => __('common.categories.' . $slug, [], 'en'),
                                'name_bn' => __('common.categories.' . $slug, [], 'bn'),
                            ];
                        }
                    }

                    return $result;
                }
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        return $configCategories;
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

        $sameCategory = $this->active()
            ->filter(fn (ToolDefinition $t) => $t->slug !== $slug && $t->category === $current->category);

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
