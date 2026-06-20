<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUiBlockRequest;
use App\Http\Requests\Admin\UpdateUiBlockRequest;
use App\Models\Site;
use App\Models\UiBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UiBlockController extends Controller
{
    // ─── Table-view CRUD ────────────────────────────────────────────────

    public function index(Site $site)
    {
        $this->authorize('view', $site);

        $blocks = $site->uiBlocks()->orderBy('display_order')->orderBy('id')->get();

        return view('admin.ui_blocks.index', compact('site', 'blocks'));
    }

    public function create(Site $site)
    {
        $this->authorize('update', $site);

        return view('admin.ui_blocks.create', compact('site'));
    }

    public function store(StoreUiBlockRequest $request, Site $site)
    {
        $this->authorize('update', $site);

        $data = $request->validated();
        $data['site_id']   = $site->id;
        $data['is_active'] = $request->boolean('is_active', true);

        $block = DB::transaction(function () use ($data, $site) {
            $data['display_order'] = $data['display_order']
                ?? (($site->uiBlocks()->lockForUpdate()->max('display_order') ?? -1) + 1);

            return UiBlock::create($data);
        });

        $this->forgetBlocksCache($site);

        if ($request->wantsJson()) {
            return response()->json($block);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block created successfully.');
    }

    // public function edit(Site $site, UiBlock $uiBlock)
    // {
    //     dd("hwllo edit block reached");

    //     $this->authorize('update', $site);
    //     abort_if($uiBlock->site_id !== $site->id, 404);

    //     return view('admin.ui_blocks.edit', compact('site', 'uiBlock'));
    // }

    public function update(UpdateUiBlockRequest $request, Site $site, UiBlock $uiBlock)
    {
        $this->authorize('update', $site);
        abort_if($uiBlock->site_id !== $site->id, 404);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', false);

        $uiBlock->update($data);

        $this->forgetBlocksCache($site);

        if ($request->wantsJson()) {
            return response()->json($uiBlock);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block updated successfully.');
    }

    public function destroy(Site $site, UiBlock $uiBlock)
    {
        $this->authorize('update', $site);
        abort_if($uiBlock->site_id !== $site->id, 404);

        $uiBlock->delete();

        $this->forgetBlocksCache($site);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block deleted successfully.');
    }

    // ─── AJAX endpoints ─────────────────────────────────────────────────

    public function reorder(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        $ids = $request->input('order');

        DB::transaction(function () use ($ids, $site) {
            $ownedIds = $site->uiBlocks()->lockForUpdate()->pluck('id');

            if ($ownedIds->count() !== count($ids) || $ownedIds->diff($ids)->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'order' => 'Submitted block IDs do not match this site\'s blocks.',
                ]);
            }

            foreach ($ids as $position => $id) {
                UiBlock::where('id', $id)->where('site_id', $site->id)->update(['display_order' => $position]);
            }
        });

        $this->forgetBlocksCache($site);

        Log::info('UI blocks reordered', [
            'site_id' => $site->id,
            'order'   => $ids,
            'user_id' => $request->user()?->id,
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function toggle(Site $site, UiBlock $uiBlock)
    {
        $this->authorize('update', $site);
        abort_if($uiBlock->site_id !== $site->id, 404);
        $uiBlock->update(['is_active' => ! $uiBlock->is_active]);

        $this->forgetBlocksCache($site);

        return response()->json(['is_active' => $uiBlock->is_active]);
    }

    // ─── Visual editor ───────────────────────────────────────────────────

    public function visualEditor(Site $site)
    {
        $this->authorize('view', $site);

        $allBlocks = $site->uiBlocks()->orderBy('display_order')->orderBy('id')->get();

        return view('admin.preview', compact('site', 'allBlocks'));
    }

    public function renderBlock(Site $site, UiBlock $uiBlock)
    {
        $this->authorize('view', $site);
        abort_if($uiBlock->site_id !== $site->id, 404);

        return view('admin.partials.block_render', ['block' => $uiBlock]);
    }

    /** Invalidate the cached active-block list this site's public page reads from. */
    protected function forgetBlocksCache(Site $site): void
    {
        Cache::forget("site:{$site->id}:active-blocks");
    }
}
