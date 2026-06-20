@extends('layouts.app')

@section('title', 'Create Block — ' . $site->name)

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('admin.sites.ui-blocks.index', $site) }}" class="hover:text-indigo-600 transition">{{ $site->name }}</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 mx-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-slate-800 font-semibold">New Block</span>
@endsection

@section('content')
<div class="container mt-4" style="max-width: 800px;">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold text-dark mb-0">Create UI Block</h1>
        <a href="{{ route('admin.sites.ui-blocks.index', $site) }}"
           class="btn btn-link text-decoration-none text-secondary d-inline-flex align-items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Blocks
        </a>
    </div>
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <form method="POST" action="{{ route('admin.sites.ui-blocks.store', $site) }}" id="block-form" novalidate>
            @csrf
            @include('admin.ui_blocks._form', ['block' => null])
            <div class="mt-4 pt-3 border-top d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 rounded-3 shadow-sm fw-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Block
                </button>
                <a href="{{ route('admin.sites.ui-blocks.index', $site) }}" class="btn btn-light px-4 py-2 rounded-3 text-secondary fw-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@include('admin.ui_blocks._form_scripts')
