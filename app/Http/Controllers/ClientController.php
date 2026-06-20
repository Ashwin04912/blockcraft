<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Support\Facades\Cache;

class ClientController extends Controller
{
    public function show(Site $site)
    {
        $blocks = Cache::rememberForever(
            "site:{$site->id}:active-blocks",
            fn () => $site->uiBlocks()->active()->ordered()->get()
        );

        return view('client.home', compact('blocks'));
    }
}
