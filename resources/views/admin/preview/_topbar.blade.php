{{-- ════════════════════ TOP BAR ════════════════════ --}}
<div id="editor-topbar">
    {{-- Logo --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="flex-shrink-0 text-white opacity-75" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
    </svg>
    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none small fw-bold tracking-wide transition">BlockCraft</a>
    <span class="text-white opacity-50 mx-1">/</span>
    <span class="text-white opacity-75 small fw-medium">{{ $site->name }}</span>
    <span class="text-white opacity-50 mx-1">/</span>
    <span class="text-white opacity-50 small">Visual Editor</span>

    <div class="flex-grow-1"></div>

    <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
       class="d-flex align-items-center gap-1 text-white opacity-75 hover-opacity-100 text-decoration-none small px-3 py-1 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 6h18M3 18h18"/>
        </svg>
        Table View
    </a>

    <a href="{{ route('client.page', $site->slug) }}" target="_blank"
       class="d-flex align-items-center gap-1 text-white opacity-75 hover-opacity-100 text-decoration-none small px-3 py-1 rounded transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Live Site ↗
    </a>

    {{-- Page background color picker --}}
    <div class="position-relative">
        <button type="button" onclick="toggleBgPicker()"
                class="btn btn-sm btn-outline-light d-flex align-items-center gap-2 px-3 py-1">
            <span id="bg-picker-swatch" style="display:inline-block;width:14px;height:14px;border-radius:50%;border:1px solid rgba(255,255,255,.5);background:{{ $site->background_color }};"></span>
            Background
        </button>
        <div id="bg-picker" class="position-absolute end-0 mt-2 p-3 bg-white rounded-3 shadow" style="display:none; z-index:3000; width:230px;">
            <p class="small fw-semibold text-dark mb-2">Page background</p>
            <div class="d-flex flex-wrap gap-2">
                @foreach (\App\Models\Site::backgroundPalette() as $swatch)
                    <button type="button"
                            class="bg-swatch-btn"
                            title="{{ $swatch['label'] }}"
                            data-value="{{ $swatch['value'] }}"
                            onclick="setBackground('{{ $swatch['value'] }}', this)"
                            style="width:28px;height:28px;border-radius:50%;cursor:pointer;background:{{ $swatch['value'] }};border:2px solid {{ $site->background_color === $swatch['value'] ? '#6366f1' : '#dee2e6' }};">
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <button onclick="openAddModal()"
            class="btn btn-sm btn-primary d-flex align-items-center gap-1 fw-bold px-3 py-1 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Block
    </button>
</div>
