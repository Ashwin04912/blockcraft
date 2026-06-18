{{--
    Footer block partial
    Variables: $title (string), $config (array)
    Config keys: brand, tagline, copyright, links [{label,url}], social_links [{platform,url}], size
--}}
@php
    $brand       = $config['brand'] ?? $title;
    $tagline     = $config['tagline'] ?? '';
    $copyright   = $config['copyright'] ?? '© ' . date('Y') . ' ' . $brand . '. All rights reserved.';
    $links       = $config['links'] ?? [];
    $socialLinks = $config['social_links'] ?? [];
    $size        = $config['size'] ?? 'md';

    $paddingCss = match($size) {
        'sm'    => 'px-8 py-10',
        'lg'    => 'px-12 py-20',
        default => 'px-10 py-16',
    };
@endphp
<footer class="relative rounded-[2.5rem] overflow-hidden bg-slate-950 text-slate-400 ring-1 ring-white/10 shadow-2xl mt-8">
    
    {{-- Ambient Background Glow --}}
    <div class="absolute top-0 left-1/4 w-[50%] h-32 bg-indigo-500/20 rounded-full blur-[100px]"></div>
    
    <div class="{{ $paddingCss }} max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row gap-12 md:gap-20 mb-12 md:mb-16">

            {{-- Brand column --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-6 group cursor-pointer w-max">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-600 to-purple-600 flex items-center justify-center flex-shrink-0 shadow-[0_0_20px_rgba(79,70,229,0.4)] group-hover:shadow-[0_0_30px_rgba(79,70,229,0.6)] group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <span class="text-white font-black text-xl leading-none drop-shadow-md">{{ strtoupper(mb_substr($brand, 0, 1)) }}</span>
                    </div>
                    <span class="text-white font-black text-2xl tracking-tighter bg-gradient-to-r from-white to-white/70 bg-clip-text text-transparent group-hover:to-white transition-colors duration-300">{{ $brand }}</span>
                </div>

                @if($tagline)
                    <p class="text-[15px] leading-relaxed max-w-sm text-slate-400/90 font-medium">{{ $tagline }}</p>
                @endif

                {{-- Social links --}}
                @if(!empty($socialLinks))
                    <div class="flex flex-wrap gap-3 mt-8">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social['url'] ?? '#' }}"
                               class="group/social inline-flex items-center text-sm font-bold bg-white/[0.04] hover:bg-white/10 ring-1 ring-white/10 hover:ring-white/30 text-slate-300 hover:text-white px-4 py-2 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-white/5"
                               target="_blank" rel="noopener noreferrer">
                                {{ $social['platform'] ?? '' }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick links --}}
            @if(!empty($links))
                <div class="flex-shrink-0 md:min-w-[200px]">
                    <h4 class="text-white font-black mb-6 uppercase tracking-[0.2em] text-xs">Quick Links</h4>
                    <ul class="space-y-4">
                        @foreach($links as $link)
                            <li>
                                <a href="{{ $link['url'] ?? '#' }}"
                                   class="text-[15px] font-semibold text-slate-400 hover:text-white transition-all duration-300 flex items-center gap-2 group/link">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500/50 group-hover/link:bg-indigo-400 group-hover/link:scale-150 transition-all duration-300"></span>
                                    <span class="group-hover/link:translate-x-1 transition-transform duration-300">{{ $link['label'] ?? '' }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Bottom copyright bar --}}
        <div class="border-t border-white/[0.06] pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm font-medium text-slate-500">{{ $copyright }}</p>
            <p class="text-sm font-medium text-slate-500 flex items-center gap-1.5">
                Powered by 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400 font-bold tracking-tight">BlockCraft</span>
            </p>
        </div>
    </div>
</footer>
