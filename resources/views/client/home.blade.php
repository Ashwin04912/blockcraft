@extends('layouts.app')

@section('content')
    {{-- Ambient Background for Client Page --}}
    <div class="position-fixed top-0 start-0 w-100 h-100 pe-none" style="z-index: -10; background-color: #f8fafc;">
        <div class="position-absolute rounded-circle mix-blend-multiply" style="top: -20%; left: -10%; width: 70%; height: 70%; background-color: rgba(165, 180, 252, 0.2); filter: blur(120px);"></div>
        <div class="position-absolute rounded-circle mix-blend-multiply" style="top: 10%; right: -10%; width: 60%; height: 60%; background-color: rgba(216, 180, 254, 0.2); filter: blur(120px);"></div>
        <div class="position-absolute rounded-circle mix-blend-multiply" style="bottom: -20%; left: 20%; width: 80%; height: 80%; background-color: rgba(249, 168, 212, 0.2); filter: blur(120px);"></div>
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiM2MzY2ZjEiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg=='); opacity: 0.15;"></div>
    </div>

    @if($blocks->isEmpty())
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5 mt-5">
            <div class="bg-white bg-opacity-50 rounded-circle d-flex align-items-center justify-content-center shadow-sm border border-white mb-4" style="width: 80px; height: 80px; backdrop-filter: blur(24px);">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <p class="fw-bold fs-3 text-dark mb-1 tracking-tight">No active blocks</p>
            <p class="text-secondary fw-medium">Activate some blocks in the admin panel to see them here.</p>
        </div>
    @else
        <div class="w-100 d-flex flex-column px-3 py-2 gap-3">
            @foreach($blocks as $block)
                <div class="w-100 position-relative" style="z-index: 10; transition: all 0.5s;">
                    {{ app(\App\Services\BlockRenderer::class)->view($block) }}
                </div>
            @endforeach
        </div>
    @endif
@endsection
