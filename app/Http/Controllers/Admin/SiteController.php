<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /**
     * Admin dashboard — list only sites owned by the current user.
     */
    public function index(Request $request)
    {
        $sites = $request->user()->sites()
            ->withCount('uiBlocks')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact('sites'));
    }

    /**
     * Store a new site, owned by the current user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'slug'        => ['required', 'string', 'max:100', 'unique:sites,slug',
                              'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
        ]);

        $validated['owner_id'] = $request->user()->id;

        $site = Site::create($validated);

        return redirect()->route('admin.sites.visual-editor', $site)
            ->with('success', "Site \"{$site->name}\" created — start adding blocks!");
    }

    /**
     * Delete a site (blocks cascade-deleted by DB constraint).
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);

        $name = $site->name;
        $blockCount = $site->uiBlocks()->count();

        Log::warning('Site deleted (cascades UI blocks)', [
            'site_id'     => $site->id,
            'slug'        => $site->slug,
            'block_count' => $blockCount,
            'user_id'     => request()->user()?->id,
        ]);

        $site->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', "Site \"{$name}\" has been deleted.");
    }
}
