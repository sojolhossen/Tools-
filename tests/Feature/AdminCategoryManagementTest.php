<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_administrator_can_view_categories_list(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Text &amp; Writing', false);
    }

    public function test_administrator_can_create_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/categories', [
            'slug' => 'audio-tools',
            'name_en' => 'Audio Tools',
            'name_bn' => 'অডিও টুলস',
            'description_en' => 'Tools for sound and music.',
            'description_bn' => 'শব্দ ও সঙ্গীতের টুলস।',
            'icon' => 'speaker-wave',
            'color' => 'rose',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', [
            'slug' => 'audio-tools',
            'name_en' => 'Audio Tools',
        ]);
    }

    public function test_administrator_can_update_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::where('slug', 'text')->firstOrFail();

        $response = $this->actingAs($admin)->put("/admin/categories/{$category->id}", [
            'slug' => 'text',
            'name_en' => 'Text & Content',
            'name_bn' => 'টেক্সট ও কন্টেন্ট',
            'icon' => 'document-text',
            'color' => 'indigo',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/categories');
        $category->refresh();
        $this->assertSame('Text & Content', $category->name_en);
    }

    public function test_administrator_cannot_delete_category_with_assigned_tools(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::where('slug', 'text')->firstOrFail();

        // text category has tools assigned to it
        $this->assertTrue(Tool::where('category_slug', 'text')->count() > 0);

        $response = $this->actingAs($admin)->delete("/admin/categories/{$category->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('categories', [
            'slug' => 'text',
        ]);
    }

    public function test_administrator_can_delete_empty_category(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $emptyCat = Category::create([
            'slug' => 'empty-temp',
            'name_en' => 'Empty Temp',
            'name_bn' => 'খালি ক্যাটাগরি',
            'icon' => 'wrench',
            'color' => 'slate',
            'sort_order' => 99,
            'is_active' => false,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/categories/{$emptyCat->id}");
        $response->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', [
            'id' => $emptyCat->id,
        ]);
    }
}
