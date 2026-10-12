@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Configure Ad Slot: {{ $placement->name }}</h1>
            <p class="text-xs text-slate-400">
                Identifier: <code class="font-mono text-indigo-400">{{ $placement->identifier }}</code>
            </p>
        </div>
        <a href="{{ route('admin.ads.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
            &larr; Back to Placements
        </a>
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

    <form action="{{ route('admin.ads.update', $placement) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-300">Display Name</label>
                <input type="text" name="name" value="{{ old('name', $placement->name) }}" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Eligible Location</label>
                    <select name="page_location" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="all" {{ old('page_location', $placement->page_location) === 'all' ? 'selected' : '' }}>Global (All Pages)</option>
                        <option value="home" {{ old('page_location', $placement->page_location) === 'home' ? 'selected' : '' }}>Homepage</option>
                        <option value="tools" {{ old('page_location', $placement->page_location) === 'tools' ? 'selected' : '' }}>Directory Overview</option>
                        <option value="tool-view" {{ old('page_location', $placement->page_location) === 'tool-view' ? 'selected' : '' }}>Tool Workspaces</option>
                        <option value="content-page" {{ old('page_location', $placement->page_location) === 'content-page' ? 'selected' : '' }}>Articles & Policies</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Provider Network</label>
                    <select name="provider" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="adsense" {{ old('provider', $placement->provider) === 'adsense' ? 'selected' : '' }}>Google AdSense</option>
                        <option value="custom_partner" {{ old('provider', $placement->provider) === 'custom_partner' ? 'selected' : '' }}>Direct Sponsor / Partner</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300">Provider Slot ID (data-ad-slot)</label>
                    <input type="text" name="slot_id" value="{{ old('slot_id', $placement->slot_id) }}" placeholder="e.g. 1234567890" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs font-mono text-indigo-300 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300">Display Format</label>
                    <select name="format" required class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        <option value="responsive" {{ old('format', $placement->format) === 'responsive' ? 'selected' : '' }}>Responsive (Fluid)</option>
                        <option value="horizontal" {{ old('format', $placement->format) === 'horizontal' ? 'selected' : '' }}>Horizontal Leaderboard (728x90)</option>
                        <option value="rectangle" {{ old('format', $placement->format) === 'rectangle' ? 'selected' : '' }}>Medium Rectangle (300x250)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300">Administrative Notes</label>
                <textarea name="notes" rows="2" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('notes', $placement->notes) }}</textarea>
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $placement->is_active) ? 'checked' : '' }} class="rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                    <span class="text-xs text-slate-300 font-medium">Activate this placement immediately</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.ads.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-xs text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                Cancel
            </a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors">
                Save Placement Configuration
            </button>
        </div>
    </form>
</div>
@endsection
