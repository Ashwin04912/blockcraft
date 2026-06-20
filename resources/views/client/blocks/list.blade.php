{{--
    List block partial
    Variables: $title (string), $config (array)
    Config keys: items (array of strings), size (sm|md|lg), layout (vertical|horizontal)
--}}
@php
    $items   = $config['items'] ?? [];
    $size    = $config['size'] ?? 'md';
    $layout  = $config['layout'] ?? 'vertical';
    $padding = ['sm' => 'px-4 py-3', 'md' => 'px-6 py-4', 'lg' => 'px-8 py-4'][$size] ?? 'px-6 py-4';
    $badgeW  = ['sm' => 'w-8 h-8 text-sm', 'md' => 'w-10 h-10 text-base', 'lg' => 'w-10 h-10 text-base'][$size] ?? 'w-10 h-10 text-base';

    $wrapperClass = $layout === 'horizontal'
        ? 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'
        : 'flex flex-col gap-4';
@endphp
<section class="w-full">
    <div class="flex items-center gap-4 mb-6">
        <span class="inline-block w-2 h-6 bg-amber-500 rounded-full"></span>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-3 py-1 rounded-full ml-auto ring-1 ring-amber-500/10">List</span>
    </div>

    <div class="{{ $wrapperClass }}">
        @forelse($items as $i => $item)
            <div class="group flex items-center gap-4 {{ $padding }} bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 hover:shadow-lg hover:shadow-slate-900/10 hover:-translate-y-0.5 transition-all duration-200">
                <span class="flex-shrink-0 {{ $badgeW }} rounded-xl bg-amber-50 text-amber-700 font-bold flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-200">
                    {{ $i + 1 }}
                </span>
                <span class="text-slate-600 {{ $size === 'lg' ? 'text-base' : 'text-sm' }} font-medium group-hover:text-slate-900 transition-colors duration-200">
                    {{ $item }}
                </span>
            </div>
        @empty
            <div class="px-6 py-12 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                <span class="text-slate-400 text-sm font-medium">No items configured.</span>
            </div>
        @endforelse
    </div>
</section>
