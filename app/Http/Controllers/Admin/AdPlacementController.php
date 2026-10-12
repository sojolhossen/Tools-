<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPlacement;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdPlacementController extends Controller
{
    public function index(): View
    {
        $placements = AdPlacement::orderBy('id')->get();
        return view('admin.ads.index', compact('placements'));
    }

    public function edit(AdPlacement $placement): View
    {
        return view('admin.ads.edit', compact('placement'));
    }

    public function update(Request $request, AdPlacement $placement): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'page_location' => ['required', 'string', Rule::in(['all', 'home', 'tools', 'tool-view', 'content-page'])],
            'provider' => ['required', 'string', Rule::in(['adsense', 'custom_partner'])],
            'slot_id' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'format' => ['required', 'string', Rule::in(['responsive', 'horizontal', 'rectangle'])],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $placement->update([
            'name' => $validated['name'],
            'page_location' => $validated['page_location'],
            'provider' => $validated['provider'],
            'slot_id' => $validated['slot_id'] ?? '',
            'format' => $validated['format'],
            'is_active' => $request->boolean('is_active'),
            'notes' => $validated['notes'] ?? null,
        ]);

        AuditLogger::log(
            action: 'ad_placement.updated',
            entityType: 'AdPlacement',
            entityId: $placement->id,
            details: [
                'identifier' => $placement->identifier,
                'is_active' => $placement->is_active,
                'slot_id' => $placement->slot_id,
            ]
        );

        return redirect()->route('admin.ads.index')->with('success', "Ad placement [{$placement->name}] updated.");
    }

    public function toggle(AdPlacement $placement): RedirectResponse
    {
        $newStatus = ! $placement->is_active;
        $placement->update(['is_active' => $newStatus]);

        AuditLogger::log(
            action: 'ad_placement.toggled',
            entityType: 'AdPlacement',
            entityId: $placement->id,
            details: ['identifier' => $placement->identifier, 'is_active' => $newStatus]
        );

        return back()->with('success', "Placement [{$placement->name}] is now " . ($newStatus ? 'active' : 'inactive') . '.');
    }
}
