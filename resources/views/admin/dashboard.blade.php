@extends('layouts.app')

@section('title', 'Dashboard — BlockCraft')

@section('nav-links')
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="text-secondary me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span class="text-dark fw-semibold">Dashboard</span>
@endsection

@section('header-actions')
    <button onclick="document.getElementById('create-modal-backdrop').classList.add('open')"
            class="btn btn-primary d-inline-flex align-items-center gap-2 fw-medium shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Site
    </button>
@endsection

@section('content')
<div class="d-flex flex-column gap-4">

    {{-- Welcome header --}}
    <div class="bg-white border rounded-4 p-4 shadow-sm position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 mt-n4 me-n4 text-light opacity-50 pe-none" style="margin-top: -4rem; margin-right: -4rem;">
            <svg width="300" height="300" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        </div>
        <div class="position-relative z-1 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="fs-4 fw-bold text-dark mb-2">
                    Welcome back, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-secondary mb-0">
                    Select a site below to open its Visual Editor, or create a new one to get started.
                </p>
            </div>
        </div>
    </div>

    {{-- Site cards --}}
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="fs-5 fw-semibold text-dark mb-0">Your Sites</h2>
        <span class="badge bg-secondary text-light rounded-pill px-3">{{ $sites->count() }} total</span>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">

        @forelse($sites as $site)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm transition group" style="border-radius: 1rem; overflow: hidden; position: relative;">
                    <div class="position-absolute top-0 start-0 w-100 bg-primary opacity-0 transition group-hover-opacity-100" style="height: 4px; transition: opacity 0.3s;"></div>
                    
                    {{-- Card body --}}
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                            <div class="min-w-0">
                                <h2 class="fw-bold text-dark fs-5 text-truncate mb-1">
                                    {{ $site->name }}
                                </h2>
                                <p class="text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 40px;">
                                    {{ $site->description ?: 'No description provided.' }}
                                </p>
                            </div>
                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.sites.destroy', $site) }}"
                                  onsubmit="return confirm('Delete site \'{{ addslashes($site->name) }}\' and ALL its blocks?')"
                                  class="flex-shrink-0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="btn btn-light btn-sm text-secondary hover-danger"
                                        title="Delete site">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- Meta --}}
                        <div class="d-flex align-items-center gap-3 mt-auto pt-2">
                            <div class="d-flex align-items-center gap-1 font-monospace text-secondary bg-light border px-2 py-1 rounded small">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                /page/{{ $site->slug }}
                            </div>
                            <div class="d-flex align-items-center gap-1 text-secondary small fw-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                {{ $site->ui_blocks_count }} blocks
                            </div>
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="card-footer bg-transparent border-0 px-4 pb-4 pt-0 d-flex gap-2 flex-wrap">
                        {{-- PRIMARY: Visual Editor --}}
                        <a href="{{ route('admin.sites.visual-editor', $site) }}"
                           class="btn btn-primary flex-grow-1 d-inline-flex justify-content-center align-items-center gap-2 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Visual Editor
                        </a>

                        {{-- Table view --}}
                        <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
                           class="btn btn-light text-secondary hover-primary d-inline-flex justify-content-center align-items-center"
                           title="Manage Data">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </a>

                        {{-- View live --}}
                        <a href="{{ route('client.page', $site->slug) }}" target="_blank"
                           class="btn btn-light text-secondary hover-success d-inline-flex justify-content-center align-items-center"
                           title="View live page">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty state --}}
            <div class="col-12 text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-secondary fw-medium">No sites yet</p>
                <button onclick="document.getElementById('create-modal-backdrop').classList.add('open')"
                        class="btn btn-primary fw-semibold mt-2">
                    Create your first site &rarr;
                </button>
            </div>
        @endforelse
    </div>

</div>

{{-- ── Create Site Modal ─────────────────────────────── --}}
<div id="create-modal-backdrop"
     style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px);"
     onclick="this.classList.remove('open')">
    <div style="background:#fff;border-radius:1rem;width:min(500px,calc(100vw - 2rem));box-shadow:0 1rem 3rem rgba(0,0,0,.175);animation:modalIn .2s ease;"
         onclick="event.stopPropagation()">
        <div class="p-4 border-bottom">
            <h2 class="fs-5 fw-bold text-dark mb-1">Create New Site</h2>
            <p class="text-secondary small mb-0">Set a name and slug — you'll configure blocks in the Visual Editor.</p>
        </div>
        <form method="POST" action="{{ route('admin.sites.store') }}" class="p-4">
            @csrf
            @if($errors->any())
                <div class="alert alert-danger p-3 mb-4 small">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="d-flex flex-column gap-3">
                <div>
                    <label class="form-label fw-semibold small mb-1">Site Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="create-name" value="{{ old('name') }}"
                           placeholder="e.g. Marketing Landing Page"
                           class="form-control"
                           required>
                </div>
                <div>
                    <label class="form-label fw-semibold small mb-1">
                        URL Slug <span class="text-danger">*</span>
                        <span class="text-secondary fw-normal ms-1">— lowercase letters, numbers, hyphens only</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text text-secondary font-monospace bg-light">/page/</span>
                        <input type="text" name="slug" id="create-slug" value="{{ old('slug') }}"
                               placeholder="my-site"
                               class="form-control font-monospace"
                               required pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
                    </div>
                </div>
                <div>
                    <label class="form-label fw-semibold small mb-1">Description <span class="text-secondary fw-normal">(optional)</span></label>
                    <textarea name="description" rows="2"
                              placeholder="Short description of this site's purpose…"
                              class="form-control">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit"
                        class="btn btn-primary flex-grow-1 fw-bold">
                    Create & Open Editor &rarr;
                </button>
                <button type="button" onclick="document.getElementById('create-modal-backdrop').classList.remove('open')"
                        class="btn btn-light text-secondary fw-medium">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<style>
    #create-modal-backdrop.open { display:flex !important; }
    @keyframes modalIn { from{transform:scale(.95);opacity:0} to{transform:scale(1);opacity:1} }
    .group:hover .group-hover-opacity-100 { opacity: 1 !important; }
    .hover-danger:hover { color: #dc3545 !important; background-color: rgba(220, 53, 69, 0.1); }
    .hover-primary:hover { color: #0d6efd !important; background-color: rgba(13, 110, 253, 0.1); }
    .hover-success:hover { color: #198754 !important; background-color: rgba(25, 135, 84, 0.1); }
</style>
<script>
// Auto-generate slug from site name
document.getElementById('create-name').addEventListener('input', function() {
    const slugEl = document.getElementById('create-slug');
    // Only auto-fill if user hasn't typed their own slug yet
    if (!slugEl.dataset.manual) {
        slugEl.value = this.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }
});
document.getElementById('create-slug').addEventListener('input', function() {
    this.dataset.manual = 'true';
});
</script>
@endpush
