<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BlockCraft')</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
@php
    $isClientPage = request()->routeIs('client.page');
@endphp
<body class="d-flex min-vh-100 {{ $isClientPage ? '' : 'bg-light' }} {{ $isClientPage && isset($site) ? $site->background_text_class : 'text-dark' }}"
      @if($isClientPage && isset($site)) style="background-color: '{{ $site->background_color }}';" @endif>

    @if(!request()->routeIs('client.page'))
    {{-- Sidebar --}}
    <aside class="d-flex flex-column flex-shrink-0 min-vh-100 sticky-top bg-dark text-light" style="width: 250px;">
        <div class="d-flex align-items-center px-4 border-bottom border-secondary" style="height: 64px;">
            <a href="{{ auth()->check() ? route('admin.dashboard') : route('login') }}"
               class="d-flex align-items-center gap-2 fw-bold fs-4 text-white text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                BlockCraft
            </a>
        </div>
        <div class="flex-grow-1 overflow-auto py-4 px-3">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-light' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            @if(request()->routeIs('admin.sites.*') && isset($site))
            <a href="{{ route('admin.sites.ui-blocks.index', $site) }}" class="d-flex align-items-center gap-3 px-3 py-2 mt-1 rounded text-decoration-none {{ request()->routeIs('admin.sites.ui-blocks.*') ? 'bg-primary text-white' : 'text-light' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                UI Blocks
            </a>
            <a href="{{ route('admin.sites.visual-editor', $site) }}" class="d-flex align-items-center gap-3 px-3 py-2 mt-1 rounded text-decoration-none text-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Visual Editor
            </a>
            @endif
        </div>
        @auth
        <div class="p-3 border-top border-secondary flex-shrink-0">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-primary text-white fw-bold d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="flex-grow-1 text-truncate">
                    <div class="fw-bold text-white" style="font-size: 14px;">{{ auth()->user()->name }}</div>
                    <div class="text-secondary" style="font-size: 12px;">{{ auth()->user()->email ?? 'admin@blockcraft' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Logout
                </button>
            </form>
        </div>
        @endauth
    </aside>
    @endif

    <div class="flex-grow-1 d-flex flex-column min-vw-0 min-vh-100">
        @if(!request()->routeIs('client.page'))
        {{-- Top Header for Admin --}}
        <header class="bg-white border-bottom d-flex align-items-center px-4 justify-content-between sticky-top shadow-sm" style="height: 64px; z-index: 1030;">
            <div class="d-flex align-items-center gap-2 text-muted fw-medium" style="font-size: 14px;">
                @yield('nav-links')
            </div>
            <div class="d-flex align-items-center gap-3">
                @yield('header-actions')
            </div>
        </header>
        @endif

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="container-fluid pt-4">
                <div class="alert alert-success d-flex align-items-center shadow-sm" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="me-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
        @endif

        {{-- Main content --}}
        <main class="{{ request()->routeIs('client.page') ? 'w-100 position-relative' : 'p-4 flex-grow-1' }}">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
