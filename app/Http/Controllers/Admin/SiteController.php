<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /**
     * Admin dashboard — list all sites as cards.
     */
    public function index()
    {
        $sites = Site::withCount('uiBlocks')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact('sites'));
    }

    /**
     * Store a new site.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'slug'        => ['required', 'string', 'max:100', 'unique:sites,slug',
                              'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
        ]);

        $site = Site::create($validated);

        return redirect()->route('admin.sites.visual-editor', $site)
            ->with('success', "Site \"{$site->name}\" created — start adding blocks!");
    }

    /**
     * Delete a site (blocks cascade-deleted by DB constraint).
     */
    public function destroy(Site $site)
    {
        $name = $site->name;
        $site->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', "Site \"{$name}\" has been deleted.");
    }
}
