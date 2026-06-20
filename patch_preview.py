import re

with open('resources/views/admin/preview.blade.php', 'r') as f:
    content = f.read()

# 1. Add sb-bg-style HTML
sb_size_html = r"""        {{-- Size selector --}}
        <div class="sb-field" id="sb-size-field">
            <label class="sb-label">Block Size</label>
            <div class="size-btn-group">
                <button class="size-btn" data-size="sm" onclick="selectSize('sm')">S — Compact</button>
                <button class="size-btn active" data-size="md" onclick="selectSize('md')">M — Normal</button>
                <button class="size-btn" data-size="lg" onclick="selectSize('lg')">L — Large</button>
            </div>
        </div>"""
sb_bg_html = sb_size_html + r"""

        {{-- Background Style --}}
        <div class="sb-field">
            <label class="sb-label">Background Style</label>
            <select id="sb-bg-style" class="sb-input">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="gradient">Gradient</option>
            </select>
        </div>"""
content = content.replace(sb_size_html, sb_bg_html)

# 2. Add add-bg-style HTML
add_size_html = r"""            {{-- Size --}}
            <div class="sb-field" id="add-size-field">
                <label class="sb-label">Block Size</label>
                <div class="size-btn-group" id="add-size-group">
                    <button class="size-btn" data-size="sm" onclick="selectAddSize('sm')">S</button>
                    <button class="size-btn active" data-size="md" onclick="selectAddSize('md')">M</button>
                    <button class="size-btn" data-size="lg" onclick="selectAddSize('lg')">L</button>
                </div>
            </div>"""
add_bg_html = add_size_html + r"""

            {{-- Background Style --}}
            <div class="sb-field" id="add-bg-style-field">
                <label class="sb-label">Background Style</label>
                <select id="add-bg-style" class="sb-input">
                    <option value="light">Light (Default)</option>
                    <option value="dark">Dark</option>
                    <option value="gradient">Gradient</option>
                </select>
            </div>"""
content = content.replace(add_size_html, add_bg_html)

# 3. Remove header specific bg style from HTML
header_bg_html = r"""                <div class="sb-field">
                    <label class="sb-label">Background Style</label>
                    <select id="add-header-bg" class="sb-input">
                        <option value="light">Light (Default)</option>
                        <option value="dark">Dark</option>
                        <option value="gradient">Gradient</option>
                    </select>
                </div>"""
content = content.replace(header_bg_html, "")

# 4. openEditor JS
open_editor_js = r"""    // Size
    const size = block.config?.size ?? 'md';
    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.size === size);
    });
    const sizeField = document.getElementById('sb-size-field');
    if (sizeField) {
        sizeField.style.display = (block.type === 'header') ? 'none' : 'block';
    }"""
open_editor_new_js = open_editor_js + r"""

    // Background style
    const bgStyle = block.config?.bg_style ?? 'light';
    const bgStyleField = document.getElementById('sb-bg-style');
    if (bgStyleField) {
        bgStyleField.value = bgStyle;
    }"""
content = content.replace(open_editor_js, open_editor_new_js)

# 5. collectSidebarData JS
collect_js = r"""    const title    = document.getElementById('sb-title').value.trim();
    const isActive = document.getElementById('sb-is-active').checked;
    const size     = document.querySelector('.size-btn.active')?.dataset.size ?? 'md';

    let config = { ...(block.config ?? {}), size };"""
collect_new_js = r"""    const title    = document.getElementById('sb-title').value.trim();
    const isActive = document.getElementById('sb-is-active').checked;
    const size     = document.querySelector('.size-btn.active')?.dataset.size ?? 'md';
    const bgStyle  = document.getElementById('sb-bg-style')?.value ?? 'light';

    let config = { ...(block.config ?? {}), size, bg_style: bgStyle };"""
content = content.replace(collect_js, collect_new_js)

# 6. Remove header specific JS from collectSidebarData
collect_header_js = r"""        case 'header':
            config.bg_style  = document.getElementById('cfg-header-bg')?.value ?? 'light';
            config.logo_text = document.getElementById('cfg-header-logo-text')?.value.trim() ?? '';"""
collect_header_new_js = r"""        case 'header':
            config.logo_text = document.getElementById('cfg-header-logo-text')?.value.trim() ?? '';"""
content = content.replace(collect_header_js, collect_header_new_js)

# 7. submitAddBlock JS
add_js = r"""    const type  = document.getElementById('add-type').value;
    const title = document.getElementById('add-title').value.trim();
    const isActive = document.getElementById('add-is-active').checked;
    const size  = document.querySelector('#add-size-group .size-btn.active')?.dataset.size ?? 'md';

    if (!title) { errBox.textContent='Title is required.'; errBox.classList.remove('hidden'); return; }

    let config = { size };"""
add_new_js = r"""    const type  = document.getElementById('add-type').value;
    const title = document.getElementById('add-title').value.trim();
    const isActive = document.getElementById('add-is-active').checked;
    const size  = document.querySelector('#add-size-group .size-btn.active')?.dataset.size ?? 'md';
    const bgStyle = document.getElementById('add-bg-style')?.value ?? 'light';

    if (!title) { errBox.textContent='Title is required.'; errBox.classList.remove('hidden'); return; }

    let config = { size, bg_style: bgStyle };"""
content = content.replace(add_js, add_new_js)

# 8. Remove header specific JS from submitAddBlock
add_header_js = r"""    } else if (type === 'header') {
        config.bg_style  = document.getElementById('add-header-bg')?.value ?? 'light';
        config.logo_text = document.getElementById('add-header-logo-text')?.value.trim() ?? '';"""
add_header_new_js = r"""    } else if (type === 'header') {
        config.logo_text = document.getElementById('add-header-logo-text')?.value.trim() ?? '';"""
content = content.replace(add_header_js, add_header_new_js)

with open('resources/views/admin/preview.blade.php', 'w') as f:
    f.write(content)

print("Patch applied!")
