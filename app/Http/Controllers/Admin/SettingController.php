<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'site_name' => SiteSetting::get('site_name', 'OmniTools'),
            'contact_email' => SiteSetting::get('contact_email', 'support@example.com'),
            'meta_description_default_en' => SiteSetting::get('meta_description_default_en', ''),
            'meta_description_default_bn' => SiteSetting::get('meta_description_default_bn', ''),
            'sitemap_enabled' => (bool) SiteSetting::get('sitemap_enabled', true),
            'analytics_enabled' => (bool) SiteSetting::get('analytics_enabled', false),
            'analytics_measurement_id' => SiteSetting::get('analytics_measurement_id', ''),
            'adsense_enabled' => (bool) SiteSetting::get('adsense_enabled', false),
            'adsense_publisher_id' => SiteSetting::get('adsense_publisher_id', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:255'],
            'meta_description_default_en' => ['nullable', 'string', 'max:300'],
            'meta_description_default_bn' => ['nullable', 'string', 'max:300'],
            'sitemap_enabled' => ['nullable', 'boolean'],
            'analytics_enabled' => ['nullable', 'boolean'],
            'analytics_measurement_id' => ['nullable', 'string', 'max:50', 'regex:/^(G-[A-Za-z0-9]+)?$/'],
            'adsense_enabled' => ['nullable', 'boolean'],
            'adsense_publisher_id' => ['nullable', 'string', 'max:50', 'regex:/^(ca-pub-[0-9]+)?$/'],
        ]);

        SiteSetting::set('site_name', $validated['site_name'], 'general', 'string', 'Platform brand name');
        SiteSetting::set('contact_email', $validated['contact_email'], 'general', 'string', 'Inquiry email');
        SiteSetting::set('meta_description_default_en', $validated['meta_description_default_en'] ?? '', 'seo', 'string', 'Default English meta description');
        SiteSetting::set('meta_description_default_bn', $validated['meta_description_default_bn'] ?? '', 'seo', 'string', 'Default Bangla meta description');
        SiteSetting::set('sitemap_enabled', $request->boolean('sitemap_enabled'), 'seo', 'boolean', 'Enable XML sitemap generation');
        SiteSetting::set('analytics_enabled', $request->boolean('analytics_enabled'), 'analytics', 'boolean', 'GA4 tracking active toggle');
        SiteSetting::set('analytics_measurement_id', $validated['analytics_measurement_id'] ?? '', 'analytics', 'string', 'GA4 measurement ID');
        SiteSetting::set('adsense_enabled', $request->boolean('adsense_enabled'), 'ads', 'boolean', 'Global AdSense toggle');
        SiteSetting::set('adsense_publisher_id', $validated['adsense_publisher_id'] ?? '', 'ads', 'string', 'AdSense publisher client ID');

        AuditLogger::log(
            action: 'settings.updated',
            entityType: 'SiteSetting',
            details: [
                'site_name' => $validated['site_name'],
                'analytics_enabled' => $request->boolean('analytics_enabled'),
                'adsense_enabled' => $request->boolean('adsense_enabled'),
            ]
        );

        return redirect()->route('admin.settings.index')->with('success', 'Site settings and SEO defaults successfully updated.');
    }
}
