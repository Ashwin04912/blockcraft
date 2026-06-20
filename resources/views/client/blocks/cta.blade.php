{{--
    CTA block partial
    Variables: $title (string), $config (array)
    Config keys: heading, subheading, button_text, button_link, style (primary|secondary)
--}}
@php
    $style = $config['style'] ?? 'primary';
    $wrapCss = match($style) {
        'secondary' => 'bg-white ring-1 ring-slate-900/5',
        default     => 'bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600',
    };
    $headingCss = $style === 'secondary' ? 'text-slate-900' : 'text-white';
    $subCss     = $style === 'secondary' ? 'text-slate-500' : 'text-white/80';
    $btnCss     = $style === 'secondary'
        ? 'bg-slate-900 text-white hover:bg-indigo-600'
        : 'bg-white text-indigo-700 hover:bg-white/90';
@endphp
<section class="rounded-2xl overflow-hidden shadow-xl shadow-indigo-900/10 {{ $wrapCss }}">
    <div class="px-8 py-12 text-center max-w-2xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight {{ $headingCss }}">{{ $config['heading'] ?? $title }}</h2>
        @if(!empty($config['subheading']))
            <p class="mt-2 text-base {{ $subCss }}">{{ $config['subheading'] }}</p>
        @endif
        @if(!empty($config['button_text']))
            <a href="{{ $config['button_link'] ?? '#' }}"
               class="inline-flex items-center gap-2 mt-6 text-sm font-semibold px-6 py-3 rounded-xl transition-all duration-150 shadow-sm hover:shadow-md hover:-translate-y-0.5 {{ $btnCss }}">
                {{ $config['button_text'] }}
            </a>
        @endif
    </div>
</section>
