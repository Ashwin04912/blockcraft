
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
        'dark'     => 'bg-white text-slate-900 hover:bg-slate-100 shadow-sm',
        'gradient' => 'bg-white text-indigo-700 hover:bg-white/90 shadow-sm',
        default    => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm',
    };
    $dotCss = match($bgStyle) {
        'dark', 'gradient' => 'bg-white/10 ring-1 ring-white/20 backdrop-blur-md shadow-inner',
        default            => 'bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600 shadow-lg shadow-indigo-500/40 ring-1 ring-white/50',
    };
@endphp
<header
    id="site-header"
    class="sticky top-0 z-50 w-full {{ $wrapCss }} transition-all duration-500 border-b shadow-sm bg-transparent border-slate-200/20"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-6">
 
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0 group">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $logoText }}" class="h-9 w-auto object-contain transition-transform duration-500 group-hover:scale-105">
            @else
                <div class="w-10 h-10 rounded-xl {{ $dotCss }} flex items-center justify-center flex-shrink-0 transition-transform duration-500 group-hover:rotate-6 group-hover:scale-110">
                    <span class="text-white font-black text-sm leading-none drop-shadow-md">{{ strtoupper(mb_substr($logoText, 0, 1)) }}</span>
                </div>
            @endif
            <span class="font-extrabold text-xl {{ $logoCss }} tracking-tight transition-all duration-300">{{ $logoText }}</span>
        </a>
 
        {{-- Nav links (desktop) --}}
        @if(!empty($navLinks))
            <nav class="hidden lg:flex items-center gap-6 min-w-0 overflow-x-auto whitespace-nowrap [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach($navLinks as $link)
                    @php
                        $url = $link['url'] ?? '#';
                        $active = $url !== '#' && request()->is(trim($url, '/') ?: '/');
                    @endphp
                    <a href="{{ $url }}"
                       class="relative flex-shrink-0 text-[15px] font-semibold {{ $active ? $logoCss : $linkCss }} transition-colors duration-300 group">
                        {{ $link['label'] ?? '' }}
                        <span class="absolute -bottom-1.5 left-0 h-0.5 bg-current rounded-full transition-all duration-300 {{ $active ? 'w-full opacity-70' : 'w-0 opacity-50 group-hover:w-full' }}"></span>
                    </a>
                @endforeach
            </nav>
        @endif
 
        {{-- CTA (md and up) + mobile toggle (below md) --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            @if($ctaLabel)
                <a href="{{ $ctaUrl }}"
                   class="hidden md:inline-flex text-sm font-bold px-6 py-3 rounded-xl transition-all duration-200 {{ $ctaCss }} hover:-translate-y-0.5 active:translate-y-0 items-center gap-2">
                    {{ $ctaLabel }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            @endif
 
            @if(!empty($navLinks))
                <button
                    type="button"
                    id="mobile-menu-toggle"
                    class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg {{ $linkCss }} focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-current"
                    aria-label="Toggle menu"
                    aria-expanded="false"
                >
                    <svg class="menu-icon-open h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="menu-icon-close h-6 w-6 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    </div>
 
    {{-- Mobile menu panel --}}
    @if(!empty($navLinks))
        <div id="mobile-menu-panel" class="hidden lg:hidden border-t border-slate-200/20 bg-white/95 backdrop-blur-xl">
            <nav class="px-4 sm:px-6 py-4 flex flex-col gap-1">
                @foreach($navLinks as $link)
                    <a href="{{ $link['url'] ?? '#' }}"
                       class="text-base font-semibold {{ $linkCss }} px-3 py-3 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                        {{ $link['label'] ?? '' }}
                    </a>
                @endforeach
                @if($ctaLabel)
                    <a href="{{ $ctaUrl }}"
                       class="mt-2 text-sm font-bold px-6 py-3 rounded-xl text-center {{ $ctaCss }}">
                        {{ $ctaLabel }}
                    </a>
                @endif
            </nav>
        </div>
    @endif
</header>

<script>
(function () {
    var header = document.getElementById('site-header');
    var toggle = document.getElementById('mobile-menu-toggle');
    var panel = document.getElementById('mobile-menu-panel');

    if (toggle && panel) {
        var iconOpen = toggle.querySelector('.menu-icon-open');
        var iconClose = toggle.querySelector('.menu-icon-close');

        var closeMenu = function () {
            panel.classList.add('hidden');
            toggle.setAttribute('aria-expanded', 'false');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        };

        toggle.addEventListener('click', function () {
            var isOpen = !panel.classList.contains('hidden');
            if (isOpen) {
                closeMenu();
            } else {
                panel.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
            }
        });

        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });
    }

    if (header) {
        var onScroll = function () {
            if (window.scrollY > 12) {
                header.classList.add('shadow-md', 'backdrop-blur-xl');
                header.classList.add('bg-white/80');
                header.classList.add('border-slate-200/40');
                header.classList.remove('shadow-sm', 'bg-transparent', 'border-slate-200/20');
            } else {
                header.classList.remove('shadow-md', 'backdrop-blur-xl', 'bg-white/80', 'border-slate-200/40');
                header.classList.add('shadow-sm', 'bg-transparent', 'border-slate-200/20');
            }
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }
})();
</script>