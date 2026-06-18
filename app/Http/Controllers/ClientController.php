<?php

namespace App\Http\Controllers;

use App\Models\Site;

class ClientController extends Controller
{
    public function show(Site $site)
    {
        $blocks = $site->uiBlocks()->active()->ordered()->get();

        return view('client.home', compact('blocks', 'site'));
    }
}
