const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const BASE_URL  = '"test"';
const SITE_BASE = '"test"';
let currentId   = null;  // block id currently open in sidebar
let sidebarOpen = false;

const blockData = {};
document.querySelectorAll('.preview-block[data-id]').forEach(el => {
    blockData[el.dataset.id] = JSON.parse(el.dataset.json);
});

// Init Block Sorting
Sortable.create(document.getElementById('editor-canvas'), {
    handle: '.drag-block-handle',
    animation: 200,
    ghostClass: 'opacity-50',
    onEnd: async function () {
        const order = [...document.querySelectorAll('.preview-block[data-id]')].map(w => parseInt(w.dataset.id));
        try {
            await api('POST', `${SITE_BASE}/ui-blocks/reorder`, { order });
            order.forEach((bid, pos) => { if (blockData[bid]) blockData[bid].display_order = pos; });
            toast('Order saved', 'success');
        } catch (e) {
            toast('Reorder failed', 'error');
        }
    }
});

// Init Add Modal Sorting
['add-card-items', 'add-list-items', 'add-stats-items', 'add-header-nav-links', 'add-footer-links', 'add-footer-social'].forEach(id => {
    const el = document.getElementById(id);
    if(el) Sortable.create(el, { handle: '.drag-row-handle', animation: 150 });
});

// ─── Sidebar open/close ──────────────────────────────────────
function openEditor(id) {
    currentId = String(id);
    const block = blockData[currentId];
    if (!block) return;

    // Header
    document.getElementById('sb-type-badge').textContent  = block.type;
    document.getElementById('sb-block-id').textContent    = '#' + block.id;
    document.getElementById('sb-title-display').textContent = block.title;

    // Fields
    document.getElementById('sb-title').value     = block.title;
    document.getElementById('sb-is-active').checked = !!block.is_active;

    // Size
    const size = block.config?.size ?? 'md';
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.size === size);
    });
    const sizeField = document.getElementById('sb-size-field');
    if (sizeField) {
        sizeField.style.display = (block.type === 'header') ? 'none' : 'block';
    }

    // Background style
    const bgStyle = block.config?.bg_style ?? 'light';
    const bgStyleField = document.getElementById('sb-bg-style');
    if (bgStyleField) {
        bgStyleField.value = bgStyle;
    }

    // Config fields
    buildConfigFields(block);

    // Open sidebar
    document.getElementById('editor-sidebar').classList.add('open');
    document.body.classList.add('sidebar-open');
    sidebarOpen = true;
}

function closeSidebar() {
    document.getElementById('editor-sidebar').classList.remove('open');
    document.body.classList.remove('sidebar-open');
    sidebarOpen = false;
    currentId = null;
}

// ─── Size buttons ─────────────────────────────────────────────
function selectSize(s) {
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.size === s);
    });
}
function selectAddSize(s) {
    document.querySelectorAll('#add-size-group .size-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.size === s);
    });
}

// ─── Config field builders ────────────────────────────────────
function buildConfigFields(block) {
    const c = block.config ?? {};
    const container = document.getElementById('config-fields');
    let html = '';

    switch (block.type) {
        case 'banner':
            html = `
              <p class="section-label">Banner Config</p>
              <div class="sb-field">
                <label class="sb-label">Image URL</label>
                <input type="url" id="cfg-image_url" class="sb-input" value="${esc(c.image_url ?? '')}" placeholder="https://...">
              </div>
              <div class="sb-field">
                <label class="sb-label">Link URL</label>
                <input type="url" id="cfg-link" class="sb-input" value="${esc(c.link ?? '')}" placeholder="https://...">
              </div>`;
            break;

        case 'card':
            const cards = c.cards ?? (c.title ? [c] : [{ title:'', description:'', image_url:'' }]);
            const cardsHtml = cards.map(cd => `
              <div class="dyn-row items-start">
                <div class="drag-row-handle mt-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <div class="flex-1 space-y-2">
                    <input type="text" class="sb-input card-title" value="${esc(cd.title ?? '')}" placeholder="Card Title">
                    <textarea class="sb-input card-desc" placeholder="Short description…">${esc(cd.description ?? '')}</textarea>
                    <input type="url" class="sb-input card-image" value="${esc(cd.image_url ?? '')}" placeholder="Image URL (optional)">
                </div>
                <button type="button" class="remove-row-btn mt-2" onclick="removeSbRow(this, 'sb-card-items', 1)">✕</button>
              </div>`).join('');
            html = `
              <p class="section-label">Card Config</p>
              <div class="sb-field">
                <label class="sb-label">Cards</label>
                <div id="sb-card-items" class="d-flex flex-column gap-2 mb-2">${cardsHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbCardItem()">+ Add card</button>
              </div>`;
            break;

        case 'list':
            const items = c.items ?? [''];
            const itemsHtml = items.map((it, i) => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <input type="text" class="sb-input list-item-input" value="${esc(it)}" placeholder="List item…">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-list-items', 1)">✕</button>
              </div>`).join('');
            const listLayout = c.layout ?? 'vertical';
            html = `
              <p class="section-label">List Config</p>
              <div class="sb-field mb-3">
                <label class="sb-label">Layout</label>
                <select id="sb-list-layout" class="sb-input">
                    <option value="vertical" ${listLayout === 'vertical' ? 'selected' : ''}>Vertical (Default)</option>
                    <option value="horizontal" ${listLayout === 'horizontal' ? 'selected' : ''}>Horizontal</option>
                </select>
              </div>
              <div class="sb-field">
                <label class="sb-label">Items</label>
                <div id="sb-list-items" class="d-flex flex-column gap-2 mb-2">${itemsHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbListItem()">+ Add item</button>
              </div>`;
            break;

        case 'stats':
            const stats = c.stats ?? [{ label:'', value:'', icon:'' }];
            const statsHtml = stats.map((s, i) => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <select class="sb-input stat-icon" style="flex:0.5; padding:8px 6px;">
                    <option value="" ${s.icon==='' ? 'selected':''}>No Icon</option>
                    <option value="users" ${s.icon==='users' ? 'selected':''}>Users</option>
                    <option value="chart" ${s.icon==='chart' ? 'selected':''}>Chart</option>
                    <option value="star" ${s.icon==='star' ? 'selected':''}>Star</option>
                    <option value="clock" ${s.icon==='clock' ? 'selected':''}>Clock</option>
                </select>
                <input type="text" class="sb-input stat-label" value="${esc(s.label ?? '')}" placeholder="Label">
                <input type="text" class="sb-input stat-value" value="${esc(s.value ?? '')}" placeholder="Value">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-stats-items', 1)">✕</button>
              </div>`).join('');
            html = `
              <p class="section-label">Stats Config</p>
              <div class="sb-field">
                <label class="sb-label">Statistics</label>
                <div id="sb-stats-items" class="d-flex flex-column gap-2 mb-2">${statsHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbStatItem()">+ Add stat</button>
              </div>`;
            break;
        case 'header':
            const navLinks = c.nav_links ?? [{ label:'', url:'' }];
            const navHtml = navLinks.map(n => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <input type="text" class="sb-input nav-label" value="${esc(n.label ?? '')}" placeholder="Label">
                <input type="text" class="sb-input nav-url" value="${esc(n.url ?? '')}" placeholder="URL">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-nav-items', 1)">✕</button>
              </div>`).join('');
            html = `
              <p class="section-label">Header Config</p>
              <div class="sb-field">
                <label class="sb-label">Logo Text</label>
                <input type="text" id="cfg-header-logo-text" class="sb-input" value="${esc(c.logo_text ?? '')}" placeholder="e.g. Acme Corp">
              </div>
              <div class="sb-field">
                <label class="sb-label">Logo Image URL</label>
                <input type="url" id="cfg-header-logo-url" class="sb-input" value="${esc(c.logo_url ?? '')}" placeholder="https://...">
              </div>
              <div class="sb-field">
                <label class="sb-label">CTA Label</label>
                <input type="text" id="cfg-header-cta-label" class="sb-input" value="${esc(c.cta_label ?? '')}" placeholder="e.g. Get Started">
              </div>
              <div class="sb-field">
                <label class="sb-label">CTA URL</label>
                <input type="text" id="cfg-header-cta-url" class="sb-input" value="${esc(c.cta_url ?? '')}" placeholder="https://...">
              </div>
              <div class="sb-field">
                <label class="sb-label">Nav Links</label>
                <div id="sb-nav-items" class="d-flex flex-column gap-2 mb-2">${navHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbNavItem()">+ Add link</button>
              </div>`;
            break;

        case 'footer':
            const quickLinks = c.links ?? [{ label:'', url:'' }];
            const socialLinks = c.social_links ?? [{ platform:'', url:'' }];
            
            const qlHtml = quickLinks.map(l => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <input type="text" class="sb-input footer-label" value="${esc(l.label ?? '')}" placeholder="Label">
                <input type="text" class="sb-input footer-url" value="${esc(l.url ?? '')}" placeholder="URL">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-footer-links', 1)">✕</button>
              </div>`).join('');
              
            const slHtml = socialLinks.map(s => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <input type="text" class="sb-input social-platform" value="${esc(s.platform ?? '')}" placeholder="Platform">
                <input type="text" class="sb-input social-url" value="${esc(s.url ?? '')}" placeholder="URL">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-social-links', 1)">✕</button>
              </div>`).join('');
              
            html = `
              <p class="section-label">Footer Config</p>
              <div class="sb-field">
                <label class="sb-label">Brand Name</label>
                <input type="text" id="cfg-footer-brand" class="sb-input" value="${esc(c.brand ?? '')}" placeholder="e.g. Acme Corp">
              </div>
              <div class="sb-field">
                <label class="sb-label">Tagline</label>
                <input type="text" id="cfg-footer-tagline" class="sb-input" value="${esc(c.tagline ?? '')}" placeholder="Making the world better...">
              </div>
              <div class="sb-field">
                <label class="sb-label">Copyright</label>
                <input type="text" id="cfg-footer-copyright" class="sb-input" value="${esc(c.copyright ?? '')}" placeholder="© 2026...">
              </div>
              <div class="sb-field">
                <label class="sb-label">Quick Links</label>
                <div id="sb-footer-links" class="d-flex flex-column gap-2 mb-2">${qlHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbFooterLink()">+ Add link</button>
              </div>
              <div class="sb-field">
                <label class="sb-label">Social Links</label>
                <div id="sb-social-links" class="d-flex flex-column gap-2 mb-2">${slHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbSocialLink()">+ Add social</button>
              </div>`;
            break;
    }

    container.innerHTML = html;

    // Init sorting for dynamic arrays in sidebar
    ['sb-card-items', 'sb-list-items', 'sb-stats-items', 'sb-nav-items', 'sb-footer-links', 'sb-social-links'].forEach(id => {
        const el = document.getElementById(id);
        if(el) Sortable.create(el, { handle: '.drag-row-handle', animation: 150 });
    });
}

// ─── Dynamic row helpers (sidebar) ────────────────────────────
function addSbCardItem() {
    const cont = document.getElementById('sb-card-items');
    const div = document.createElement('div');
    div.className = 'dyn-row align-items-start';
    div.innerHTML = `<div class="drag-row-handle mt-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <div class="flex-grow-1 d-flex flex-column gap-2">
                         <input type="text" class="sb-input card-title" placeholder="Card Title">
                         <textarea class="sb-input card-desc" placeholder="Short description…"></textarea>
                         <input type="url" class="sb-input card-image" placeholder="Image URL (optional)">
                     </div>
                     <button type="button" class="remove-row-btn mt-2" onclick="removeSbRow(this, 'sb-card-items', 1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addSbListItem() {
    const cont = document.getElementById('sb-list-items');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input list-item-input" placeholder="List item…">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-list-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addSbStatItem() {
    const cont = document.getElementById('sb-stats-items');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <select class="sb-input stat-icon" style="flex:0.5; padding:8px 6px;">
                         <option value="">No Icon</option>
                         <option value="users">Users</option>
                         <option value="chart">Chart</option>
                         <option value="star">Star</option>
                         <option value="clock">Clock</option>
                     </select>
                     <input type="text" class="sb-input stat-label" placeholder="Label">
                     <input type="text" class="sb-input stat-value" placeholder="Value">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-stats-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('select').focus();
}
function addSbNavItem() {
    const cont = document.getElementById('sb-nav-items');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input nav-label" placeholder="Label">
                     <input type="text" class="sb-input nav-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-nav-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addSbFooterLink() {
    const cont = document.getElementById('sb-footer-links');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input footer-label" placeholder="Label">
                     <input type="text" class="sb-input footer-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-footer-links',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addSbSocialLink() {
    const cont = document.getElementById('sb-social-links');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input social-platform" placeholder="Platform">
                     <input type="text" class="sb-input social-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-social-links',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function removeSbRow(btn, containerId, minRows) {
    const cont = document.getElementById(containerId);
    if (cont && cont.querySelectorAll('.dyn-row').length > minRows) {
        btn.closest('.dyn-row').remove();
    }
}

// ─── Collect sidebar form data ────────────────────────────────
function collectSidebarData() {
    const block = blockData[currentId];
    const title    = document.getElementById('sb-title').value.trim();
    const isActive = document.getElementById('sb-is-active').checked;
    const size     = document.querySelector('.size-btn.active')?.dataset.size ?? 'md';
    const bgStyle  = document.getElementById('sb-bg-style')?.value ?? 'light';

    let config = { ...(block.config ?? {}), size, bg_style: bgStyle };

    switch (block.type) {
        case 'banner':
            config.image_url = document.getElementById('cfg-image_url')?.value.trim() ?? '';
            config.link      = document.getElementById('cfg-link')?.value.trim() ?? '';
            break;
        case 'card':
            config.cards = [...document.querySelectorAll('#sb-card-items .dyn-row')]
                .map(row => ({
                    title: row.querySelector('.card-title')?.value.trim() ?? '',
                    description: row.querySelector('.card-desc')?.value.trim() ?? '',
                    image_url: row.querySelector('.card-image')?.value.trim() ?? '',
                })).filter(c => c.title || c.description);
            break;
        case 'list':
            config.layout = document.getElementById('sb-list-layout')?.value ?? 'vertical';
            config.items = [...document.querySelectorAll('#sb-list-items .list-item-input')]
                .map(i => i.value.trim()).filter(Boolean);
            break;
        case 'stats':
            config.stats = [...document.querySelectorAll('#sb-stats-items .dyn-row')]
                .map(row => ({
                    icon: row.querySelector('.stat-icon')?.value ?? '',
                    label: row.querySelector('.stat-label')?.value.trim() ?? '',
                    value: row.querySelector('.stat-value')?.value.trim() ?? '',
                })).filter(s => s.label || s.value);
            break;
        case 'header':
            config.logo_text = document.getElementById('cfg-header-logo-text')?.value.trim() ?? '';
            config.logo_url  = document.getElementById('cfg-header-logo-url')?.value.trim() ?? '';
            config.cta_label = document.getElementById('cfg-header-cta-label')?.value.trim() ?? '';
            config.cta_url   = document.getElementById('cfg-header-cta-url')?.value.trim() ?? '';
            config.nav_links = [...document.querySelectorAll('#sb-nav-items .dyn-row')].map(row => ({
                label: row.querySelector('.nav-label')?.value.trim() ?? '',
                url: row.querySelector('.nav-url')?.value.trim() ?? ''
            })).filter(l => l.label || l.url);
            break;
        case 'footer':
            config.brand     = document.getElementById('cfg-footer-brand')?.value.trim() ?? '';
            config.tagline   = document.getElementById('cfg-footer-tagline')?.value.trim() ?? '';
            config.copyright = document.getElementById('cfg-footer-copyright')?.value.trim() ?? '';
            config.links = [...document.querySelectorAll('#sb-footer-links .dyn-row')].map(row => ({
                label: row.querySelector('.footer-label')?.value.trim() ?? '',
                url: row.querySelector('.footer-url')?.value.trim() ?? ''
            })).filter(l => l.label || l.url);
            config.social_links = [...document.querySelectorAll('#sb-social-links .dyn-row')].map(row => ({
                platform: row.querySelector('.social-platform')?.value.trim() ?? '',
                url: row.querySelector('.social-url')?.value.trim() ?? ''
            })).filter(l => l.platform || l.url);
            break;
    }

    return { title, type: block.type, is_active: isActive, config };
}

// ─── Save sidebar ─────────────────────────────────────────────
async function saveSidebar() {
    if (!currentId) return;
    const data = collectSidebarData();

    try {
        const res = await api('PUT', `${SITE_BASE}/ui-blocks/${currentId}`, data);
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            const msg = err.message ?? 'Validation error';
            toast(msg, 'error'); return;
        }

        // Update local cache
        blockData[currentId] = { ...blockData[currentId], ...data, id: parseInt(currentId) };

        // Refresh the block's rendered HTML via AJAX
        await refreshBlockPreview(currentId);

        // Update sidebar header
        document.getElementById('sb-title-display').textContent = data.title;

        toast('Saved!', 'success');
    } catch(e) {
        toast('Network error', 'error');
        console.error(e);
    }
}

// ─── Refresh a block's preview HTML ──────────────────────────
async function refreshBlockPreview(id) {
    const res = await fetch(`${SITE_BASE}/ui-blocks/${id}/render`, {
        headers: { 'Accept': 'text/html' }
    });
    const html = await res.text();
    document.getElementById(`block-content-${id}`).innerHTML = html;

    const wrapper = document.getElementById(`block-wrapper-${id}`);
    const isActive = blockData[id].is_active;

    // Sync active state visuals
    wrapper.classList.toggle('inactive', !isActive);
    const badge = document.getElementById(`inactive-badge-${id}`);
    if (badge) badge.style.display = isActive ? 'none' : '';

    const toggleLabel = document.getElementById(`toggle-label-${id}`);
    if (toggleLabel) toggleLabel.textContent = isActive ? '👁 Hide' : '👁 Show';

    // Sync data attribute
    wrapper.dataset.json = JSON.stringify(blockData[id]);
}

// ─── Quick toggle ─────────────────────────────────────────────
async function quickToggle(id) {
    try {
        const res = await api('PATCH', `${SITE_BASE}/ui-blocks/${id}/toggle`, {});
        const data = await res.json();
        blockData[id].is_active = data.is_active;
        await refreshBlockPreview(id);

        // If sidebar is open for this block, sync the toggle
        if (currentId === String(id)) {
            document.getElementById('sb-is-active').checked = data.is_active;
        }

        toast(data.is_active ? 'Block activated' : 'Block hidden', 'success');
    } catch(e) {
        toast('Toggle failed', 'error');
    }
}

// ─── Delete ───────────────────────────────────────────────────
async function deleteBlock(id) {
    if (!confirm('Delete this block permanently?')) return;
    try {
        await api('DELETE', `${SITE_BASE}/ui-blocks/${id}`, {});
        document.getElementById(`block-wrapper-${id}`)?.remove();
        delete blockData[id];
        if (currentId === String(id)) closeSidebar();
        toast('Block deleted', 'success');
    } catch(e) {
        toast('Delete failed', 'error');
    }
}

// ─── Move up/down ─────────────────────────────────────────────
function addCardItemModal() {
    const cont = document.getElementById('add-card-items');
    const div = document.createElement('div');
    div.className = 'dyn-row align-items-start';
    div.innerHTML = `<div class="drag-row-handle mt-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <div class="flex-grow-1 d-flex flex-column gap-2">
                         <input type="text" class="sb-input card-title" placeholder="Card Title">
                         <textarea class="sb-input card-desc" placeholder="Short description…"></textarea>
                         <input type="url" class="sb-input card-image" placeholder="Image URL (optional)">
                     </div>
                     <button type="button" class="remove-row-btn mt-2" onclick="removeRow(this,'add-card-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addHeaderNavItemModal() {
    const div = document.createElement('div'); div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input nav-label" placeholder="Label">
                     <input type="text" class="sb-input nav-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-header-nav-links', 0)">✕</button>`;
    document.getElementById('add-header-nav-links').appendChild(div);
}
function addFooterLinkModal() {
    const div = document.createElement('div'); div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input link-label" placeholder="Label">
                     <input type="text" class="sb-input link-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-footer-links', 0)">✕</button>`;
    document.getElementById('add-footer-links').appendChild(div);
}
function addFooterSocialModal() {
    const div = document.createElement('div'); div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input social-platform" placeholder="Platform">
                     <input type="text" class="sb-input social-url" placeholder="URL">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-footer-social', 0)">✕</button>`;
    document.getElementById('add-footer-social').appendChild(div);
}
function openAddModal() {
    document.getElementById('add-modal-backdrop').classList.add('open');
    document.getElementById('add-title').focus();
    document.getElementById('add-error').classList.add('hidden');
    switchAddModalType(document.getElementById('add-type').value);
}
function closeAddModal() {
    document.getElementById('add-modal-backdrop').classList.remove('open');
}
function switchAddModalType(type) {
    document.querySelectorAll('.modal-cfg').forEach(el => el.classList.remove('visible'));
    document.getElementById(`modal-cfg-${type}`)?.classList.add('visible');
    
    const sizeField = document.getElementById('add-size-field');
    if (sizeField) {
        sizeField.style.display = (type === 'header') ? 'none' : 'block';
    }
}

function addListItemModal() {
    const cont = document.getElementById('add-list-items');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input" placeholder="List item…">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this,'add-list-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
}
function addStatItemModal() {
    const cont = document.getElementById('add-stats-items');
    const div = document.createElement('div');
    div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <select class="sb-input stat-icon" style="flex:0.5; padding:8px 6px;">
                         <option value="">No Icon</option>
                         <option value="users">Users</option>
                         <option value="chart">Chart</option>
                         <option value="star">Star</option>
                         <option value="clock">Clock</option>
                     </select>
                     <input type="text" class="sb-input stat-label" placeholder="Label">
                     <input type="text" class="sb-input stat-value" placeholder="Value">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this,'add-stats-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('select').focus();
}
function removeRow(btn, containerId, minRows) {
    const cont = document.getElementById(containerId);
    if (cont && cont.querySelectorAll('.dyn-row').length > minRows) {
        btn.closest('.dyn-row').remove();
    }
}

async function submitAddBlock() {
    const errBox = document.getElementById('add-error');
    errBox.classList.add('hidden');

    const type  = document.getElementById('add-type').value;
    const title = document.getElementById('add-title').value.trim();
    const isActive = document.getElementById('add-is-active').checked;
    const size  = document.querySelector('#add-size-group .size-btn.active')?.dataset.size ?? 'md';
    const bgStyle = document.getElementById('add-bg-style')?.value ?? 'light';

    if (!title) { errBox.textContent='Title is required.'; errBox.classList.remove('hidden'); return; }

    let config = { size, bg_style: bgStyle };

    if (type === 'banner') {
        config.image_url = document.getElementById('add-banner-image').value.trim();
        config.link      = document.getElementById('add-banner-link').value.trim();
        if (!config.image_url || !config.link) {
            errBox.textContent='Image URL and Link URL are required for Banner.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'card') {
        config.cards = [...document.querySelectorAll('#add-card-items .dyn-row')]
            .map(row => ({
                title: row.querySelector('.card-title')?.value.trim() ?? '',
                description: row.querySelector('.card-desc')?.value.trim() ?? '',
                image_url: row.querySelector('.card-image')?.value.trim() ?? '',
            })).filter(c => c.title || c.description);
        if (!config.cards.length) {
            errBox.textContent='At least one card is required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'list') {
        config.layout = document.getElementById('add-list-layout')?.value ?? 'vertical';
        config.items = [...document.querySelectorAll('#add-list-items input')]
            .map(i => i.value.trim()).filter(Boolean);
        if (!config.items.length) {
            errBox.textContent='At least one list item is required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'stats') {
        config.stats = [...document.querySelectorAll('#add-stats-items .dyn-row')]
            .map(row => ({
                icon: row.querySelector('.stat-icon')?.value ?? '',
                label: row.querySelector('.stat-label')?.value.trim() ?? '',
                value: row.querySelector('.stat-value')?.value.trim() ?? '',
            })).filter(s => s.label || s.value);
        if (!config.stats.length) {
            errBox.textContent='At least one stat is required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'header') {
        config.logo_text = document.getElementById('add-header-logo-text')?.value.trim() ?? '';
        config.logo_url  = document.getElementById('add-header-logo-url')?.value.trim() ?? '';
        config.cta_label = document.getElementById('add-header-cta-label')?.value.trim() ?? '';
        config.cta_url   = document.getElementById('add-header-cta-url')?.value.trim() ?? '';
        config.nav_links = [...document.querySelectorAll('#add-header-nav-links .dyn-row')].map(row => ({
            label: row.querySelector('.nav-label')?.value.trim() ?? '',
            url: row.querySelector('.nav-url')?.value.trim() ?? ''
        })).filter(l => l.label || l.url);
    } else if (type === 'footer') {
        config.brand   = document.getElementById('add-footer-brand')?.value.trim() ?? '';
        config.tagline = document.getElementById('add-footer-tagline')?.value.trim() ?? '';
        config.links   = [...document.querySelectorAll('#add-footer-links .dyn-row')].map(row => ({
            label: row.querySelector('.link-label')?.value.trim() ?? '',
            url: row.querySelector('.link-url')?.value.trim() ?? ''
        })).filter(l => l.label || l.url);
        config.social_links = [...document.querySelectorAll('#add-footer-social .dyn-row')].map(row => ({
            platform: row.querySelector('.social-platform')?.value.trim() ?? '',
            url: row.querySelector('.social-url')?.value.trim() ?? ''
        })).filter(l => l.platform || l.url);
    }

    const payload = { title, type, is_active: isActive, config };

    try {
        const res = await api('POST', `${SITE_BASE}/ui-blocks`, payload);
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            errBox.textContent = err.message ?? 'Validation error. Check all required fields.';
            errBox.classList.remove('hidden'); return;
        }
        toast('Block created!', 'success');
        closeAddModal();
        // Reload canvas to show the new block
        setTimeout(() => location.reload(), 600);
    } catch(e) {
        errBox.textContent = 'Network error. Please try again.';
        errBox.classList.remove('hidden');
    }
}

// ─── Generic fetch helper ─────────────────────────────────────
function api(method, path, data) {
    const opts = {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':  CSRF,
            'Accept':        'application/json',
        },
    };
    if (method !== 'GET') opts.body = JSON.stringify(data);
    return fetch(path, opts);
}

// ─── Toast notifications ──────────────────────────────────────
function toast(msg, type = 'success') {
    const el = document.createElement('div');
    el.className = `toast ${type}`;
    el.textContent = msg;
    document.getElementById('toast-container').appendChild(el);
    setTimeout(() => el.style.opacity = '0', 2200);
    setTimeout(() => el.remove(), 2600);
}

// ─── Utility ──────────────────────────────────────────────────
function esc(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Close sidebar on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeSidebar();
        closeAddModal();
    }
});
