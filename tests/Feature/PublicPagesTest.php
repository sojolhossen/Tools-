<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class PublicPagesTest extends TestCase
{
    public function test_root_url_redirects_to_default_locale(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/en');
    }

    public function test_homepage_loads_in_english_and_bangla(): void
    {
        $responseEn = $this->get('/en');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Free, Fast & Secure Online Tools');

        $responseBn = $this->get('/bn');
        $responseBn->assertStatus(200);
        $responseBn->assertSee('ফ্রি, দ্রুত ও নিরাপদ অনলাইন টুলস');
    }

    public function test_directory_loads_in_english_and_bangla(): void
    {
        $responseEn = $this->get('/en/tools');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('All Tools');

        $responseBn = $this->get('/bn/tools');
        $responseBn->assertStatus(200);
        $responseBn->assertSee('সকল টুলস');
    }

    public function test_category_pages_render_and_validate(): void
    {
        $responseValid = $this->get('/en/tools/category/text');
        $responseValid->assertStatus(200);
        $responseValid->assertSee('Word & Character Counter');

        $responseInvalid = $this->get('/en/tools/category/unknown-category');
        $responseInvalid->assertStatus(404);
    }

    public function test_informational_pages_load_successfully(): void
    {
        $pages = ['about', 'contact', 'privacy-policy', 'terms-of-service', 'disclaimer'];

        foreach ($pages as $page) {
            $respEn = $this->get("/en/{$page}");
            $respEn->assertStatus(200);

            $respBn = $this->get("/bn/{$page}");
            $respBn->assertStatus(200);
        }
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post('/en/contact', [
            'name' => '',
            'email' => 'invalid-email',
            'subject' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_contact_form_accepts_valid_submission(): void
    {
        $response = $this->post('/en/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Tool Suggestion',
            'message' => 'Please add an SVG optimizer tool in Phase 2.',
        ]);

        $response->assertRedirect('/en/contact');
        $response->assertSessionHas('success');
    }

    public function test_sitemap_xml_returns_valid_response(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);
        $response->assertSee('/en/tools/word-counter', false);
        $response->assertSee('/bn/tools/word-counter', false);
        $response->assertSee('hreflang="bn"', false);
    }

    public function test_ads_txt_returns_valid_response(): void
    {
        $response = $this->get('/ads.txt');
        $response->assertStatus(200);
        $response->assertSee('pub-8336443496687004');
        $response->assertSee('f08c47fec0942fa0');
    }

    public function test_unknown_routes_return_proper_404(): void
    {
        $response = $this->get('/en/some-random-page-that-does-not-exist');
        $response->assertStatus(404);
    }

    public function test_whois_api_validates_domain_input(): void
    {
        $responseEmpty = $this->getJson('/api/tools/whois');
        $responseEmpty->assertStatus(400);

        $responseInvalid = $this->getJson('/api/tools/whois?domain=nodot');
        $responseInvalid->assertStatus(422);
    }

    public function test_whois_lookup_page_renders_successfully(): void
    {
        $responseEn = $this->get('/en/tools/whois-lookup');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('WHOIS Domain Lookup');
        $responseEn->assertSee('/api/tools/whois', false);

        $responseBn = $this->get('/bn/tools/whois-lookup');
        $responseBn->assertStatus(200);
    }

    public function test_webrtc_ip_leak_checker_page_renders_successfully(): void
    {
        $responseEn = $this->get('/en/tools/webrtc-ip-leak-checker');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('WebRTC IP Leak Checker');

        $responseBn = $this->get('/bn/tools/webrtc-ip-leak-checker');
        $responseBn->assertStatus(200);
    }

    public function test_dns_lookup_api_and_page_renders_successfully(): void
    {
        $responseEmpty = $this->getJson('/api/tools/dns-lookup');
        $responseEmpty->assertStatus(400);

        $responseInvalid = $this->getJson('/api/tools/dns-lookup?name=nodot');
        $responseInvalid->assertStatus(422);

        $responsePage = $this->get('/en/tools/dns-lookup');
        $responsePage->assertStatus(200);
        $responsePage->assertSee('DNS Record Lookup');
        $responsePage->assertSee('/api/tools/dns-lookup', false);
    }

    public function test_rdns_lookup_api_and_page_renders_successfully(): void
    {
        $responseEmpty = $this->getJson('/api/tools/rdns-lookup');
        $responseEmpty->assertStatus(400);

        $responseInvalid = $this->getJson('/api/tools/rdns-lookup?ip=invalid-ip');
        $responseInvalid->assertStatus(422);

        $responsePage = $this->get('/en/tools/reverse-dns-lookup');
        $responsePage->assertStatus(200);
        $responsePage->assertSee('Reverse DNS (rDNS / PTR) Lookup');
        $responsePage->assertSee('/api/tools/rdns-lookup', false);
    }

    public function test_http_headers_lookup_api_and_page_renders(): void
    {
        $responseEmpty = $this->getJson('/api/tools/http-headers');
        $responseEmpty->assertStatus(400);

        $responsePage = $this->get('/en/tools/http-headers-lookup');
        $responsePage->assertStatus(200);
        $responsePage->assertSee('HTTP Header Checker');
    }
}




