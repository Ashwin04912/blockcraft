{{-- ════════════════════ SIDEBAR ════════════════════ --}}
<div id="editor-sidebar">
    <div id="sidebar-header">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <div class="d-flex align-items-center gap-2">
                <span id="sb-type-badge" class="badge bg-primary bg-opacity-10 text-primary text-uppercase px-2 rounded-pill">—</span>
                <span id="sb-block-id" class="small text-secondary">#—</span>
            </div>
            <button onclick="closeSidebar()"
                    class="btn btn-sm btn-light text-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <h2 id="sb-title-display" class="fs-5 fw-bold text-dark m-0">Block Editor</h2>
    </div>

    <div id="sidebar-body">

        {{-- Block title --}}
        <div class="sb-field">
            <label class="sb-label">Block Title</label>
            <input type="text" id="sb-title" class="sb-input" placeholder="e.g. Hero Banner">
        </div>

        {{-- Size selector --}}
        <div class="sb-field" id="sb-size-field">
            <label class="sb-label">Block Size</label>
            <div class="size-btn-group">
                <button class="size-btn" data-size="sm" onclick="selectSize('sm')">S — Compact</button>
                <button class="size-btn active" data-size="md" onclick="selectSize('md')">M — Normal</button>
                <button class="size-btn" data-size="lg" onclick="selectSize('lg')">L — Large</button>
            </div>
        </div>

        {{-- Background Style --}}
        <div class="sb-field" id="sb-bg-style-field">
            <label class="sb-label">Background Style</label>
            <select id="sb-bg-style" class="sb-input">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="gradient">Gradient</option>
            </select>
        </div>

        {{-- Active toggle --}}
        <div class="sb-field d-flex align-items-center justify-content-between">
            <div>
                <p class="small fw-semibold text-dark mb-0">Visible on Client Page</p>
                <p class="text-secondary mb-0" style="font-size: 0.75rem;">Toggle to show/hide this block</p>
            </div>
            <label class="toggle-switch mb-0">
                <input type="checkbox" id="sb-is-active">
                <span class="toggle-slider"></span>
            </label>
        </div>

        <div style="height:1px;background:#f3f4f6;margin:16px 0"></div>

        {{-- Dynamic config fields injected here by JS --}}
        <div id="config-fields"></div>

    </div>

    <div id="sidebar-footer">
        <button onclick="saveSidebar()"
                class="btn btn-primary w-100 fw-semibold py-2 mb-2">
            Save Changes
        </button>
        <button onclick="closeSidebar()"
                class="btn btn-light w-100 text-secondary fw-medium py-2">
            Cancel
        </button>
    </div>
</div>
