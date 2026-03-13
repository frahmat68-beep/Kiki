<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    public function switch(string $theme, Request $request): RedirectResponse
    {
        if (! in_array($theme, ['system', 'dark', 'light'], true)) {
            $theme = 'light';
        }

        $request->session()->put('theme', $theme);

        if ($request->user() && schema_column_exists_cached('users', 'preferred_theme')) {
            $request->user()->forceFill([
                'preferred_theme' => $theme,
            ])->save();
        }

        $target = $this->resolveRedirectTarget($request, $request->query('redirect'));

        return redirect()->to($target)->withCookie(cookie('theme', $theme, 60 * 24 * 30));
    }

    private function resolveRedirectTarget(Request $request, mixed $redirectTarget): string
    {
        $fallback = url()->previous() ?: route('home');

        if (! is_string($redirectTarget) || trim($redirectTarget) === '') {
            return $fallback;
        }

        $candidate = trim($redirectTarget);
        if (Str::startsWith($candidate, '/')) {
            return $candidate;
        }

        $candidateParts = parse_url($candidate);
        if (! is_array($candidateParts) || empty($candidateParts['host'])) {
            return $fallback;
        }

        $allowedHosts = collect([
            parse_url($request->getSchemeAndHttpHost(), PHP_URL_HOST),
            parse_url((string) config('app.url'), PHP_URL_HOST),
        ])->filter()->values()->all();

        return in_array($candidateParts['host'], $allowedHosts, true) ? $candidate : $fallback;
    }
}
