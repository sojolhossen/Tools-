<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\AdPlacement;
use App\Models\AuditLog;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\AuditLogger;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsAndDiagnosticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_administrator_can_update_settings(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->put('/admin/settings', [
            'site_name' => 'OmniTools Pro',
            'contact_email' => 'contact@omnitools.test',
            'meta_description_default_en' => 'Pro tools for everyone.',
            'meta_description_default_bn' => 'সবার জন্য সেরা টুলস।',
            'sitemap_enabled' => true,
            'analytics_enabled' => true,
            'analytics_measurement_id' => 'G-TEST12345',
            'adsense_enabled' => true,
            'adsense_publisher_id' => 'ca-pub-1234567890123456',
        ]);

        $response->assertRedirect('/admin/settings');

        $this->assertSame('OmniTools Pro', SiteSetting::get('site_name'));
        $this->assertTrue((bool) SiteSetting::get('analytics_enabled'));
        $this->assertSame('G-TEST12345', SiteSetting::get('analytics_measurement_id'));

        // Verify that public page now includes GA4 script
        $publicResp = $this->get('/en');
        $publicResp->assertStatus(200);
        $publicResp->assertSee('https://www.googletagmanager.com/gtag/js?id=G-TEST12345', false);
        $publicResp->assertSee("gtag('config', 'G-TEST12345')", false);
    }

    public function test_ad_placement_can_be_configured_and_toggled(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $placement = AdPlacement::where('identifier', 'footer-banner')->firstOrFail();

        $response = $this->actingAs($admin)->put("/admin/ads/{$placement->id}", [
            'name' => 'Footer Banner Responsive',
            'page_location' => 'all',
            'provider' => 'adsense',
            'slot_id' => '987654321',
            'format' => 'responsive',
            'is_active' => true,
        ]);

        $response->assertRedirect('/admin/ads');
        $placement->refresh();
        $this->assertSame('987654321', $placement->slot_id);
        $this->assertTrue($placement->is_active);

        // Toggle back
        $this->actingAs($admin)->post("/admin/ads/{$placement->id}/toggle");
        $placement->refresh();
        $this->assertFalse($placement->is_active);
    }

    public function test_administrator_can_view_diagnostics(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/diagnostics');
        $response->assertStatus(200);
        $response->assertSee('System Status & Diagnostic Health', false);
        $response->assertSee(PHP_VERSION);
    }

    public function test_audit_logger_redacts_sensitive_keys(): void
    {
        AuditLogger::log(
            action: 'test.sensitive_action',
            entityType: 'User',
            entityId: 1,
            details: [
                'username' => 'testadmin',
                'password' => 'SuperSecretPassword!',
                'token' => 'Bearer sensitive-token-123',
                'app_key' => 'base64:sensitive-key',
            ]
        );

        $log = AuditLog::where('action', 'test.sensitive_action')->firstOrFail();

        $this->assertSame('testadmin', $log->details['username']);
        $this->assertSame('[REDACTED]', $log->details['password']);
        $this->assertSame('[REDACTED]', $log->details['token']);
        $this->assertSame('[REDACTED]', $log->details['app_key']);
    }

    public function test_disabled_integrations_do_not_inject_scripts(): void
    {
        SiteSetting::set('analytics_enabled', '0');
        SiteSetting::set('adsense_enabled', '0');

        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertDontSee('googletagmanager.com/gtag/js');
        $response->assertDontSee('pagead2.googlesyndication.com');
    }
}
