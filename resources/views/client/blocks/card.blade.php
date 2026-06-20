{{--
    Card block partial
    Variables: $title (string), $config (array)
    Config keys: cards [{title, description, image_url}], size (sm|md|lg), bg_style (light|dark|gradient)
--}}
@php
    $cards     = $config['cards'] ?? (!empty($config['title']) ? [$config] : []);
    $size      = $config['size'] ?? 'md';
    // Mapping sizes to custom classes we can add to app.scss or inline
    $imgH      = ['sm' => 'custom-img-h-sm', 'md' => 'custom-img-h-md', 'lg' => 'custom-img-h-lg'][$size] ?? 'custom-img-h-md';
    $padding   = ['sm' => 'p-3',  'md' => 'p-4',  'lg' => 'p-5' ][$size] ?? 'p-4';
    $titleSize = ['sm' => 'fs-5', 'md' => 'fs-4', 'lg' => 'fs-3'][$size] ?? 'fs-4';

    $bgStyle = $config['bg_style'] ?? 'light';

    $panelClass = match($bgStyle) {
        'dark'     => 'bg-dark',
        'gradient' => 'bg-primary bg-gradient',
        default    => '',
    };
    $headingClass = $bgStyle === 'light' ? 'text-dark' : 'text-white';
    $cardClass    = $bgStyle === 'light' ? '' : 'text-bg-dark';
    $descClass    = $bgStyle === 'light' ? 'text-secondary' : 'text-white-50';
    $iconWrapClass = $bgStyle === 'light' ? 'bg-primary bg-opacity-10' : 'bg-white bg-opacity-10';
    $iconClass     = $bgStyle === 'light' ? 'text-primary opacity-50' : 'text-white opacity-50';
@endphp

<div class="{{ $panelClass }} {{ $panelClass ? 'rounded-4 p-4' : '' }}">
    {{-- Fixed header row --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="d-inline-block rounded-pill shadow-sm bg-primary" style="width: 8px; height: 24px;"></span>
        <h2 class="fs-4 fw-bold {{ $headingClass }} m-0 tracking-tight">{{ $title }}</h2>
    </div>

    {{-- Scrollable grid body --}}
    <div class="flex-grow-1 min-vh-0  px-1 py-3" >
        <div class="row g-4">
            @forelse($cards as $card)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition group {{ $cardClass }}">
                        @if(!empty($card['image_url']))
                            <div class="position-relative overflow-hidden {{ $imgH }}">
                                <img src="{{ $card['image_url'] }}"
                                     alt="{{ $card['title'] ?? '' }}"
                                     class="w-100 h-100 object-fit-cover transition group-hover-scale">
                            </div>
                        @else
                            <div class="w-100 {{ $imgH }} position-relative overflow-hidden {{ $iconWrapClass }} d-flex align-items-center justify-content-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" class="{{ $iconClass }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column {{ $padding }}">
                            @if(!empty($card['title']))
                                <h3 class="fw-bold {{ $bgStyle === 'light' ? 'text-dark' : 'text-white' }} {{ $titleSize }} mb-2 tracking-tight">{{ $card['title'] }}</h3>
                            @endif
                            @if(!empty($card['description']))
                                <p class="{{ $descClass }} small mb-0">{{ $card['description'] }}</p>
                            @endif

                            <div class="mt-auto pt-3 border-top mt-3 d-flex align-items-center gap-2 small fw-semibold text-primary transition group-hover-gap">
                                Read more
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-light rounded-4 border border-secondary border-dashed border-opacity-25">
                    <span class="text-secondary small fw-medium">No cards configured.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .custom-img-h-sm { height: 9rem; }
    .custom-img-h-md { height: 13rem; }
    .custom-img-h-lg { height: 18rem; }
    .group:hover .group-hover-scale { transform: scale(1.05); }
    .group-hover-scale { transition: transform 0.5s ease-out; }
    .group:hover .group-hover-gap { gap: 0.75rem !important; }
</style>
