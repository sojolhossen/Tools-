<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use App\Support\Tools\ToolRegistry;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminToolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_administrator_can_view_tools_index(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/tools');
        $response->assertStatus(200);
        $response->assertSee('Word &amp; Character Counter', false);
    }

    public function test_administrator_can_update_tool_metadata(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tool = Tool::where('slug', 'word-counter')->firstOrFail();

        $response = $this->actingAs($admin)->put("/admin/tools/{$tool->id}", [
            'name_en' => 'Pro Word Counter',
            'name_bn' => 'প্রো ওয়ার্ড কাউন্টার',
            'description_en' => 'Updated English description.',
            'description_bn' => 'হালনাগাদ বাংলা বিবরণ।',
            'category_slug' => 'text',
            'icon' => 'text-quote',
            'view_handler' => 'tools.word-counter',
            'status' => 'active',
            'sort_order' => 5,
            'is_featured' => true,
            'is_popular' => true,
            'meta_title_en' => 'Pro Word Counter Tool',
            'meta_title_bn' => 'প্রো ওয়ার্ড কাউন্টার টুল',
            'meta_description_en' => 'Updated meta description.',
            'meta_description_bn' => 'আপডেট করা মেটা বিবরণ।',
            'tags' => 'words, characters, count',
        ]);

        $response->assertRedirect('/admin/tools');

        $tool->refresh();
        $this->assertSame('Pro Word Counter', $tool->name_en);
        $this->assertSame('প্রো ওয়ার্ড কাউন্টার', $tool->name_bn);
        $this->assertSame(5, $tool->sort_order);

        // Registry should immediately reflect dynamic DB changes
        $registry = app(ToolRegistry::class);
        $registry->refresh();
        $toolDef = $registry->find('word-counter');
        $this->assertNotNull($toolDef);
        $this->assertSame('Pro Word Counter', $toolDef->getName('en'));
        $this->assertSame('প্রো ওয়ার্ড কাউন্টার', $toolDef->getName('bn'));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'tool.updated',
            'entity_id' => (string) $tool->id,
        ]);
    }

    public function test_administrator_cannot_set_disallowed_view_handler(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tool = Tool::where('slug', 'word-counter')->firstOrFail();

        $response = $this->actingAs($admin)->put("/admin/tools/{$tool->id}", [
            'name_en' => 'Hacked Tool',
            'name_bn' => 'হ্যাকড টুল',
            'category_slug' => 'text',
            'icon' => 'wrench',
            'view_handler' => 'malicious.arbitrary.template',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $response->assertSessionHasErrors(['view_handler']);
    }

    public function test_administrator_can_toggle_tool_status(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $tool = Tool::where('slug', 'word-counter')->firstOrFail();

        $response = $this->actingAs($admin)->post("/admin/tools/{$tool->id}/toggle");
        $response->assertRedirect();

        $tool->refresh();
        $this->assertSame('inactive', $tool->status);

        $registry = app(ToolRegistry::class);
        $registry->refresh();
        $this->assertNull($registry->active()->get('word-counter'));

        // Toggle back to active
        $response = $this->actingAs($admin)->post("/admin/tools/{$tool->id}/toggle");
        $tool->refresh();
        $this->assertSame('active', $tool->status);
    }
}
