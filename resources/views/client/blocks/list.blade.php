{{--
    List block partial
    Variables: $title (string), $config (array)
    Config keys: items (array of strings), size (sm|md|lg)
--}}
@php
    $items   = $config['items'] ?? [];
    $size    = $config['size'] ?? 'md';
    $padding = ['sm' => 'px-5 py-3.5', 'md' => 'px-6 py-4.5', 'lg' => 'px-8 py-6'][$size] ?? 'px-6 py-4.5';
    $badgeW  = ['sm' => 'w-8 h-8 text-sm', 'md' => 'w-10 h-10 text-base', 'lg' => 'w-12 h-12 text-lg'][$size] ?? 'w-10 h-10 text-base';
@endphp
<section class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6 px-1">
        <span class="inline-block w-2 h-6 bg-gradient-to-b from-amber-400 via-orange-500 to-rose-500 rounded-full shadow-sm shadow-orange-500/50"></span>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-600 bg-orange-50/80 px-3 py-1.5 rounded-full ml-auto ring-1 ring-orange-500/10 shadow-sm">List</span>
    </div>
    
    <div class="flex flex-col gap-3">
        @forelse($items as $i => $item)
            <div class="group relative flex items-center gap-5 {{ $padding }} bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm ring-1 ring-slate-900/5 hover:bg-white hover:shadow-xl hover:shadow-orange-500/10 hover:ring-orange-500/20 transition-all duration-300 transform hover:-translate-y-1 hover:translate-x-1 cursor-default overflow-hidden">
                {{-- Hover gradient background sweep --}}
                <div class="absolute inset-0 bg-gradient-to-r from-orange-50/50 to-transparent translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500 ease-out"></div>
                
                <div class="relative z-10 flex-shrink-0 {{ $badgeW }} rounded-xl bg-gradient-to-br from-amber-100 to-orange-100 ring-1 ring-orange-200 text-orange-700 font-black flex items-center justify-center group-hover:from-amber-400 group-hover:to-orange-500 group-hover:text-white group-hover:shadow-lg group-hover:shadow-orange-500/40 group-hover:ring-orange-400 transition-all duration-300 group-hover:scale-110 group-hover:-rotate-3">
                    {{ $i + 1 }}
                </div>
                
                <span class="relative z-10 text-slate-600 {{ $size === 'lg' ? 'text-lg' : 'text-[15px]' }} font-semibold group-hover:text-slate-900 transition-colors duration-300 pr-4">
                    {{ $item }}
                </span>
                
                {{-- Arrow indicator --}}
                <div class="relative z-10 ml-auto opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300 text-orange-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                <span class="text-slate-400 text-sm font-medium">No items configured.</span>
            </div>
        @endforelse
    </div>
</section>
