import re

with open('resources/views/admin/preview.blade.php', 'r') as f:
    content = f.read()

# 1. Update saveSidebar
save_js = r"""async function saveSidebar() {
    if (!currentId) return;
    const data = collectSidebarData();

    try {
        const res = await api('PUT', `${SITE_BASE}/ui-blocks/${currentId}`, data);
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            const msg = err.message ?? 'Validation error';
            toast(msg, 'error'); return;
        }"""
save_new_js = r"""async function saveSidebar() {
    if (!currentId) return;
    
    let data;
    try {
        data = collectSidebarData();
    } catch (e) {
        console.error(e);
        toast('Error collecting data: ' + e.message, 'error');
        return;
    }

    try {
        const res = await api('PUT', `${SITE_BASE}/ui-blocks/${currentId}`, data);
        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            let msg = 'Validation error';
            if (err.errors) {
                msg = Object.values(err.errors).flat().join(' | ');
            } else if (err.message) {
                msg = err.message;
            }
            toast(msg, 'error'); return;
        }"""
content = content.replace(save_js, save_new_js)

# 2. Update submitAddBlock
add_js = r"""    try {
        const res = await api('POST', `${SITE_BASE}/ui-blocks`, {
            title, type, is_active: isActive, config
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            errBox.textContent = err.message ?? 'Validation error';
            errBox.classList.remove('hidden'); return;
        }"""
add_new_js = r"""    try {
        const res = await api('POST', `${SITE_BASE}/ui-blocks`, {
            title, type, is_active: isActive, config
        });

        if (!res.ok) {
            const err = await res.json().catch(() => ({}));
            let msg = 'Validation error';
            if (err.errors) {
                msg = Object.values(err.errors).flat().join(' | ');
            } else if (err.message) {
                msg = err.message;
            }
            errBox.textContent = msg;
            errBox.classList.remove('hidden'); return;
        }"""
content = content.replace(add_js, add_new_js)

with open('resources/views/admin/preview.blade.php', 'w') as f:
    f.write(content)

print("Patch applied!")
