@php
$brand       = $config['brand'] ?? $title;
$tagline     = $config['tagline'] ?? '';
$copyright   = $config['copyright'] ?? '© ' . date('Y') . ' ' . $brand . '. All rights reserved.';
$links       = $config['links'] ?? [];
$socialLinks = $config['social_links'] ?? [];
$size        = $config['size'] ?? 'md';

// Defaults to 'dark' (not 'light' like other blocks) — that's the look
// this footer has always had; switching the global default would silently
// change every footer already seeded without bg_style set.
$bgStyle = $config['bg_style'] ?? 'dark';

// Padding control
$paddingCss = match($size) {
    'sm'    => 'py-4',
    'lg'    => 'py-5',
    default => 'py-5',
};

$footerClass = match($bgStyle) {
    'light'    => 'bg-white text-secondary border-top',
    'gradient' => 'bg-primary bg-gradient text-white border-top',
    default    => 'bg-dark text-secondary border-top',
};
$brandTextClass = $bgStyle === 'light' ? 'text-dark' : 'text-white';
$linkClass      = $bgStyle === 'light' ? 'text-secondary' : 'text-secondary';
$socialBtnClass = $bgStyle === 'light' ? 'btn-outline-secondary' : 'btn-outline-light';
@endphp

<footer class="{{ $footerClass }} mt-5">
    <div class="container {{ $paddingCss }}">


    <!-- Top Section -->
    <div class="row mb-4">

        <!-- Brand Column -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded bg-primary text-white d-flex align-items-center justify-content-center me-3"
                     style="width:48px;height:48px;">
                    <strong>{{ strtoupper(mb_substr($brand, 0, 1)) }}</strong>
                </div>
                <h5 class="{{ $brandTextClass }} mb-0 fw-bold">{{ $brand }}</h5>
            </div>

            @if($tagline)
                <p class="small text-secondary mb-3">{{ $tagline }}</p>
            @endif

            <!-- Social Links -->
            @if(!empty($socialLinks))
                <div>
                    @foreach($socialLinks as $social)
                        <a href="{{ $social['url'] ?? '#' }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn {{ $socialBtnClass }} btn-sm me-2 mb-2">
                            {{ $social['platform'] ?? '' }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Links Column -->
        @if(!empty($links))
            <div class="col-md-3">
                <h6 class="{{ $brandTextClass }} text-uppercase fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    @foreach($links as $link)
                        <li class="mb-2">
                            <a href="{{ $link['url'] ?? '#' }}"
                               class="{{ $linkClass }} text-decoration-none">
                                {{ $link['label'] ?? '' }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    <!-- Bottom Section -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center border-top pt-3">
        <p class="small mb-2 mb-sm-0 text-secondary">
            {{ $copyright }}
        </p>

        <p class="small mb-0 text-secondary">
            Powered by <span class="text-primary fw-bold">BlockCraft</span>
        </p>
    </div>

</div>


</footer>
