<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor — {{ $site->name }} — BlockCraft</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        // Server-rendered config for public/js/editor.js — kept to the bare
        // minimum (two URLs) so editor.js stays a plain static asset with
        // no Blade interpolation inside it.
        window.EDITOR_CONFIG = {
            baseUrl: "{!! rtrim(url('/'), '/') !!}",
            siteBase: "{!! rtrim(url('/admin/sites/' . $site->id), '/') !!}"
        };
    </script>
</head>
<body>

@include('admin.preview._topbar')
@include('admin.preview._canvas')
@include('admin.preview._sidebar')
@include('admin.preview._add-modal')

{{-- FAB --}}
<button id="fab-add" onclick="openAddModal()" title="Add new block">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
    </svg>
</button>

{{-- Toast container --}}
<div id="toast-container"></div>

<script src="{{ asset('js/editor.js') }}"></script>
</body>
</html>
