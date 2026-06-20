@php
$bgStyle  = $config['bg_style'] ?? 'light';
$logoText = $config['logo_text'] ?? $title;
$logoUrl  = $config['logo_url'] ?? '';
$navLinks = $config['nav_links'] ?? [];
$ctaLabel = $config['cta_label'] ?? '';
$ctaUrl   = $config['cta_url'] ?? '#';


// Map styles to Bootstrap classes
$navbarClass = match($bgStyle) {
    'dark'     => 'navbar-dark bg-dark',
    'gradient' => 'navbar-dark bg-primary',
    default    => 'navbar-light bg-light',
};

$btnClass = match($bgStyle) {
    'dark'     => 'btn btn-light',
    'gradient' => 'btn btn-light text-primary',
    default    => 'btn btn-primary',
};


@endphp

<nav id="site-header" class="navbar navbar-expand-lg {{ $navbarClass }} sticky-top shadow-sm">
    <div class="container">


    {{-- Logo --}}
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $logoText }}" height="40">
        @endif
        @if($logoText)
            <span class="fw-bold">{{ $logoText }}</span>
        @endif
    </a>

    {{-- Mobile Toggle --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    {{-- Nav Links --}}
    <div class="collapse navbar-collapse" id="navbarContent">
        @if(!empty($navLinks))
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                @foreach($navLinks as $link)
                    @php
                        $url = $link['url'] ?? '#';
                        $active = $url !== '#' && request()->is(trim($url, '/') ?: '/');
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $active ? 'active fw-semibold' : '' }}"
                           href="{{ $url }}">
                            {{ $link['label'] ?? '' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- CTA --}}
        @if($ctaLabel)
            <div class="d-flex">
                <a href="{{ $ctaUrl }}" class="{{ $btnClass }}">
                    {{ $ctaLabel }}
                </a>
            </div>
        @endif
    </div>
</div>


</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('site-header');

    const onScroll = () => {
        if (window.scrollY > 10) {
            header.classList.add('shadow');
        } else {
            header.classList.remove('shadow');
        }
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
});
</script>
