<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ContentPage;
use App\Services\SafeContentRenderer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class PageController extends Controller
{
    private function checkPublishedOrAdmin(string $slug): ?ContentPage
    {
        try {
            if (! \Illuminate\Support\Facades\Schema::hasTable('content_pages')) {
                return null;
            }

            $page = ContentPage::where('slug', $slug)->first();
            if ($page !== null && $page->status === 'draft') {
                if (! Auth::check() || ! Auth::user()->isAdmin()) {
                    abort(404);
                }
            }
            return $page;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function about(string $locale = 'en'): View
    {
        $page = $this->checkPublishedOrAdmin('about');
        return view('pages.about', ['locale' => $locale, 'page' => $page]);
    }

    public function contact(string $locale = 'en'): View
    {
        $page = $this->checkPublishedOrAdmin('contact');
        return view('pages.contact', ['locale' => $locale, 'page' => $page]);
    }

    public function submitContact(Request $request, string $locale = 'en'): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        return redirect()->route('pages.contact', ['locale' => $locale])
            ->with('success', __('pages.contact.success_message'));
    }

    public function privacy(string $locale = 'en'): View
    {
        $page = $this->checkPublishedOrAdmin('privacy-policy');
        return view('pages.privacy', ['locale' => $locale, 'page' => $page]);
    }

    public function terms(string $locale = 'en'): View
    {
        $page = $this->checkPublishedOrAdmin('terms-of-service');
        return view('pages.terms', ['locale' => $locale, 'page' => $page]);
    }

    public function disclaimer(string $locale = 'en'): View
    {
        $page = $this->checkPublishedOrAdmin('disclaimer');
        return view('pages.disclaimer', ['locale' => $locale, 'page' => $page]);
    }

    public function show(string $locale, string $slug): View
    {
        $page = ContentPage::where('slug', $slug)->firstOrFail();

        if ($page->status === 'draft') {
            if (! Auth::check() || ! Auth::user()->isAdmin()) {
                abort(404);
            }
        }

        $renderedContent = SafeContentRenderer::render($page->getBody($locale));

        return view('pages.content', [
            'locale' => $locale,
            'page' => $page,
            'renderedContent' => $renderedContent,
        ]);
    }
}
