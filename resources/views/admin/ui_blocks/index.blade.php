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
