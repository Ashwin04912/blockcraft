{{-- Block HTML fragment — no layout, for AJAX refresh in the visual editor --}}
@includeIf('client.blocks.' . $block->type, [
    'config' => $block->config ?? [],
    'title'  => $block->title,
])
