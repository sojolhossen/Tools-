@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Media Asset Management</h1>
            <p class="text-xs text-slate-400">
                Upload and manage verified image assets for tutorials, guides, and articles.
            </p>
        </div>
    </div>

    <!-- Upload Box -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-indigo-400 mb-3">Upload New Image Asset</h2>
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-end gap-4">
            @csrf

            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-300">Select Image (Max 5MB: JPG, PNG, WebP, SVG, GIF)</label>
                <input type="file" name="image" required accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" class="mt-1 block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
            </div>

            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-300">Alt Text (English)</label>
                <input type="text" name="alt_text_en" placeholder="Accessible description of the image" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-3.5 py-2 text-xs text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>

            <div>
                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition-colors shrink-0">
                    Upload Asset
                </button>
            </div>
        </form>
    </div>

    <!-- Assets Grid -->
    <div class="rounded-2xl bg-slate-900 border border-slate-800 p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Uploaded Media Library</h2>

        @if($assets->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($assets as $asset)
                    <div class="rounded-xl bg-slate-950 border border-slate-800 overflow-hidden flex flex-col justify-between group">
                        <div class="aspect-square bg-slate-900 flex items-center justify-center p-2 overflow-hidden relative">
                            <img src="{{ $asset->getUrl() }}" alt="{{ $asset->alt_text_en ?? $asset->original_name }}" class="object-cover max-h-full max-w-full rounded">
                        </div>
                        <div class="p-3 space-y-1.5 border-t border-slate-800/80">
                            <p class="truncate text-xs font-semibold text-white" title="{{ $asset->original_name }}">{{ $asset->original_name }}</p>
                            <p class="text-[10px] text-slate-500 font-mono">{{ round($asset->size_bytes / 1024, 1) }} KB &middot; {{ strtoupper($asset->mime_type) }}</p>
                            
                            <div class="pt-2 flex items-center justify-between">
                                <button 
                                    type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $asset->getUrl() }}'); alert('Image URL copied to clipboard!');" 
                                    class="text-[10px] font-semibold text-indigo-400 hover:text-indigo-300"
                                >
                                    Copy URL
                                </button>

                                <form action="{{ route('admin.media.destroy', $asset) }}" method="POST" onsubmit="return confirm('Permanently delete this media asset?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] font-semibold text-rose-400 hover:text-rose-300">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $assets->links() }}
            </div>
        @else
            <div class="py-12 text-center text-xs text-slate-500">
                No media assets uploaded yet. Upload images above to embed them into tutorials or articles.
            </div>
        @endif
    </div>
</div>
@endsection
