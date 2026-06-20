<?php

namespace App\Services;

use App\Models\UiBlock;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class BlockRenderer
{
    /**
     * Resolve a block to its Blade view, falling back to a generic
     * placeholder (and logging) when no partial exists for its type.
     */
    public function view(UiBlock $block): View
    {
        $partial = 'client.blocks.' . $block->type;

        if (! view()->exists($partial)) {
            Log::warning('Missing Blade partial for block type', [
                'block_id' => $block->id,
                'site_id'  => $block->site_id,
                'type'     => $block->type,
            ]);

            $partial = 'client.blocks._fallback';
        }

        return view($partial, [
            'config' => $block->config ?? [],
            'title'  => $block->title,
        ]);
    }
}
