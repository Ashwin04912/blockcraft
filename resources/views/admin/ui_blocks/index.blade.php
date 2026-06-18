@extends('layouts.app')

@section('title', $site->name . ' — Blocks')

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-slate-800 font-semibold">{{ $site->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.sites.visual-editor', $site) }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-indigo-600 px-3 py-2 rounded-xl hover:bg-indigo-50 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Visual Editor
    </a>
    <a href="{{ route('admin.sites.ui-blocks.create', $site) }}"
       class="inline-flex items-center gap-2 bg-indigo-600 text-white font-medium text-sm px-4 py-2 rounded-xl hover:bg-indigo-700 transition shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Block
    </a>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Blocks Overview</h1>
        <a href="{{ route('client.page', $site->slug) }}" target="_blank"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-emerald-600 font-mono transition bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
           /page/{{ $site->slug }}
        </a>
    </div>

    {{-- Blocks table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @if($blocks->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="text-slate-500 font-medium">No blocks configured yet.</p>
                <a href="{{ route('admin.sites.ui-blocks.create', $site) }}" class="mt-3 text-indigo-600 text-sm font-semibold hover:text-indigo-800 transition">Create your first block →</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-4 w-10"></th>
                        <th class="px-5 py-4 text-left font-semibold">Order</th>
                        <th class="px-5 py-4 text-left font-semibold">Title</th>
                        <th class="px-5 py-4 text-left font-semibold">Type</th>
                        <th class="px-5 py-4 text-center font-semibold">Visibility</th>
                        <th class="px-5 py-4 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-body" class="divide-y divide-slate-100">
                    @foreach($blocks as $block)
                        <tr data-id="{{ $block->id }}" class="hover:bg-slate-50/80 transition group">
                            <td class="px-5 py-4 text-slate-300 cursor-grab active:cursor-grabbing group-hover:text-slate-500 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </td>
                            <td class="px-5 py-4 text-slate-400 font-mono text-xs sort-order">{{ $block->display_order }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-800">{{ $block->title }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $typeColors = ['banner'=>'bg-purple-100 text-purple-700','card'=>'bg-blue-100 text-blue-700','list'=>'bg-amber-100 text-amber-700','stats'=>'bg-emerald-100 text-emerald-700','header'=>'bg-slate-200 text-slate-700','footer'=>'bg-slate-800 text-slate-100'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold tracking-wide uppercase {{ $typeColors[$block->type] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $block->type }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <button id="toggle-btn-{{ $block->id }}"
                                        onclick="toggleBlock('{{ $block->id }}', this)"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 {{ $block->is_active ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $block->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.sites.ui-blocks.edit', [$site, $block]) }}"
                                       class="inline-flex items-center gap-1.5 text-slate-500 hover:text-indigo-600 transition text-xs font-medium px-2.5 py-1.5 rounded-lg hover:bg-indigo-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.sites.ui-blocks.destroy', [$site, $block]) }}"
                                          onsubmit="return confirm('Delete this block?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-slate-500 hover:text-red-600 transition text-xs font-medium px-2.5 py-1.5 rounded-lg hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @if($blocks->isNotEmpty())
        <p class="text-xs text-slate-400 text-center flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            Drag rows to reorder — saved automatically.
        </p>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const body = document.getElementById('sortable-body');
if (body) {
    Sortable.create(body, {
        handle: 'td:first-child', animation: 150, ghostClass: 'opacity-40',
        onEnd() {
            const order = [...body.querySelectorAll('tr[data-id]')].map(tr => parseInt(tr.dataset.id));
            body.querySelectorAll('.sort-order').forEach((cell, i) => { cell.textContent = i; });
            fetch("{{ route('admin.sites.ui-blocks.reorder', $site) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: JSON.stringify({ order }),
            }).catch(e => console.error(e));
        },
    });
}
function toggleBlock(id, btn) {
    const url = "{{ route('admin.sites.ui-blocks.toggle', [$site, '__ID__']) }}".replace('__ID__', id);
    fetch(url, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
    }).then(r => r.json()).then(data => {
        const on = data.is_active;
        btn.classList.toggle('bg-emerald-500', on);
        btn.classList.toggle('bg-slate-200', !on);
        const knob = btn.querySelector('span');
        knob.classList.toggle('translate-x-6', on);
        knob.classList.toggle('translate-x-1', !on);
    });
}
</script>
@endpush
