<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ContentPage;
use App\Models\User;
use App\Services\SafeContentRenderer;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_administrator_can_view_pages_list(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/content');
        $response->assertStatus(200);
        $response->assertSee('About ToolZeno');
    }

    public function test_administrator_can_create_new_guide_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/content', [
            'slug' => 'word-counter-guide',
            'title_en' => 'How to Count Words in Bangla',
            'title_bn' => 'বাংলায় কীভাবে শব্দ গণনা করবেন',
            'body_en' => '## Guide Steps\n\n1. Paste your Bengali text into the input box.\n2. Review results.',
            'body_bn' => '## নির্দেশিকা\n\n১. টেক্সট বক্সে বাংলা লেখা দিন।\n২. ফলাফল দেখুন।',
            'status' => 'published',
        ]);

        $response->assertRedirect('/admin/content');
        $this->assertDatabaseHas('content_pages', [
            'slug' => 'word-counter-guide',
            'status' => 'published',
        ]);

        // Public visitor can view published guide
        $publicResponse = $this->get('/en/pages/word-counter-guide');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('How to Count Words in Bangla');
    }

    public function test_draft_page_returns_404_for_public_visitor(): void
    {
        ContentPage::create([
            'slug' => 'secret-draft',
            'title_en' => 'Draft Tutorial',
            'title_bn' => 'খসড়া টিউটোরিয়াল',
            'body_en' => 'Private draft content',
            'status' => 'draft',
        ]);

        // Public visitor gets 404
        $publicResponse = $this->get('/en/pages/secret-draft');
        $publicResponse->assertStatus(404);

        // Authenticated admin can view draft
        $admin = User::factory()->create(['is_admin' => true]);
        $adminResponse = $this->actingAs($admin)->get('/en/pages/secret-draft');
        $adminResponse->assertStatus(200);
    }

    public function test_safe_content_renderer_strips_malicious_xss(): void
    {
        $malicious = "## Hello World\n\n<script>alert('pwned');</script><p onclick=\"hack()\">Safe Text</p>";
        $clean = SafeContentRenderer::render($malicious);

        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringContainsString('<h2>Hello World</h2>', $clean);
        $this->assertStringContainsString('Safe Text', $clean);
    }

    public function test_administrator_cannot_delete_core_system_pages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $aboutPage = ContentPage::where('slug', 'about')->firstOrFail();

        $response = $this->actingAs($admin)->delete("/admin/content/{$aboutPage->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('content_pages', ['slug' => 'about']);
    }
}
