@extends('layouts.app')

@section('content')
    {{-- Ambient Background for Client Page --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 bg-slate-50">
        <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] rounded-full bg-indigo-300/20 blur-[120px] mix-blend-multiply"></div>
        <div class="absolute top-[10%] -right-[10%] w-[60%] h-[60%] rounded-full bg-purple-300/20 blur-[120px] mix-blend-multiply"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[80%] h-[80%] rounded-full bg-pink-300/20 blur-[120px] mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiM2MzY2ZjEiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-[0.15]"></div>
    </div>

    @if($blocks->isEmpty())
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-20 h-20 bg-white/50 backdrop-blur-xl rounded-full flex items-center justify-center shadow-lg shadow-indigo-500/10 mb-6 border border-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <p class="font-bold text-2xl text-slate-800 tracking-tight">No active blocks</p>
            <p class="text-slate-500 mt-2 font-medium">Activate some blocks in the admin panel to see them here.</p>
        </div>
    @else
       <div class="w-full flex flex-col px-4 py-2 gap-4">
    @foreach($blocks as $block)
        <div class="w-full relative z-10 transition-all duration-500">
            {{ app(\App\Services\BlockRenderer::class)->view($block) }}
        </div>
    @endforeach
</div>
    @endif
@endsection
