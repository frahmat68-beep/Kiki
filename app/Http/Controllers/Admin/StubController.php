<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StubController extends Controller
{
    public function home(): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    public function equipmentsIndex(): View
    {
        return view('admin.equipments.index');
    }

    public function equipmentsCreate(): View
    {
        return view('admin.equipments.create');
    }

    public function equipmentsStore(): RedirectResponse
    {
        return redirect()->route('admin.equipments.index');
    }

    public function equipmentsEdit(string $slug): View
    {
        return view('admin.equipments.edit', compact('slug'));
    }

    public function equipmentsUpdate(string $slug): RedirectResponse
    {
        return redirect()->route('admin.equipments.index');
    }

    public function equipmentsDestroy(string $slug): RedirectResponse
    {
        return redirect()->route('admin.equipments.index');
    }

    public function categoriesIndex(): View
    {
        return view('admin.categories.index');
    }

    public function categoriesCreate(): View
    {
        return view('admin.categories.create');
    }

    public function categoriesStore(): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function categoriesEdit(string $slug): View
    {
        return view('admin.categories.edit', compact('slug'));
    }

    public function categoriesUpdate(string $slug): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function categoriesDestroy(string $slug): RedirectResponse
    {
        return redirect()->route('admin.categories.index');
    }

    public function contentIndex(): View
    {
        return view('admin.content.index');
    }

    public function contentUpdate(): RedirectResponse
    {
        return redirect()->route('admin.content.index');
    }
}
