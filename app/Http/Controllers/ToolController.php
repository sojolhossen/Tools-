<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Support\Tools\ToolRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final class ToolController extends Controller
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $tool = $this->toolRegistry->find($slug);

        if ($tool === null || !$tool->isActive()) {
            abort(404);
        }

        if (!$tool->supportsLocale($locale)) {
            abort(404);
        }

        $relatedTools = $this->toolRegistry->getRelated($slug, 3);

        return view($tool->view, [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
            'locale' => $locale,
        ]);
    }
}
