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
<footer class="relative w-full overflow-hidden bg-slate-950 text-slate-400 border-t border-slate-800 mt-16">
    <div class="{{ $paddingCss }} max-w-7xl mx-auto relative z-10">
        <div class="flex flex-col md:flex-row gap-12 md:gap-20 mb-12 md:mb-16">

            {{-- Brand column --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-4 mb-6 w-max">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xl leading-none">{{ strtoupper(mb_substr($brand, 0, 1)) }}</span>
                    </div>
                    <span class="text-white font-bold text-2xl tracking-tight">{{ $brand }}</span>
                </div>

                @if($tagline)
                    <p class="text-sm leading-relaxed max-w-sm text-slate-400">{{ $tagline }}</p>
                @endif

                {{-- Social links --}}
                @if(!empty($socialLinks))
                    <div class="flex flex-wrap gap-2 mt-6">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social['url'] ?? '#' }}"
                               class="inline-flex items-center text-sm font-semibold bg-white/5 hover:bg-white/10 ring-1 ring-white/10 text-slate-300 hover:text-white px-4 py-2 rounded-xl transition-colors duration-150"
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
                    <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Quick Links</h4>
                    <ul class="space-y-3">
                        @foreach($links as $link)
                            <li>
                                <a href="{{ $link['url'] ?? '#' }}"
                                   class="text-sm font-medium text-slate-400 hover:text-white transition-colors duration-150">
                                    {{ $link['label'] ?? '' }}
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
            <p class="text-sm font-medium text-slate-500">
                Powered by <span class="text-indigo-400 font-bold">BlockCraft</span>
            </p>
        </div>
    </div>
</footer>
