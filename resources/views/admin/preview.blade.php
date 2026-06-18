<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor — {{ $site->name }} — BlockCraft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <style>
        /* ─── Editor Shell ─────────────────────────────────────── */
        body { 
            margin:0; 
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }

        #editor-topbar {
            position: fixed; top:0; left:0; right:0; z-index:1000;
            height: 52px;
            background: linear-gradient(135deg,#1e1b4b 0%,#312e81 100%);
            display: flex; align-items: center; padding: 0 20px; gap:12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.3);
        }

        #editor-canvas {
            margin-top: 52px;
            padding: 32px 24px 120px;
            max-width: 860px;
            margin-left: auto;
            margin-right: auto;
            transition: margin-right .3s ease;
        }
        body.sidebar-open #editor-canvas { margin-right: 420px; }

        /* ─── Block Wrapper ────────────────────────────────────── */
        .preview-block {
            position: relative;
            margin-bottom: 28px;
            border-radius: 16px;
        }
        .preview-block .block-content {
            transition: opacity .2s;
        }
        .preview-block.inactive .block-content {
            opacity: .42;
            filter: saturate(.4);
        }

        /* Hover ring */
        .preview-block:hover { outline: 2px solid #6366f1; outline-offset: 3px; }

        /* Toolbar shown on hover */
        .block-toolbar {
            display: none;
            position: absolute;
            top: -18px; left: 50%; transform: translateX(-50%);
            z-index: 50;
            background: #1e293b;
            border-radius: 12px;
            padding: 4px 6px;
            gap: 2px;
            align-items: center;
            box-shadow: 0 4px 16px rgba(0,0,0,.25);
        }
        .preview-block:hover .block-toolbar { display: flex; }

        .tb-btn {
            display: inline-flex; align-items: center; gap:4px;
            padding: 5px 10px;
            border: none; border-radius: 8px;
            font-size: 11px; font-weight: 600; cursor: pointer;
            white-space: nowrap; transition: background .15s;
            color: #94a3b8;
            background: transparent;
        }
        .tb-btn:hover { background: rgba(255,255,255,.1); color:#fff; }
        .tb-btn.danger:hover { background: #ef4444; color:#fff; }
        .tb-btn.primary { background: #6366f1; color:#fff; }
        .tb-btn.primary:hover { background: #4f46e5; }

        .tb-divider {
            width:1px; height:16px; background: rgba(255,255,255,.15); margin:0 2px;
        }

        /* Inactive badge */
        .inactive-badge {
            position: absolute;
            top: 8px; left: 12px;
            z-index: 20;
            background: rgba(239,68,68,.9);
            color: #fff;
            font-size: 10px; font-weight: 700;
            padding: 2px 8px;
            border-radius: 99px;
            letter-spacing: .05em;
            text-transform: uppercase;
            pointer-events: none;
        }

        /* ─── Right Sidebar ────────────────────────────────────── */
        #editor-sidebar {
            position: fixed;
            top: 52px; right: 0; bottom: 0;
            width: 420px;
            background: #fff;
            border-left: 1px solid #e5e7eb;
            z-index: 900;
            display: flex; flex-direction: column;
            transform: translateX(100%);
            transition: transform .3s ease;
            box-shadow: -4px 0 24px rgba(0,0,0,.08);
        }
        #editor-sidebar.open { transform: translateX(0); }

        #sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
            flex-shrink: 0;
        }
        #sidebar-body { flex:1; overflow-y:auto; padding:20px; }
        #sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid #f3f4f6;
            background: #fafafa;
            flex-shrink: 0;
        }

        .sb-label {
            display: block;
            font-size: 11px; font-weight: 700; color: #475569;
            margin-bottom: 6px; text-transform: uppercase; letter-spacing: .05em;
        }
        .sb-input {
            width: 100%; box-sizing: border-box;
            border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 10px 12px; font-size: 13px; color: #0f172a;
            background: #f8fafc;
            outline: none; transition: all .15s;
            font-family: inherit;
        }
        .sb-input:focus { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
        textarea.sb-input { resize: vertical; min-height: 80px; }

        .sb-field { margin-bottom: 16px; }

        .size-btn-group { display: flex; gap: 6px; }
        .size-btn {
            flex:1; border: 1.5px solid #e5e7eb; background: #fff;
            border-radius: 8px; padding: 8px 4px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            color: #6b7280; transition: all .15s;
            text-align: center;
        }
        .size-btn.active {
            border-color: #6366f1; background: #eef2ff; color: #4f46e5;
        }
        .size-btn:hover:not(.active) { border-color: #a5b4fc; color: #4f46e5; }

        /* Dynamic config area */
        #config-fields .sb-field { border-top: none; }
        #config-fields > .section-label {
            font-size: 10px; font-weight: 800; text-transform: uppercase;
            letter-spacing: .1em; color: #94a3b8;
            margin: 16px 0 12px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;
        }

        .dyn-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
        .drag-row-handle {
            color: #d1d5db; cursor: grab; padding: 4px;
            transition: color .15s; display: flex; align-items: center; justify-content: center;
        }
        .drag-row-handle:hover { color: #6b7280; }
        .drag-row-handle:active { cursor: grabbing; }
        .dyn-row input { flex:1; min-width: 0; }
        .remove-row-btn {
            background: none; border:none; cursor:pointer;
            color: #d1d5db; padding: 4px; border-radius:4px; flex-shrink:0;
            transition: color .15s;
        }
        .remove-row-btn:hover { color: #ef4444; }

        .add-row-btn {
            background: #f1f5f9; border: 1px dashed #cbd5e1;
            border-radius: 8px; width:100%; padding:8px;
            color: #475569; font-size:12px; font-weight:600; cursor:pointer;
            transition: all .15s; margin-top:4px;
        }
        .add-row-btn:hover { background:#eef2ff; border-color: #a5b4fc; color: #4f46e5; }

        /* Toggle switch */
        .toggle-switch {
            position:relative; display:inline-block; width:44px; height:24px;
        }
        .toggle-switch input { opacity:0; width:0; height:0; }
        .toggle-slider {
            position:absolute; cursor:pointer; inset:0;
            background:#e5e7eb; border-radius:24px; transition:.25s;
        }
        .toggle-slider:before {
            content:''; position:absolute;
            height:18px; width:18px; left:3px; bottom:3px;
            background:#fff; border-radius:50%; transition:.25s;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        input:checked + .toggle-slider { background:#6366f1; }
        input:checked + .toggle-slider:before { transform: translateX(20px); }

        /* ─── Add Block Modal ──────────────────────────────────── */
        #add-modal-backdrop {
            position:fixed; inset:0; background:rgba(0,0,0,.5);
            z-index:2000; display:none; align-items:center; justify-content:center;
            backdrop-filter: blur(3px);
        }
        #add-modal-backdrop.open { display:flex; }
        #add-modal {
            background:#fff; border-radius:20px;
            width: min(600px, calc(100vw - 48px));
            max-height: calc(100vh - 80px);
            display:flex; flex-direction:column;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            animation: modalIn .2s ease;
        }
        @keyframes modalIn { from { transform:scale(.95); opacity:0; } to { transform:scale(1); opacity:1; } }
        #add-modal-header { padding:20px 24px 16px; border-bottom:1px solid #f3f4f6; flex-shrink:0; }
        #add-modal-body { flex:1; overflow-y:auto; padding:20px 24px; }
        #add-modal-footer { padding:14px 24px; border-top:1px solid #f3f4f6; flex-shrink:0; display:flex; gap:10px; justify-content:flex-end; }

        /* Modal config sections */
        .modal-cfg { display:none; }
        .modal-cfg.visible { display:block; }

        /* ─── FAB ──────────────────────────────────────────────── */
        #fab-add {
            position:fixed; bottom:32px; right:32px; z-index:800;
            width:56px; height:56px;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            border:none; border-radius:50%; color:#fff;
            font-size:28px; cursor:pointer;
            box-shadow: 0 4px 20px rgba(99,102,241,.5);
            display:flex; align-items:center; justify-content:center;
            transition: transform .2s, box-shadow .2s;
        }
        #fab-add:hover { transform:scale(1.12); box-shadow:0 6px 28px rgba(99,102,241,.6); }

        /* ─── Toast ─────────────────────────────────────────────── */
        #toast-container {
            position:fixed; bottom:90px; left:50%; transform:translateX(-50%);
            z-index:9999; display:flex; flex-direction:column; align-items:center; gap:8px;
            pointer-events:none;
        }
        .toast {
            padding:10px 20px; border-radius:99px;
            font-size:13px; font-weight:600;
            box-shadow:0 4px 16px rgba(0,0,0,.15);
            animation: toastIn .25s ease;
        }
        .toast.success { background:#22c55e; color:#fff; }
        .toast.error   { background:#ef4444; color:#fff; }
        @keyframes toastIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
    </style>
</head>
<body>

{{-- ════════════════════ TOP BAR ════════════════════ --}}
<div id="editor-topbar">
    {{-- Logo --}}
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
    </svg>
    <a href="{{ route('admin.dashboard') }}" class="text-indigo-300 hover:text-white text-sm font-bold tracking-wide transition">BlockCraft</a>
    <span class="text-indigo-500 mx-1">/</span>
    <span class="text-indigo-200 text-sm font-medium">{{ $site->name }}</span>
    <span class="text-indigo-500 mx-1">/</span>
    <span class="text-indigo-400 text-xs">Visual Editor</span>

    <div class="flex-1"></div>

    <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
       class="flex items-center gap-1.5 text-indigo-200 hover:text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-white/10 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 6h18M3 18h18"/>
        </svg>
        Table View
    </a>

    <a href="{{ route('client.page', $site->slug) }}" target="_blank"
       class="flex items-center gap-1.5 text-indigo-200 hover:text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-white/10 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Live Site ↗
    </a>

    <button onclick="openAddModal()"
            class="flex items-center gap-1.5 bg-indigo-500 hover:bg-indigo-400 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Block
    </button>
</div>

{{-- ════════════════════ CANVAS ════════════════════ --}}
<main id="editor-canvas">
    @if($allBlocks->isEmpty())
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
            </svg>
            <p class="text-lg font-medium text-gray-300">No blocks yet</p>
            <button onclick="openAddModal()" class="mt-4 bg-indigo-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition">
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

            {{-- Toolbar shown on hover --}}
            <div class="block-toolbar">
                <div class="tb-btn drag-block-handle" style="cursor: grab;" title="Drag to reorder">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
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
                </button>
            </div>

            {{-- Inactive badge --}}
            @if(!$block->is_active)
                <div class="inactive-badge" id="inactive-badge-{{ $block->id }}">Hidden on client</div>
            @else
                <div class="inactive-badge" id="inactive-badge-{{ $block->id }}" style="display:none">Hidden on client</div>
            @endif

            {{-- Exact same rendering as the client page --}}
            <div class="block-content" id="block-content-{{ $block->id }}">
                @includeIf('client.blocks.' . $block->type, [
                    'config' => $block->config ?? [],
                    'title'  => $block->title,
                ])
            </div>
        </div>
        @endforeach
    @endif
</main>

{{-- ════════════════════ SIDEBAR ════════════════════ --}}
<div id="editor-sidebar">
    <div id="sidebar-header">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-2">
                <span id="sb-type-badge" class="text-xs font-bold uppercase px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700">—</span>
                <span id="sb-block-id" class="text-xs text-gray-400">#—</span>
            </div>
            <button onclick="closeSidebar()"
                    class="text-gray-400 hover:text-gray-700 transition p-1 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <h2 id="sb-title-display" class="font-bold text-gray-900 text-lg leading-tight">Block Editor</h2>
    </div>

    <div id="sidebar-body">

        {{-- Block title --}}
        <div class="sb-field">
            <label class="sb-label">Block Title</label>
            <input type="text" id="sb-title" class="sb-input" placeholder="e.g. Hero Banner">
        </div>

        {{-- Size selector --}}
        <div class="sb-field">
            <label class="sb-label">Block Size</label>
            <div class="size-btn-group">
                <button class="size-btn" data-size="sm" onclick="selectSize('sm')">S — Compact</button>
                <button class="size-btn active" data-size="md" onclick="selectSize('md')">M — Normal</button>
                <button class="size-btn" data-size="lg" onclick="selectSize('lg')">L — Large</button>
            </div>
        </div>

        {{-- Active toggle --}}
        <div class="sb-field flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-800">Visible on Client Page</p>
                <p class="text-xs text-gray-400 mt-0.5">Toggle to show/hide this block</p>
            </div>
            <label class="toggle-switch">
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
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition text-sm shadow-sm">
            Save Changes
        </button>
        <button onclick="closeSidebar()"
                class="w-full mt-2 text-gray-500 hover:text-gray-700 font-medium py-2 rounded-xl transition text-sm hover:bg-gray-100">
            Cancel
        </button>
    </div>
</div>

{{-- ════════════════════ ADD BLOCK MODAL ════════════════════ --}}
<div id="add-modal-backdrop" onclick="closeAddModal()">
    <div id="add-modal" onclick="event.stopPropagation()">
        <div id="add-modal-header">
            <h2 class="text-lg font-bold text-gray-900">Add New Block</h2>
            <p class="text-xs text-gray-400 mt-0.5">Choose a type and configure, then save to see it on the canvas.</p>
        </div>
        <div id="add-modal-body" class="space-y-4">

            {{-- Title --}}
            <div class="sb-field">
                <label class="sb-label">Block Title <span class="text-red-400">*</span></label>
                <input type="text" id="add-title" class="sb-input" placeholder="e.g. Hero Banner">
            </div>

            {{-- Type --}}
            <div class="sb-field">
                <label class="sb-label">Type <span class="text-red-400">*</span></label>
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
            <div class="sb-field">
                <label class="sb-label">Block Size</label>
                <div class="size-btn-group" id="add-size-group">
                    <button class="size-btn" data-size="sm" onclick="selectAddSize('sm')">S</button>
                    <button class="size-btn active" data-size="md" onclick="selectAddSize('md')">M</button>
                    <button class="size-btn" data-size="lg" onclick="selectAddSize('lg')">L</button>
                </div>
            </div>

            {{-- Active toggle --}}
            <div class="sb-field flex items-center justify-between">
                <p class="text-sm font-semibold text-gray-800">Active (visible on client)</p>
                <label class="toggle-switch">
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
                    <label class="sb-label">Card Title <span class="text-red-400">*</span></label>
                    <input type="text" id="add-card-title" class="sb-input" placeholder="Feature title">
                </div>
                <div class="sb-field">
                    <label class="sb-label">Description <span class="text-red-400">*</span></label>
                    <textarea id="add-card-desc" class="sb-input" placeholder="Short description…"></textarea>
                </div>
                <div class="sb-field">
                    <label class="sb-label">Image URL <span class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <input type="url" id="add-card-image" class="sb-input" placeholder="https://...">
                </div>
            </div>

            {{-- List config --}}
            <div class="modal-cfg" id="modal-cfg-list">
                <p class="text-xs font-bold text-amber-500 uppercase tracking-wider mb-3">List Config</p>
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
                    <label class="sb-label">Background Style</label>
                    <select id="add-header-bg" class="sb-input">
                        <option value="light">Light (Default)</option>
                        <option value="dark">Dark</option>
                        <option value="gradient">Gradient</option>
                    </select>
                </div>
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
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                Create Block
            </button>
        </div>
    </div>
</div>

{{-- FAB --}}
<button id="fab-add" onclick="openAddModal()" title="Add new block">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Toast container --}}
<div id="toast-container"></div>

{{-- ════════════════════ EDITOR JAVASCRIPT ════════════════════ --}}
<script>
const CSRF      = document.querySelector('meta[name="csrf-token"]').content;
const BASE_URL  = '{{ rtrim(url("/"), "/") }}';
const SITE_BASE = '{{ rtrim(url("/admin/sites/" . $site->id), "/") }}';
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
['add-list-items', 'add-stats-items', 'add-header-nav-links', 'add-footer-links', 'add-footer-social'].forEach(id => {
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
            html = `
              <p class="section-label">Card Config</p>
              <div class="sb-field">
                <label class="sb-label">Card Title</label>
                <input type="text" id="cfg-card-title" class="sb-input" value="${esc(c.title ?? '')}" placeholder="Feature title">
              </div>
              <div class="sb-field">
                <label class="sb-label">Description</label>
                <textarea id="cfg-card-desc" class="sb-input" placeholder="Short description…">${esc(c.description ?? '')}</textarea>
              </div>
              <div class="sb-field">
                <label class="sb-label">Image URL <span style="font-weight:400;color:#9ca3af;">(optional)</span></label>
                <input type="url" id="cfg-card-image" class="sb-input" value="${esc(c.image_url ?? '')}" placeholder="https://...">
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
            html = `
              <p class="section-label">List Config</p>
              <div class="sb-field">
                <label class="sb-label">Items</label>
                <div id="sb-list-items" class="space-y-2 mb-2">${itemsHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbListItem()">+ Add item</button>
              </div>`;
            break;

        case 'stats':
            const stats = c.stats ?? [{ label:'', value:'' }];
            const statsHtml = stats.map((s, i) => `
              <div class="dyn-row">
                <div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                <input type="text" class="sb-input stat-label" value="${esc(s.label ?? '')}" placeholder="Label">
                <input type="text" class="sb-input stat-value" value="${esc(s.value ?? '')}" placeholder="Value">
                <button type="button" class="remove-row-btn" onclick="removeSbRow(this, 'sb-stats-items', 1)">✕</button>
              </div>`).join('');
            html = `
              <p class="section-label">Stats Config</p>
              <div class="sb-field">
                <label class="sb-label">Statistics</label>
                <div id="sb-stats-items" class="space-y-2 mb-2">${statsHtml}</div>
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
                <label class="sb-label">Background Style</label>
                <select id="cfg-header-bg" class="sb-input">
                    <option value="light" ${c.bg_style === 'light' ? 'selected' : ''}>Light (Default)</option>
                    <option value="dark" ${c.bg_style === 'dark' ? 'selected' : ''}>Dark</option>
                    <option value="gradient" ${c.bg_style === 'gradient' ? 'selected' : ''}>Gradient</option>
                </select>
              </div>
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
                <div id="sb-nav-items" class="space-y-2 mb-2">${navHtml}</div>
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
                <div id="sb-footer-links" class="space-y-2 mb-2">${qlHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbFooterLink()">+ Add link</button>
              </div>
              <div class="sb-field">
                <label class="sb-label">Social Links</label>
                <div id="sb-social-links" class="space-y-2 mb-2">${slHtml}</div>
                <button type="button" class="add-row-btn" onclick="addSbSocialLink()">+ Add social</button>
              </div>`;
            break;
    }

    container.innerHTML = html;

    // Init sorting for dynamic arrays in sidebar
    ['sb-list-items', 'sb-stats-items', 'sb-nav-items', 'sb-footer-links', 'sb-social-links'].forEach(id => {
        const el = document.getElementById(id);
        if(el) Sortable.create(el, { handle: '.drag-row-handle', animation: 150 });
    });
}

// ─── Dynamic row helpers (sidebar) ────────────────────────────
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
                     <input type="text" class="sb-input stat-label" placeholder="Label">
                     <input type="text" class="sb-input stat-value" placeholder="Value">
                     <button type="button" class="remove-row-btn" onclick="removeSbRow(this,'sb-stats-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
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

    let config = { ...(block.config ?? {}), size };

    switch (block.type) {
        case 'banner':
            config.image_url = document.getElementById('cfg-image_url')?.value.trim() ?? '';
            config.link      = document.getElementById('cfg-link')?.value.trim() ?? '';
            break;
        case 'card':
            config.title       = document.getElementById('cfg-card-title')?.value.trim() ?? '';
            config.description = document.getElementById('cfg-card-desc')?.value.trim() ?? '';
            config.image_url   = document.getElementById('cfg-card-image')?.value.trim() ?? '';
            break;
        case 'list':
            config.items = [...document.querySelectorAll('#sb-list-items .list-item-input')]
                .map(i => i.value.trim()).filter(Boolean);
            break;
        case 'stats':
            config.stats = [...document.querySelectorAll('#sb-stats-items .dyn-row')]
                .map(row => ({
                    label: row.querySelector('.stat-label')?.value.trim() ?? '',
                    value: row.querySelector('.stat-value')?.value.trim() ?? '',
                })).filter(s => s.label || s.value);
            break;
        case 'header':
            config.bg_style  = document.getElementById('cfg-header-bg')?.value ?? 'light';
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
function addListItemModal() {
    const div = document.createElement('div'); div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input" placeholder="List item…">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-list-items', 1)">✕</button>`;
    document.getElementById('add-list-items').appendChild(div);
}
function addStatItemModal() {
    const div = document.createElement('div'); div.className = 'dyn-row';
    div.innerHTML = `<div class="drag-row-handle"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg></div>
                     <input type="text" class="sb-input stat-label" placeholder="Label">
                     <input type="text" class="sb-input stat-value" placeholder="Value">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this, 'add-stats-items', 1)">✕</button>`;
    document.getElementById('add-stats-items').appendChild(div);
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
}
function closeAddModal() {
    document.getElementById('add-modal-backdrop').classList.remove('open');
}
function switchAddModalType(type) {
    document.querySelectorAll('.modal-cfg').forEach(el => el.classList.remove('visible'));
    document.getElementById(`modal-cfg-${type}`)?.classList.add('visible');
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
                     <input type="text" class="sb-input stat-label" placeholder="Label">
                     <input type="text" class="sb-input stat-value" placeholder="Value">
                     <button type="button" class="remove-row-btn" onclick="removeRow(this,'add-stats-items',1)">✕</button>`;
    cont.appendChild(div);
    div.querySelector('input').focus();
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

    if (!title) { errBox.textContent='Title is required.'; errBox.classList.remove('hidden'); return; }

    let config = { size };

    if (type === 'banner') {
        config.image_url = document.getElementById('add-banner-image').value.trim();
        config.link      = document.getElementById('add-banner-link').value.trim();
        if (!config.image_url || !config.link) {
            errBox.textContent='Image URL and Link URL are required for Banner.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'card') {
        config.title       = document.getElementById('add-card-title').value.trim();
        config.description = document.getElementById('add-card-desc').value.trim();
        config.image_url   = document.getElementById('add-card-image').value.trim();
        if (!config.title || !config.description) {
            errBox.textContent='Card title and description are required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'list') {
        config.items = [...document.querySelectorAll('#add-list-items input')]
            .map(i => i.value.trim()).filter(Boolean);
        if (!config.items.length) {
            errBox.textContent='At least one list item is required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'stats') {
        config.stats = [...document.querySelectorAll('#add-stats-items .dyn-row')]
            .map(row => ({
                label: row.querySelector('.stat-label')?.value.trim() ?? '',
                value: row.querySelector('.stat-value')?.value.trim() ?? '',
            })).filter(s => s.label || s.value);
        if (!config.stats.length) {
            errBox.textContent='At least one stat is required.';
            errBox.classList.remove('hidden'); return;
        }
    } else if (type === 'header') {
        config.bg_style  = document.getElementById('add-header-bg')?.value ?? 'light';
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
</script>
</body>
</html>
