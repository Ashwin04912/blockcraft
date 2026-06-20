{{-- ════════════════════ ADD BLOCK MODAL ════════════════════ --}}
<div id="add-modal-backdrop" onclick="closeAddModal()">
    <div id="add-modal" onclick="event.stopPropagation()">
        <div id="add-modal-header">
            <h2 class="fs-5 fw-bold text-dark mb-0">Add New Block</h2>
            <p class="text-secondary small mb-0">Choose a type and configure, then save to see it on the canvas.</p>
        </div>
        <div id="add-modal-body" class="d-flex flex-column gap-3">

            {{-- Title --}}
            <div class="sb-field">
                <label class="sb-label">Block Title <span class="text-danger">*</span></label>
                <input type="text" id="add-title" class="sb-input" placeholder="e.g. Hero Banner">
            </div>

            {{-- Type --}}
            <div class="sb-field">
                <label class="sb-label">Type <span class="text-danger">*</span></label>
                <select id="add-type" class="sb-input" onchange="switchAddModalType(this.value)">
                    <option value="banner">Banner</option>
                    <option value="card">Card</option>
                    <option value="list">List</option>
                    <option value="stats">Stats</option>
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                </select>
            </div>

            {{-- Size --}}
            <div class="sb-field" id="add-size-field">
                <label class="sb-label">Block Size</label>
                <div class="size-btn-group" id="add-size-group">
                    <button class="size-btn" data-size="sm" onclick="selectAddSize('sm')">S</button>
                    <button class="size-btn active" data-size="md" onclick="selectAddSize('md')">M</button>
                    <button class="size-btn" data-size="lg" onclick="selectAddSize('lg')">L</button>
                </div>
            </div>

            {{-- Background Style --}}
            <div class="sb-field" id="add-bg-style-field">
                <label class="sb-label">Background Style</label>
                <select id="add-bg-style" class="sb-input">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="gradient">Gradient</option>
                </select>
            </div>

            {{-- Active toggle --}}
            <div class="sb-field d-flex align-items-center justify-content-between">
                <p class="small fw-semibold text-dark mb-0">Active (visible on client)</p>
                <label class="toggle-switch mb-0">
                    <input type="checkbox" id="add-is-active" checked>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="height:1px;background:#f3f4f6;"></div>

            {{-- Banner config --}}
            <div class="modal-cfg visible" id="modal-cfg-banner">
                <p class="text-xs font-bold text-purple-500 uppercase tracking-wider mb-3">Banner Config</p>
                <div class="sb-field">
                    <label class="sb-label">Image URL <span class="text-red-400">*</span></label>
                    <input type="url" id="add-banner-image" class="sb-input" placeholder="https://...">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Link URL <span class="text-red-400">*</span></label>
                    <input type="url" id="add-banner-link" class="sb-input" placeholder="https://...">
                </div>
            </div>

            {{-- Card config --}}
            <div class="modal-cfg" id="modal-cfg-card">
                <p class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-3">Card Config</p>
                <div class="sb-field">
                    <label class="sb-label">Cards</label>
                    <div id="add-card-items" class="space-y-4 mb-2">
                        <div class="dyn-row items-start">
                            <div class="drag-row-handle mt-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                            <div class="flex-1 space-y-2">
                                <input type="text" class="sb-input card-title" placeholder="Card Title">
                                <textarea class="sb-input card-desc" placeholder="Short description…"></textarea>
                                <input type="url" class="sb-input card-image" placeholder="Image URL (optional)">
                            </div>
                            <button type="button" class="remove-row-btn mt-2" onclick="removeRow(this, 'add-card-items', 1)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addCardItemModal()">+ Add card</button>
                </div>
            </div>

            {{-- List config --}}
            <div class="modal-cfg" id="modal-cfg-list">
                <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-3">List Config</p>
                <div class="sb-field mb-3">
                    <label class="sb-label">Layout</label>
                    <select id="add-list-layout" class="sb-input">
                        <option value="vertical">Vertical (Default)</option>
                        <option value="horizontal">Horizontal</option>
                    </select>
                </div>
                <label class="sb-label">Items <span class="text-red-400">*</span></label>
                <div id="add-list-items" class="space-y-2 mb-2">
                    <div class="dyn-row">
                        <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                        <input type="text" class="sb-input" placeholder="List item…">
                        <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-list-items', 1)">✕</button>
                    </div>
                </div>
                <button type="button" class="add-row-btn" onclick="addListItemModal()">+ Add item</button>
            </div>

            {{-- Stats config --}}
            <div class="modal-cfg" id="modal-cfg-stats">
                <p class="text-xs font-bold text-green-500 uppercase tracking-wider mb-3">Stats Config</p>
                <label class="sb-label">Statistics <span class="text-red-400">*</span></label>
                <div id="add-stats-items" class="space-y-2 mb-2">
                    <div class="dyn-row">
                        <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                        <select class="sb-input stat-icon" style="flex:0.5; padding:8px 6px;">
                            <option value="">No Icon</option>
                            <option value="users">Users</option>
                            <option value="chart">Chart</option>
                            <option value="star">Star</option>
                            <option value="clock">Clock</option>
                        </select>
                        <input type="text" class="sb-input stat-label" placeholder="Label">
                        <input type="text" class="sb-input stat-value" placeholder="Value">
                        <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-stats-items', 1)">✕</button>
                    </div>
                </div>
                <button type="button" class="add-row-btn" onclick="addStatItemModal()">+ Add stat</button>
            </div>

            {{-- Header config --}}
            <div class="modal-cfg" id="modal-cfg-header">
                <p class="text-xs font-bold text-rose-500 uppercase tracking-wider mb-3">Header Config</p>
                <div class="sb-field">
                    <label class="sb-label">Logo Text <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="text" id="add-header-logo-text" class="sb-input" placeholder="e.g. Acme Corp">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Logo Image URL <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="url" id="add-header-logo-url" class="sb-input" placeholder="https://...">
                </div>
                <div class="sb-field">
                    <label class="sb-label">CTA Label <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="text" id="add-header-cta-label" class="sb-input" placeholder="e.g. Get Started">
                </div>
                <div class="sb-field">
                    <label class="sb-label">CTA URL <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="text" id="add-header-cta-url" class="sb-input" placeholder="https://...">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Nav Links</label>
                    <div id="add-header-nav-links" class="space-y-2 mb-2">
                        <div class="dyn-row">
                            <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                            <input type="text" class="sb-input nav-label" placeholder="Label">
                            <input type="text" class="sb-input nav-url" placeholder="URL">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-header-nav-links', 0)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addHeaderNavItemModal()">+ Add link</button>
                </div>
            </div>

            {{-- Footer config --}}
            <div class="modal-cfg" id="modal-cfg-footer">
                <p class="text-xs font-bold text-teal-500 uppercase tracking-wider mb-3">Footer Config</p>
                <div class="sb-field">
                    <label class="sb-label">Brand Name <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="text" id="add-footer-brand" class="sb-input" placeholder="e.g. Acme Corp">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Tagline <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="text" id="add-footer-tagline" class="sb-input" placeholder="Making the world better...">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Quick Links</label>
                    <div id="add-footer-links" class="space-y-2 mb-2">
                        <div class="dyn-row">
                            <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                            <input type="text" class="sb-input link-label" placeholder="Label">
                            <input type="text" class="sb-input link-url" placeholder="URL">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-footer-links', 0)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addFooterLinkModal()">+ Add link</button>
                </div>
                <div class="sb-field">
                    <label class="sb-label">Social Links</label>
                    <div id="add-footer-social" class="space-y-2 mb-2">
                        <div class="dyn-row">
                            <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                            <input type="text" class="sb-input social-platform" placeholder="Platform">
                            <input type="text" class="sb-input social-url" placeholder="URL">
                            <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-footer-social', 0)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addFooterSocialModal()">+ Add social</button>
                </div>
            </div>

            {{-- Error area --}}
            <div id="add-error" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg"></div>

        </div>
        <div id="add-modal-footer">
            <button onclick="closeAddModal()"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:bg-gray-100 transition">
                Cancel
            </button>
            <button onclick="submitAddBlock()"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-grey-500 text-sm font-bold rounded-xl transition shadow-sm">
                Create Block
            </button>
        </div>
    </div>
</div>
