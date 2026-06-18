@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
// -----------------------------------------------------------------------
// Show/hide config sections based on selected type
// -----------------------------------------------------------------------
const typeSelect  = document.getElementById('type');
const allSections = document.querySelectorAll('.config-section');

function showSection(type) {
    allSections.forEach(section => {
        const show = section.id === 'cfg-' + type;
        section.classList.toggle('hidden', !show);
        // Disable inputs inside hidden sections so they don't get submitted / validated
        section.querySelectorAll('input, textarea, select').forEach(el => {
            el.disabled = !show;
        });
    });
}

// Init on page load
showSection(typeSelect.value);

// React to change
typeSelect.addEventListener('change', () => showSection(typeSelect.value));

// Init sorting for dynamic lists
document.addEventListener('DOMContentLoaded', () => {
    const listIds = ['list-items', 'stats-items', 'nav-links-items', 'footer-links-items', 'social-links-items'];
    listIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) Sortable.create(el, { handle: '.drag-row-handle', animation: 150 });
    });
});

// -----------------------------------------------------------------------
// List item helpers
// -----------------------------------------------------------------------
function addListItem() {
    const container = document.getElementById('list-items');
    const div = document.createElement('div');
    div.className = 'flex gap-2 list-item-row items-center';
    div.innerHTML = `
        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
        </div>
        <input type="text" name="config[items][]"
               placeholder="List item…"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <button type="button" onclick="removeListItem(this)"
                class="text-gray-400 hover:text-red-500 transition px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function removeListItem(btn) {
    const rows = document.querySelectorAll('.list-item-row');
    if (rows.length > 1) {
        btn.closest('.list-item-row').remove();
    }
}

// -----------------------------------------------------------------------
// Stats item helpers
// -----------------------------------------------------------------------
function addStatItem() {
    const container = document.getElementById('stats-items');
    const idx = container.querySelectorAll('.stats-item-row').length;
    const div = document.createElement('div');
    div.className = 'flex gap-2 stats-item-row items-center';
    div.innerHTML = `
        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
        </div>
        <input type="text" name="config[stats][${idx}][label]"
               placeholder="Label (e.g. Users)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <input type="text" name="config[stats][${idx}][value]"
               placeholder="Value (e.g. 10,000)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <button type="button" onclick="removeStatItem(this)"
                class="text-gray-400 hover:text-red-500 transition px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function removeStatItem(btn) {
    const rows = document.querySelectorAll('.stats-item-row');
    if (rows.length > 1) {
        btn.closest('.stats-item-row').remove();
    }
}

// -----------------------------------------------------------------------
// Header & Footer dynamic row helpers
// -----------------------------------------------------------------------
function removeDynamicRow(btn, rowClass) {
    const rows = document.querySelectorAll(rowClass);
    if (rows.length > 1) {
        btn.closest(rowClass).remove();
    }
}

function addNavLinkItem() {
    const container = document.getElementById('nav-links-items');
    const idx = container.querySelectorAll('.nav-link-row').length;
    const div = document.createElement('div');
    div.className = 'flex gap-2 nav-link-row items-center';
    div.innerHTML = `
        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
        </div>
        <input type="text" name="config[nav_links][${idx}][label]"
               placeholder="Label (e.g. Home)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <input type="text" name="config[nav_links][${idx}][url]"
               placeholder="URL (e.g. /home)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <button type="button" onclick="removeDynamicRow(this, '.nav-link-row')"
                class="text-gray-400 hover:text-red-500 transition px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function addFooterLinkItem() {
    const container = document.getElementById('footer-links-items');
    const idx = container.querySelectorAll('.footer-link-row').length;
    const div = document.createElement('div');
    div.className = 'flex gap-2 footer-link-row items-center';
    div.innerHTML = `
        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
        </div>
        <input type="text" name="config[links][${idx}][label]"
               placeholder="Label (e.g. Terms)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <input type="text" name="config[links][${idx}][url]"
               placeholder="URL"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <button type="button" onclick="removeDynamicRow(this, '.footer-link-row')"
                class="text-gray-400 hover:text-red-500 transition px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input').focus();
}

function addSocialLinkItem() {
    const container = document.getElementById('social-links-items');
    const idx = container.querySelectorAll('.social-link-row').length;
    const div = document.createElement('div');
    div.className = 'flex gap-2 social-link-row items-center';
    div.innerHTML = `
        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
        </div>
        <input type="text" name="config[social_links][${idx}][platform]"
               placeholder="Platform (e.g. Twitter)"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <input type="text" name="config[social_links][${idx}][url]"
               placeholder="URL"
               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
        <button type="button" onclick="removeDynamicRow(this, '.social-link-row')"
                class="text-gray-400 hover:text-red-500 transition px-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>`;
    container.appendChild(div);
    div.querySelector('input').focus();
}
</script>
@endpush
