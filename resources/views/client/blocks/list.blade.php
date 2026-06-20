@php
    $items   = $config['items'] ?? [];
    $size    = $config['size'] ?? 'md';
    $layout  = $config['layout'] ?? 'vertical';
    $padding = ['sm' => 'px-3 py-2', 'md' => 'px-4 py-3', 'lg' => 'px-5 py-3'][$size] ?? 'px-4 py-3';
    $badgeW  = ['sm' => 'badge-w-sm', 'md' => 'badge-w-md', 'lg' => 'badge-w-md'][$size] ?? 'badge-w-md';
@endphp
<section class="w-100">
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="d-inline-block rounded-pill bg-warning" style="width: 8px; height: 24px;"></span>
        <h2 class="fs-4 fw-bold text-dark m-0 tracking-tight">{{ $title }}</h2>
        
    </div>

    @if($layout === 'horizontal')
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
    @else
        <div class="d-flex flex-column gap-3">
    @endif
    
        @forelse($items as $i => $item)
            @if($layout === 'horizontal') <div class="col"> @endif
            <div class="card border-0 shadow-sm rounded-4 transition group-hover-translate-y h-100 {{ $padding }} group">
                <div class="d-flex align-items-center gap-3 m-0">
                    <span class="d-flex align-items-center justify-content-center flex-shrink-0 {{ $badgeW }} rounded-3 bg-warning bg-opacity-10 text-dark fw-bold transition group-hover-bg-warning">
                        {{ $i + 1 }}
                    </span>
                    <span class="text-secondary {{ $size === 'lg' ? 'fs-6' : 'small' }} fw-medium transition group-hover-text-dark m-0">
                        {{ $item }}
                    </span>
                </div>
            </div>
            @if($layout === 'horizontal') </div> @endif
        @empty
            @if($layout === 'horizontal') <div class="col-12"> @endif
            <div class="px-4 py-5 text-center bg-light rounded-4 border border-secondary border-dashed border-opacity-25">
                <span class="text-secondary small fw-medium">No items configured.</span>
            </div>
            @if($layout === 'horizontal') </div> @endif
        @endforelse
        
    </div>
</section>

<style>
    .badge-w-sm { width: 2rem; height: 2rem; font-size: 0.875rem; }
    .badge-w-md { width: 2.5rem; height: 2.5rem; font-size: 1rem; }
    .group-hover-translate-y { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .group-hover-translate-y:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important; }
    .transition { transition: all 0.2s ease; }
    .group:hover .group-hover-bg-warning { background-color: var(--bs-warning) !important; color: #000 !important; }
    .group:hover .group-hover-text-dark { color: var(--bs-dark) !important; }
</style>
