{{--
    Stats block partial
    Variables: $title (string), $config (array)
    Config keys: stats (array of { label, value, icon }), size (sm|md|lg), bg_style (light|dark|gradient)
--}}
@php
    $stats     = $config['stats'] ?? [];
    $size      = $config['size'] ?? 'md';
    $padding   = ['sm' => 'p-3',  'md' => 'p-4',  'lg' => 'p-5' ][$size] ?? 'p-4';
    $valSize   = ['sm' => 'fs-4', 'md' => 'fs-3', 'lg' => 'fs-2'][$size] ?? 'fs-3';

    $bgStyle = $config['bg_style'] ?? 'light';

    $panelClass = match($bgStyle) {
        'dark'     => 'bg-dark',
        'gradient' => 'bg-primary bg-gradient',
        default    => '',
    };
    $headingClass  = $bgStyle === 'light' ? 'text-dark' : 'text-white';
    $cardClass     = $bgStyle === 'light' ? '' : 'text-bg-dark';
    $labelClass    = $bgStyle === 'light' ? 'text-secondary' : 'text-white-50';
    $valueClass    = $bgStyle === 'light' ? 'text-success' : 'text-warning';
    $iconClass     = $bgStyle === 'light' ? 'text-success' : 'text-warning';
@endphp
<section class="w-100 {{ $panelClass }} {{ $panelClass ? 'rounded-4 p-4' : '' }}">
    <div class="d-flex align-items-center gap-3 mb-4">
        <span class="d-inline-block rounded-pill bg-success" style="width: 8px; height: 24px;"></span>
        <h2 class="fs-4 fw-bold {{ $headingClass }} m-0 tracking-tight">{{ $title }}</h2>
    </div>

    <div class="row row-cols-2 row-cols-lg-4 g-3">
        @forelse($stats as $stat)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm rounded-4 text-center transition group-hover-translate-y {{ $padding }} {{ $cardClass }}">
                    <div class="card-body p-0">
                        @if(!empty($stat['icon']))
                            <div class="d-flex justify-content-center mb-2 {{ $iconClass }}">
                                @if($stat['icon'] === 'users')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                @elseif($stat['icon'] === 'chart')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                @elseif($stat['icon'] === 'star')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                @elseif($stat['icon'] === 'clock')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </div>
                        @endif

                        <div class="{{ $valSize }} fw-bold {{ $valueClass }} tracking-tight">
                            {{ $stat['value'] ?? '—' }}
                        </div>
                        <div class="small {{ $labelClass }} fw-semibold mt-2 text-uppercase" style="letter-spacing: 0.05em;">
                            {{ $stat['label'] ?? '' }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center bg-light rounded-4 border border-secondary border-dashed border-opacity-25 py-5">
                <span class="text-secondary small fw-medium">No stats configured.</span>
            </div>
        @endforelse
    </div>
</section>

<style>
    .group-hover-translate-y { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .group-hover-translate-y:hover { transform: translateY(-4px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important; }
</style>
