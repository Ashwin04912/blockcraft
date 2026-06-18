@extends('layouts.app')

@section('title', 'Edit Block — ' . $site->name)

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('admin.sites.ui-blocks.index', $site) }}" class="hover:text-indigo-600 transition">{{ $site->name }}</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-slate-800 font-semibold">Edit Block</span>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Block</h1>
        <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Blocks
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <form method="POST" action="{{ route('admin.sites.ui-blocks.update', [$site, $uiBlock]) }}" id="block-form" novalidate>
            @csrf @method('PUT')
            @include('admin.ui_blocks._form', ['block' => $uiBlock])
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white font-semibold px-6 py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.sites.ui-blocks.index', $site) }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition px-5 py-2.5 rounded-xl hover:bg-slate-100">Cancel</a>
                <form method="POST" action="{{ route('admin.sites.ui-blocks.destroy', [$site, $uiBlock]) }}"
                      class="ml-auto" onsubmit="return confirm('Delete this block?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 text-sm text-red-500 hover:text-red-700 transition font-medium px-3 py-2 rounded-xl hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Block
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection

@include('admin.ui_blocks._form_scripts')
