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
<section class="relative w-full rounded-2xl overflow-hidden shadow-sm ring-1 ring-slate-900/5 group">
    <a href="{{ $config['link'] ?? '#' }}" target="_blank" rel="noopener" class="block relative w-full h-full">
        <img src="{{ $config['image_url'] ?? 'https://placehold.co/1280x400/6366f1/ffffff?text=Banner' }}"
             alt="{{ $title }}"
             class="relative z-0 w-full {{ $imgHeight }} object-cover transition-transform duration-700 group-hover:scale-105">

        <div class="absolute inset-0 z-10 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 z-20 p-8">
            <h2 class="text-white {{ $size === 'lg' ? 'text-4xl' : 'text-2xl' }} font-bold tracking-tight mb-2">{{ $title }}</h2>
            <span class="inline-flex items-center gap-2 text-white/80 text-sm font-semibold group-hover:text-white transition-colors duration-200">
                Explore now
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </span>
        </div>

        <div class="absolute top-4 right-4 z-20">
            <span class="bg-white/15 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-md ring-1 ring-white/20 uppercase tracking-wider">Featured</span>
        </div>
    </a>
</section>
