<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request): RedirectResponse
    {
        if (! in_array($locale, ['id', 'en'], true)) {
            $locale = 'id';
        }

        $request->session()->put('locale', $locale);

        if ($request->user() && Schema::hasColumn('users', 'preferred_locale')) {
            $request->user()->forceFill([
                'preferred_locale' => $locale,
            ])->save();
        }

        return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 30));
    }
}
