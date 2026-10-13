<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_view_analytics_dashboard_with_concurrency(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        // Create human pageview in the last 2 minutes
        PageView::create([
            'session_hash' => 'hash_active_1',
            'url' => 'https://toolzeno.site/en/tools/css-minifier',
            'path' => 'en/tools/css-minifier',
            'is_bot' => false,
            'viewed_date' => now()->toDateString(),
            'created_at' => now()->subMinutes(2),
        ]);

        // Create older human pageview (15 minutes ago - outside 5m live concurrency window)
        PageView::create([
            'session_hash' => 'hash_older_1',
            'url' => 'https://toolzeno.site/en/tools/json-formatter',
            'path' => 'en/tools/json-formatter',
            'is_bot' => false,
            'viewed_date' => now()->toDateString(),
            'created_at' => now()->subMinutes(15),
        ]);

        // Create bot pageview (should not be counted as live concurrency)
        PageView::create([
            'session_hash' => 'hash_bot_1',
            'url' => 'https://toolzeno.site/en/tools/base64-encoder',
            'path' => 'en/tools/base64-encoder',
            'is_bot' => true,
            'viewed_date' => now()->toDateString(),
            'created_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.analytics.index'));

        $response->assertStatus(200);
        $response->assertSee('Audience & Visitor Intelligence', false);
        $response->assertSee('Live Concurrency');
        $response->assertSee('in last 5 mins');
        $response->assertSee('1 Active now (last 5m)');
    }

    public function test_analytics_tracker_extracts_cloudflare_ip(): void
    {
        $clientIp = '103.145.22.45';

        $response = $this->withHeaders([
            'CF-Connecting-IP' => $clientIp,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
        ])->get('/en');

        $response->assertStatus(200);

        $record = PageView::where('path', 'en')->latest('id')->first();
        $this->assertNotNull($record);
        $this->assertFalse($record->is_bot);

        // Subsequent hit with same CF-Connecting-IP should have identical session_hash
        $response2 = $this->withHeaders([
            'CF-Connecting-IP' => $clientIp,
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
        ])->get('/en/tools');

        $response2->assertStatus(200);
        $record2 = PageView::where('path', 'en/tools')->latest('id')->first();
        $this->assertNotNull($record2);
        $this->assertSame($record->session_hash, $record2->session_hash);
    }

    public function test_bot_agents_are_flagged(): void
    {
        $response = $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Linux; Android 11; moto g power (2022)) AppleWebKit/537.36 Chrome-Lighthouse',
        ])->get('/en');

        $response->assertStatus(200);

        $record = PageView::latest('id')->first();
        $this->assertNotNull($record);
        $this->assertTrue($record->is_bot);
    }
}
