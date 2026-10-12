<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersAndSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_security_headers_present_on_public_responses(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp);
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
    }

    public function test_admin_and_diagnostic_responses_include_noindex_robots_tag(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');

        $upResponse = $this->get('/up');
        $upResponse->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

    public function test_robots_txt_disallows_admin_and_diagnostic_routes(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Disallow: /admin', $robotsContent);
        $this->assertStringContainsString('Disallow: /up', $robotsContent);
        $this->assertStringContainsString('Sitemap: https://toolzeno.site/sitemap.xml', $robotsContent);
    }

    public function test_homepage_renders_truthful_website_json_ld_schema(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);

        $response->assertSee('<script type="application/ld+json">', false);
        $response->assertSee('"@type":"WebSite"', false);
        $response->assertSee('"name":"ToolZeno"', false);
        $response->assertSee('"potentialAction"', false);
    }

    public function test_canonical_and_hreflang_meta_tags_on_public_pages(): void
    {
        $enResponse = $this->get('/en/tools/word-counter');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('<link rel="canonical" href="https://toolzeno.site/en/tools/word-counter">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="en" href="https://toolzeno.site/en/tools/word-counter">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="bn" href="https://toolzeno.site/bn/tools/word-counter">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="x-default" href="https://toolzeno.site/en/tools/word-counter">', false);
        
        // Assert title deduplication: must not contain double suffix
        $enResponse->assertDontSee('ToolZeno — ToolZeno');
        $enResponse->assertSee('<title>Word Counter — Count Words and Characters | ToolZeno</title>', false);

        $bnResponse = $this->get('/bn/tools/word-counter');
        $bnResponse->assertStatus(200);
        $bnResponse->assertSee('<link rel="canonical" href="https://toolzeno.site/bn/tools/word-counter">', false);
        $bnResponse->assertSee('<link rel="alternate" hreflang="en" href="https://toolzeno.site/en/tools/word-counter">', false);
        $bnResponse->assertSee('<link rel="alternate" hreflang="bn" href="https://toolzeno.site/bn/tools/word-counter">', false);
    }

    public function test_tool_page_renders_truthful_web_application_and_breadcrumb_json_ld_schema(): void
    {
        $response = $this->get('/en/tools/word-counter');
        $response->assertStatus(200);

        $response->assertSee('<script type="application/ld+json">', false);
        $response->assertSee('"@type":"WebApplication"', false);
        $response->assertSee('"name":"Word & Character Counter"', false);
        $response->assertSee('"price":"0"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertDontSee('"aggregateRating"');
    }

    public function test_sitemap_xml_contains_tools_and_bilingual_hreflang(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $response->assertSee('https://toolzeno.site/en/tools/word-counter');
        $response->assertSee('https://toolzeno.site/bn/tools/word-counter');
        $response->assertSee('hreflang="en"', false);
        $response->assertSee('hreflang="bn"', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertSee('https://toolzeno.site/en/about');
        $response->assertSee('https://toolzeno.site/en/privacy-policy');
        $response->assertSee('https://toolzeno.site/en/terms-of-service');
        $response->assertSee('<lastmod>', false);
    }
}
