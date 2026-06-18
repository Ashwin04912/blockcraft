{{--
    Banner block partial
    Variables: $title (string), $config (array)
    Config keys: image_url, link, size (sm|md|lg)
--}}
@php
    $size       = $config['size'] ?? 'md';
    $heightMap  = ['sm' => 'h-40 sm:h-56', 'md' => 'h-64 sm:h-80', 'lg' => 'h-80 sm:h-[28rem]'];
    $imgHeight  = $heightMap[$size] ?? $heightMap['md'];
@endphp
<section class="relative w-full rounded-[2rem] overflow-hidden shadow-2xl shadow-indigo-900/20 ring-1 ring-slate-900/5 group transform-gpu">
    <a href="{{ $config['link'] ?? '#' }}" target="_blank" rel="noopener" class="block relative w-full h-full">
        <div class="absolute inset-0 bg-slate-900 animate-pulse" style="animation-duration: 2s; animation-delay: 0.5s;"></div>
        <img src="{{ $config['image_url'] ?? 'https://placehold.co/1280x400/6366f1/ffffff?text=Banner' }}"
             alt="{{ $title }}"
             class="relative z-0 w-full {{ $imgHeight }} object-cover transition-transform duration-1000 group-hover:scale-110">
        
        {{-- Overlays --}}
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-slate-950/90 via-slate-900/40 to-transparent transition-opacity duration-500 group-hover:opacity-90"></div>
        <div class="absolute inset-0 z-10 bg-gradient-to-r from-indigo-900/30 to-transparent opacity-50 mix-blend-overlay"></div>
        <div class="absolute inset-0 z-10 ring-1 ring-inset ring-white/10 rounded-[2rem]"></div>

        <div class="absolute bottom-0 left-0 right-0 p-8 sm:p-12 z-20 translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
            <h2 class="text-white {{ $size === 'lg' ? 'text-5xl' : 'text-3xl' }} font-black tracking-tight drop-shadow-2xl mb-3">{{ $title }}</h2>
            <span class="inline-flex items-center gap-2 text-white/80 text-[15px] font-semibold group-hover:text-white transition-colors duration-300">
                Explore now
                <span class="bg-white/20 p-1.5 rounded-full backdrop-blur-sm group-hover:bg-white group-hover:text-indigo-600 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </span>
            </span>
        </div>
        
        {{-- Tag --}}
        <div class="absolute top-6 right-6 z-20 opacity-0 -translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500">
            <span class="bg-white/10 text-white text-xs font-bold px-4 py-2 rounded-full backdrop-blur-xl ring-1 ring-white/20 shadow-lg uppercase tracking-wider">Featured</span>
        </div>
    </a>
</section>
