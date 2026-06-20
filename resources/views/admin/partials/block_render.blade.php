{{-- Block HTML fragment — no layout, for AJAX refresh in the visual editor --}}
{{ app(\App\Services\BlockRenderer::class)->view($block) }}
