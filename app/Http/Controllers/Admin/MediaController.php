<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use App\Models\MediaAsset;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaController extends Controller
{
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg+xml',
    ];

    public function index(): View
    {
        $assets = MediaAsset::latest('id')->paginate(18);
        return view('admin.media.index', compact('assets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'file', 'max:5120', 'mimes:jpeg,png,webp,gif,svg'],
            'alt_text_en' => ['nullable', 'string', 'max:255'],
            'alt_text_bn' => ['nullable', 'string', 'max:255'],
        ]);

        $file = $request->file('image');
        if (! $file || ! $file->isValid()) {
            return back()->with('error', 'Invalid upload payload.');
        }

        // Strict server-side MIME verification
        $realMime = $file->getMimeType();
        if (! in_array($realMime, self::ALLOWED_MIMES, true)) {
            return back()->with('error', "Disallowed file type [{$realMime}]. Only standard web images are permitted.");
        }

        // Unpredictable internal filename
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $safeFilename = Str::random(32) . '.' . strtolower($extension);

        // Store outside direct script execution tree
        $path = $file->storeAs('media', $safeFilename, 'public');

        $asset = MediaAsset::create([
            'filename' => $safeFilename,
            'original_name' => $file->getClientOriginalName(),
            'disk' => 'public',
            'path' => $path,
            'mime_type' => (string) $realMime,
            'size_bytes' => $file->getSize(),
            'alt_text_en' => $request->input('alt_text_en'),
            'alt_text_bn' => $request->input('alt_text_bn'),
            'user_id' => Auth::id(),
        ]);

        AuditLogger::log(
            action: 'media.uploaded',
            entityType: 'MediaAsset',
            entityId: $asset->id,
            details: [
                'filename' => $safeFilename,
                'mime' => $realMime,
                'size' => $asset->size_bytes,
            ]
        );

        return redirect()->route('admin.media.index')->with('success', 'Media asset uploaded successfully.');
    }

    public function destroy(MediaAsset $media): RedirectResponse
    {
        // Reference check in published pages
        $isReferenced = ContentPage::where('body_en', 'like', "%{$media->filename}%")
            ->orWhere('body_bn', 'like', "%{$media->filename}%")
            ->exists();

        if ($isReferenced) {
            return back()->with('error', "Safety Guard: Cannot delete [{$media->original_name}] because it is currently embedded in one or more content pages.");
        }

        Storage::disk($media->disk)->delete($media->path);
        $filename = $media->original_name;
        $media->delete();

        AuditLogger::log(
            action: 'media.deleted',
            entityType: 'MediaAsset',
            details: ['original_name' => $filename]
        );

        return redirect()->route('admin.media.index')->with('success', "Media asset [{$filename}] deleted.");
    }
}
