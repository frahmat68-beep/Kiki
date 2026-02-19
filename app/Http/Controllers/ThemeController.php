<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ThemeController extends Controller
{
    public function switch(string $theme, Request $request): RedirectResponse
    {
        if (! in_array($theme, ['system', 'dark', 'light'], true)) {
            $theme = 'light';
        }

        $request->session()->put('theme', $theme);

        if ($request->user() && Schema::hasColumn('users', 'preferred_theme')) {
            $request->user()->forceFill([
                'preferred_theme' => $theme,
            ])->save();
        }

        return redirect()->back()->withCookie(cookie('theme', $theme, 60 * 24 * 30));
    }
}
