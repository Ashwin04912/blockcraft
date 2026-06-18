<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUiBlockRequest;
use App\Http\Requests\Admin\UpdateUiBlockRequest;
use App\Models\Site;
use App\Models\UiBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UiBlockController extends Controller
{
    // ─── Table-view CRUD ────────────────────────────────────────────────

    public function index(Site $site)
    {
        $blocks = $site->uiBlocks()->orderBy('display_order')->get();

        return view('admin.ui_blocks.index', compact('site', 'blocks'));
    }

    public function create(Site $site)
    {
        return view('admin.ui_blocks.create', compact('site'));
    }

    public function store(StoreUiBlockRequest $request, Site $site)
    {
        $data = $request->validated();
        $data['site_id']       = $site->id;
        $data['display_order'] = $data['display_order'] ?? ($site->uiBlocks()->max('display_order') + 1);
        $data['is_active']     = $request->boolean('is_active', true);

        $block = UiBlock::create($data);

        if ($request->wantsJson()) {
            return response()->json($block);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block created successfully.');
    }

    public function edit(Site $site, UiBlock $uiBlock)
    {
        abort_if($uiBlock->site_id !== $site->id, 404);

        return view('admin.ui_blocks.edit', compact('site', 'uiBlock'));
    }

    public function update(UpdateUiBlockRequest $request, Site $site, UiBlock $uiBlock)
    {
        abort_if($uiBlock->site_id !== $site->id, 404);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', false);

        $uiBlock->update($data);

        if ($request->wantsJson()) {
            return response()->json($uiBlock);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block updated successfully.');
    }

    public function destroy(Site $site, UiBlock $uiBlock)
    {
        abort_if($uiBlock->site_id !== $site->id, 404);

        $uiBlock->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.sites.ui-blocks.index', $site)
            ->with('success', 'Block deleted successfully.');
    }

    // ─── AJAX endpoints ─────────────────────────────────────────────────

    public function reorder(Request $request, Site $site)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->input('order') as $position => $id) {
                UiBlock::where('id', $id)->update(['display_order' => $position]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    public function toggle(Site $site, UiBlock $uiBlock)
    {
        abort_if($uiBlock->site_id !== $site->id, 404);
        $uiBlock->update(['is_active' => ! $uiBlock->is_active]);

        return response()->json(['is_active' => $uiBlock->is_active]);
    }

    // ─── Visual editor ───────────────────────────────────────────────────

    public function visualEditor(Site $site)
    {
        $allBlocks = $site->uiBlocks()->orderBy('display_order')->get();

        return view('admin.preview', compact('site', 'allBlocks'));
    }

    public function renderBlock(Site $site, UiBlock $uiBlock)
    {
        abort_if($uiBlock->site_id !== $site->id, 404);

        return view('admin.partials.block_render', ['block' => $uiBlock]);
    }
}
