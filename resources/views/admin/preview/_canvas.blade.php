{{-- ════════════════════ CANVAS ════════════════════ --}}
<main id="editor-canvas">
    @if($allBlocks->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center text-center text-secondary py-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" class="mb-4 text-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            <p class="fs-5 fw-medium text-secondary">No blocks yet</p>
            <button onclick="openAddModal()" class="btn btn-primary mt-3 px-4 fw-semibold">
                Create your first block
            </button>
        </div>
    @else
        @foreach($allBlocks as $block)
        <div class="preview-block {{ !$block->is_active ? 'inactive' : '' }}"
             id="block-wrapper-{{ $block->id }}"
             data-id="{{ $block->id }}"
             data-type="{{ $block->type }}"
             data-active="{{ $block->is_active ? 'true' : 'false' }}"
             data-json="{!! htmlspecialchars(json_encode($block), ENT_QUOTES, 'UTF-8') !!}">

            {{-- Inactive badge --}}
            @if(!$block->is_active)
                <div class="inactive-badge" id="inactive-badge-{{ $block->id }}">Hidden on client</div>
            @else
                <div class="inactive-badge" id="inactive-badge-{{ $block->id }}" style="display:none">Hidden on client</div>
            @endif

            {{-- Exact same rendering as the client page --}}
            <div class="block-content" id="block-content-{{ $block->id }}">
                {{ app(\App\Services\BlockRenderer::class)->view($block) }}
            </div>

            {{-- Toolbar shown on hover (placed after content to ensure higher natural stacking) --}}
            <div class="block-toolbar">
                <div class="tb-btn drag-block-handle" style="cursor: grab" title="Drag to reorder">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    Drag
                </div>
                <div class="tb-divider"></div>
                <button class="tb-btn" onclick="quickToggle('{{ $block->id }}')" id="toggle-label-{{ $block->id }}" title="Toggle visibility">
                    {{ $block->is_active ? '👁 Hide' : '👁 Show' }}
                </button>
                <div class="tb-divider"></div>
                <button class="tb-btn primary" onclick="openEditor('{{ $block->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </button>
                <div class="tb-divider"></div>
                <button class="tb-btn danger" onclick="deleteBlock('{{ $block->id }}')" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Delete
                </button>
            </div>
        </div>
        @endforeach
    @endif
</main>
