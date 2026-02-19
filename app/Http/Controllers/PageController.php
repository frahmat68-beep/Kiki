<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function catalog(): View
    {
        return view('equipments.index');
    }

    public function catalogRedirect(): RedirectResponse
    {
        return redirect()->route('catalog');
    }

    public function product(string $slug): View
    {
        return view('equipments.show', compact('slug'));
    }

    public function equipmentRedirect(string $slug): RedirectResponse
    {
        return redirect()->route('product.show', $slug);
    }

    public function contact(): View
    {
        return view('contact');
    }
}
