{{--
    Banner block partial
    Variables: $title (string), $config (array)
    Config keys: image_url, link, size (sm|md|lg)
--}}
@php
    $size       = $config['size'] ?? 'md';
    $imgHeight  = ['sm' => 'banner-h-sm', 'md' => 'banner-h-md', 'lg' => 'banner-h-lg'][$size] ?? 'banner-h-md';
@endphp
<section class="position-relative w-100 rounded-4 overflow-hidden shadow-sm group">
    <a href="{{ $config['link'] ?? '#' }}" target="_blank" rel="noopener" class="d-block position-relative w-100 h-100 text-decoration-none">
        <img src="{{ $config['image_url'] ?? 'https://placehold.co/1280x400/6366f1/ffffff?text=Banner' }}"
             alt="{{ $title }}"
             class="position-relative z-0 w-100 {{ $imgHeight }} object-fit-cover transition-transform duration-700 group-hover-scale">

        <div class="position-absolute top-0 start-0 w-100 h-100 z-1" style="background: linear-gradient(to top, rgba(2, 6, 23, 0.85), rgba(2, 6, 23, 0.2), transparent);"></div>

        <div class="position-absolute bottom-0 start-0 w-100 z-2 p-4 p-md-5">
            <h2 class="text-white {{ $size === 'lg' ? 'fs-1' : 'fs-3' }} fw-bold tracking-tight mb-2">{{ $title }}</h2>
            <span class="d-inline-flex align-items-center gap-2 text-white text-opacity-75 small fw-semibold transition group-hover-text-white">
                Explore now
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="transition-transform group-hover-translate-x" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </span>
        </div>
    </a>
</section>

<style>
    .banner-h-sm { height: 10rem; }
    @media (min-width: 576px) { .banner-h-sm { height: 14rem; } }
    .banner-h-md { height: 16rem; }
    @media (min-width: 576px) { .banner-h-md { height: 20rem; } }
    .banner-h-lg { height: 20rem; }
    @media (min-width: 576px) { .banner-h-lg { height: 28rem; } }
    .transition-transform { transition: transform 0.7s ease; }
    .group:hover .group-hover-scale { transform: scale(1.05); }
    .group:hover .group-hover-text-white { color: white !important; }
    .group:hover .group-hover-translate-x { transform: translateX(4px); }
</style>
