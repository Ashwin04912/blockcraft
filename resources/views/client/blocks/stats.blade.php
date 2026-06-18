{{--
    Stats block partial
    Variables: $title (string), $config (array)
    Config keys: stats (array of { label, value }), size (sm|md|lg)
--}}
@php
    $stats     = $config['stats'] ?? [];
    $size      = $config['size'] ?? 'md';
    $padding   = ['sm' => 'p-5',  'md' => 'p-8',  'lg' => 'p-12' ][$size] ?? 'p-8';
    $valSize   = ['sm' => 'text-3xl', 'md' => 'text-4xl', 'lg' => 'text-6xl'][$size] ?? 'text-4xl';
@endphp
<section class="py-4">
    <div class="flex items-center gap-3 mb-8 px-1 justify-center sm:justify-start">
        <span class="inline-block w-2 h-6 bg-gradient-to-b from-emerald-400 via-teal-500 to-cyan-500 rounded-full shadow-sm shadow-teal-500/50"></span>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-teal-600 bg-teal-50/80 px-3 py-1.5 rounded-full ml-auto ring-1 ring-teal-500/10 shadow-sm hidden sm:inline-block">Stats</span>
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
        @forelse($stats as $stat)
            <div class="relative bg-white/60 backdrop-blur-3xl rounded-[2rem] shadow-xl shadow-slate-200/50 ring-1 ring-slate-900/5 {{ $padding }} text-center hover:shadow-2xl hover:shadow-teal-500/20 hover:-translate-y-2 transition-all duration-500 group overflow-hidden">
                
                {{-- Shine effect on hover --}}
                <div class="absolute inset-0 bg-gradient-to-tr from-white/0 via-white/40 to-white/0 opacity-0 group-hover:opacity-100 group-hover:translate-x-full transition-all duration-1000 -translate-x-full"></div>
                
                {{-- Inner ambient glow --}}
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-teal-400/10 rounded-full blur-2xl group-hover:bg-teal-400/20 transition-colors duration-500"></div>
                <div class="absolute -top-8 -left-8 w-24 h-24 bg-cyan-400/10 rounded-full blur-2xl group-hover:bg-cyan-400/20 transition-colors duration-500"></div>

                <div class="relative z-10 {{ $valSize }} font-black bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 bg-clip-text text-transparent tracking-tighter drop-shadow-sm group-hover:scale-105 transition-transform duration-500">
                    {{ $stat['value'] ?? '—' }}
                </div>
                
                <div class="relative z-10 text-[11px] sm:text-xs text-slate-500 font-bold mt-3 uppercase tracking-[0.2em] group-hover:text-teal-700 transition-colors duration-300">
                    {{ $stat['label'] ?? '' }}
                </div>
            </div>
        @empty
            <div class="col-span-full text-center bg-slate-50/50 rounded-3xl border border-dashed border-slate-200 py-12">
                <span class="text-slate-400 text-sm font-medium">No stats configured.</span>
            </div>
        @endforelse
    </div>
</section>
