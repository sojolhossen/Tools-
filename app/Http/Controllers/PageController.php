<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PageController extends Controller
{
    public function about(string $locale = 'en'): View
    {
        return view('pages.about', ['locale' => $locale]);
    }

    public function contact(string $locale = 'en'): View
    {
        return view('pages.contact', ['locale' => $locale]);
    }

    public function submitContact(Request $request, string $locale = 'en'): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // In Phase 1, log contact message or flash success message
        // No unsolicited external dispatch before mailing provider setup
        return redirect()->route('pages.contact', ['locale' => $locale])
            ->with('success', __('pages.contact.success_message'));
    }

    public function privacy(string $locale = 'en'): View
    {
        return view('pages.privacy', ['locale' => $locale]);
    }

    public function terms(string $locale = 'en'): View
    {
        return view('pages.terms', ['locale' => $locale]);
    }

    public function disclaimer(string $locale = 'en'): View
    {
        return view('pages.disclaimer', ['locale' => $locale]);
    }
}
