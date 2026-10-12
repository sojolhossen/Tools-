@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-xl font-bold text-white tracking-tight">Site Settings & SEO Configuration</h1>
        <p class="text-xs text-slate-400">
            Control platform branding, default search engine metadata, and third-party integration toggles.
        </p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-rose-500/10 border border-rose-500/20 p-4 text-xs text-rose-300">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Branding -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">General Branding & Communications</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Brand Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Official Inquiries & Contact Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- SEO Defaults -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Global SEO Defaults</h2>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Default Meta Description (English)</label>
                <textarea name="meta_description_default_en" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_default_en', $settings['meta_description_default_en']) }}</textarea>
                <p class="mt-1 text-[11px] text-slate-500">Recommended length: 140–160 characters for optimal Google search snippets.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Default Meta Description (Bangla)</label>
                <textarea name="meta_description_default_bn" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('meta_description_default_bn', $settings['meta_description_default_bn']) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="sitemap_enabled" value="1" {{ old('sitemap_enabled', $settings['sitemap_enabled']) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <span class="text-xs text-slate-300 font-medium">Generate Dynamic XML Sitemap (/sitemap.xml) for search engines</span>
                </label>
            </div>
        </div>

        <!-- Analytics Integration -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Web Analytics (Google Analytics 4)</h2>
            <p class="text-xs text-slate-400">Strictly privacy-compliant tracking. Disabled by default.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">GA4 Measurement ID</label>
                    <input type="text" name="analytics_measurement_id" value="{{ old('analytics_measurement_id', $settings['analytics_measurement_id']) }}" placeholder="G-XXXXXXXXXX" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs font-mono text-indigo-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="analytics_enabled" value="1" {{ old('analytics_enabled', $settings['analytics_enabled']) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <span class="text-xs text-slate-300 font-medium">Enable GA4 Client-Side Script</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Monetization / Ads Integration -->
        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400">Monetization (Google AdSense)</h2>
            <p class="text-xs text-slate-400">Global advertising switch. Placements remain invisible when disabled.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">AdSense Publisher ID</label>
                    <input type="text" name="adsense_publisher_id" value="{{ old('adsense_publisher_id', $settings['adsense_publisher_id']) }}" placeholder="ca-pub-XXXXXXXXXXXXXXXX" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs font-mono text-indigo-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="adsense_enabled" value="1" {{ old('adsense_enabled', $settings['adsense_enabled']) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                        <span class="text-xs text-slate-300 font-medium">Enable Advertising Across Configured Slots</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Save System Settings
            </button>
        </div>
    </form>
</div>
@endsection
