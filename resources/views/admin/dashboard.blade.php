@extends('layouts.app')

@section('title', 'Dashboard — BlockCraft')

@section('nav-links')
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    <span class="text-gray-800 font-semibold">Dashboard</span>
@endsection

@section('header-actions')
    <button onclick="document.getElementById('create-modal-backdrop').classList.add('open')"
            class="inline-flex items-center gap-2 bg-indigo-600 text-white font-medium text-sm px-4 py-2 rounded-xl hover:bg-indigo-700 transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Site
    </button>
@endsection

@section('content')
<div class="space-y-8">

    {{-- Welcome header --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-16 -mr-16 text-slate-50 opacity-50 pointer-events-none">
            <svg width="300" height="300" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"/></svg>
        </div>
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                    Welcome back, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-slate-500 text-sm mt-1.5">
                    Select a site below to open its Visual Editor, or create a new one to get started.
                </p>
            </div>
        </div>
    </div>

    {{-- Site cards --}}
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">Your Sites</h2>
        <span class="bg-slate-200 text-slate-700 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $sites->count() }} total</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($sites as $site)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition duration-200 group flex flex-col hover:border-indigo-200 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                
                {{-- Card body --}}
                <div class="p-6 flex-1">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="font-bold text-slate-900 text-lg leading-tight truncate group-hover:text-indigo-600 transition">
                                {{ $site->name }}
                            </h2>
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2 min-h-[40px]">{{ $site->description ?: 'No description provided.' }}</p>
                        </div>
                        {{-- Delete --}}
                        <form method="POST" action="{{ route('admin.sites.destroy', $site) }}"
                              onsubmit="return confirm('Delete site \'{{ addslashes($site->name) }}\' and ALL its blocks?')"
                              class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-slate-300 hover:text-red-500 transition p-1.5 rounded-lg hover:bg-red-50"
                                    title="Delete site">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Meta --}}
                    <div class="flex items-center gap-4 mt-5">
                        <div class="flex items-center gap-1.5 text-xs font-mono text-slate-500 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            /page/{{ $site->slug }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs font-medium text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            {{ $site->ui_blocks_count }} blocks
                        </div>
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="px-6 pb-5 flex items-center gap-2 flex-wrap">
                    {{-- PRIMARY: Visual Editor --}}
                    <a href="{{ route('admin.sites.visual-editor', $site) }}"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Visual Editor
                    </a>

                    {{-- Table view --}}
                    <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
                       class="inline-flex items-center justify-center gap-1.5 text-slate-500 hover:text-indigo-600 text-sm font-medium px-3 py-2.5 rounded-xl hover:bg-indigo-50 transition"
                       title="Manage Data">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </a>

                    {{-- View live --}}
                    <a href="{{ route('client.page', $site->slug) }}" target="_blank"
                       class="inline-flex items-center justify-center gap-1.5 text-slate-500 hover:text-emerald-600 text-sm font-medium px-3 py-2.5 rounded-xl hover:bg-emerald-50 transition"
                       title="View live page">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            {{-- Empty state --}}
            <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-gray-400 font-medium">No sites yet</p>
                <button onclick="document.getElementById('create-modal-backdrop').classList.add('open')"
                        class="mt-4 bg-indigo-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition">
                    Create your first site →
                </button>
            </div>
        @endforelse
    </div>

</div>

{{-- ── Create Site Modal ─────────────────────────────── --}}
<div id="create-modal-backdrop"
     style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1000;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px);"
     onclick="this.classList.remove('open')">
    <div style="background:#fff;border-radius:20px;width:min(500px,calc(100vw - 48px));box-shadow:0 24px 60px rgba(0,0,0,.25);animation:modalIn .2s ease;"
         onclick="event.stopPropagation()">
        <div style="padding:24px 28px 18px;border-bottom:1px solid #f3f4f6;">
            <h2 class="text-lg font-bold text-gray-900">Create New Site</h2>
            <p class="text-xs text-gray-400 mt-0.5">Set a name and slug — you'll configure blocks in the Visual Editor.</p>
        </div>
        <form method="POST" action="{{ route('admin.sites.store') }}" style="padding:24px 28px;">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Site Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="create-name" value="{{ old('name') }}"
                           placeholder="e.g. Marketing Landing Page"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        URL Slug <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-gray-400 ml-1">— lowercase letters, numbers, hyphens only</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-400 font-mono flex-shrink-0">/page/</span>
                        <input type="text" name="slug" id="create-slug" value="{{ old('slug') }}"
                               placeholder="my-site"
                               class="flex-1 border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-mono focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition"
                               required pattern="[a-z0-9]+(?:-[a-z0-9]+)*">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="description" rows="2"
                              placeholder="Short description of this site's purpose…"
                              class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-sm">
                    Create & Open Editor →
                </button>
                <button type="button" onclick="document.getElementById('create-modal-backdrop').classList.remove('open')"
                        class="px-5 text-gray-500 hover:text-gray-700 font-medium text-sm rounded-xl hover:bg-gray-100 transition">
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
