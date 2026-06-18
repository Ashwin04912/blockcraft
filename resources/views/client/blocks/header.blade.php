{{--
    Header block partial
    Variables: $title (string), $config (array)
    Config keys: logo_text, logo_url, nav_links [{label,url}], cta_label, cta_url, bg_style (light|dark|gradient), size
--}}
@php
    $bgStyle  = $config['bg_style'] ?? 'light';
    $logoText = $config['logo_text'] ?? $title;
    $logoUrl  = $config['logo_url'] ?? '';
    $navLinks = $config['nav_links'] ?? [];
    $ctaLabel = $config['cta_label'] ?? '';
    $ctaUrl   = $config['cta_url'] ?? '#';

    $wrapCss = match($bgStyle) {
        'dark'     => 'bg-slate-950/80 backdrop-blur-2xl ring-1 ring-white/10 shadow-2xl shadow-slate-950/40',
        'gradient' => 'bg-gradient-to-r from-violet-600/90 via-indigo-600/90 to-blue-600/90 backdrop-blur-2xl ring-1 ring-white/20 shadow-2xl shadow-indigo-900/30',
        default    => 'bg-white/70 backdrop-blur-2xl ring-1 ring-slate-900/5 shadow-xl shadow-slate-200/50',
    };
    $linkCss = match($bgStyle) {
        'dark', 'gradient' => 'text-white/70 hover:text-white',
        default            => 'text-slate-500 hover:text-slate-900',
    };
    $logoCss = match($bgStyle) {
        'dark', 'gradient' => 'text-white',
        default            => 'bg-gradient-to-br from-slate-800 to-slate-900 bg-clip-text text-transparent',
    };
    $ctaCss = match($bgStyle) {
        'dark'     => 'bg-white text-slate-900 hover:bg-slate-100 ring-1 ring-white/50 hover:ring-white shadow-[0_0_15px_rgba(255,255,255,0.3)]',
        'gradient' => 'bg-white text-indigo-700 hover:bg-white/90 ring-1 ring-white/50 shadow-[0_0_15px_rgba(255,255,255,0.4)]',
        default    => 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white hover:from-indigo-500 hover:to-violet-500 shadow-lg shadow-indigo-500/30 ring-1 ring-indigo-500/50',
    };
    $dotCss = match($bgStyle) {
        'dark', 'gradient' => 'bg-white/10 ring-1 ring-white/20 backdrop-blur-md shadow-inner',
        default            => 'bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600 shadow-lg shadow-indigo-500/40 ring-1 ring-white/50',
    };
@endphp
<header class="relative z-50">
    <div class="rounded-[2rem] overflow-hidden {{ $wrapCss }} transition-all duration-500 hover:shadow-2xl">
        <div class="px-8 py-5 flex items-center justify-between gap-6 flex-wrap">

            {{-- Logo --}}
            <div class="flex items-center gap-3.5 flex-shrink-0 group cursor-pointer">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $logoText }}" class="h-9 w-auto object-contain transition-transform duration-500 group-hover:scale-105">
                @else
                    <div class="w-10 h-10 rounded-xl {{ $dotCss }} flex items-center justify-center flex-shrink-0 transition-transform duration-500 group-hover:rotate-6 group-hover:scale-110">
                        <span class="text-white font-black text-sm leading-none drop-shadow-md">{{ strtoupper(mb_substr($logoText, 0, 1)) }}</span>
                    </div>
                @endif
                <span class="font-extrabold text-xl {{ $logoCss }} tracking-tight transition-all duration-300">{{ $logoText }}</span>
            </div>

            {{-- Nav links --}}
            @if(!empty($navLinks))
                <nav class="hidden md:flex items-center gap-8 flex-wrap">
                    @foreach($navLinks as $link)
                        <a href="{{ $link['url'] ?? '#' }}"
                           class="relative text-[15px] font-semibold {{ $linkCss }} transition-colors duration-300 group">
                            {{ $link['label'] ?? '' }}
                            <span class="absolute -bottom-1.5 left-0 w-0 h-0.5 bg-current transition-all duration-300 group-hover:w-full rounded-full opacity-50"></span>
                        </a>
                    @endforeach
                </nav>
            @endif

            {{-- CTA button --}}
            @if($ctaLabel)
                <a href="{{ $ctaUrl }}"
                   class="flex-shrink-0 text-[15px] font-bold px-6 py-2.5 rounded-xl transition-all duration-300 {{ $ctaCss }} hover:-translate-y-1 active:translate-y-0 flex items-center gap-2">
                    {{ $ctaLabel }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
</header>
