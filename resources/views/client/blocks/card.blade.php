
@php
    $cards     = $config['cards'] ?? (!empty($config['title']) ? [$config] : []);
    $size      = $config['size'] ?? 'md';
    $imgH      = ['sm' => 'h-36', 'md' => 'h-52', 'lg' => 'h-72'][$size] ?? 'h-52';
    $padding   = ['sm' => 'p-4',  'md' => 'p-6',  'lg' => 'p-8' ][$size] ?? 'p-6';
    $titleSize = ['sm' => 'text-lg', 'md' => 'text-xl', 'lg' => 'text-3xl'][$size] ?? 'text-xl';
@endphp

<!-- <section class="group w-full flex flex-col rounded-2xl ring-1 ring-slate-900/5 bg-white/40 shadow-sm overflow-hidden h-[34rem]"> -->

    {{-- Fixed header row --}}
    <div class="flex-shrink-0 flex items-center gap-4 px-6 py-4 border-b border-slate-900/5 bg-white/70 backdrop-blur-xl">
        <span class="inline-block w-2 h-6 bg-gradient-to-b from-indigo-500 via-purple-500 to-pink-500 rounded-full shadow-sm shadow-purple-500/50"></span>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50/80 px-3 py-1 rounded-full ml-auto ring-1 ring-indigo-500/10 shadow-sm">Card</span>
    </div>

    {{-- Scrollable grid body — only this scrolls, the page does not --}}
    <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-6">
        <div class="flex flex-wrap gap-6">
            @forelse($cards as $card)
                <div class="basis-full sm:basis-[calc(50%-0.75rem)] lg:basis-[calc(33.333%-1rem)] flex flex-col bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 overflow-hidden hover:shadow-lg hover:shadow-slate-900/10 hover:-translate-y-1 transition-all duration-300">

                    @if(!empty($card['image_url']))
                        <div class="relative overflow-hidden {{ $imgH }} flex-shrink-0">
                            <img src="{{ $card['image_url'] }}"
                                 alt="{{ $card['title'] ?? '' }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
                        </div>
                    @else
                        <div class="w-full {{ $imgH }} flex-shrink-0 relative overflow-hidden bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif


                    <div class="flex flex-col flex-1 {{ $padding }}">
                        @if(!empty($card['title']))
                            <h3 class="font-bold text-slate-900 {{ $titleSize }} mb-2 tracking-tight leading-snug">{{ $card['title'] }}</h3>
                        @endif
                        @if(!empty($card['description']))
                            <p class="text-slate-500 text-sm leading-relaxed">{{ $card['description'] }}</p>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-sm font-semibold text-indigo-600 group-hover:gap-3 transition-all duration-300">
                            Read more
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </div>
            @empty
                <div class="basis-full text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 py-12">
                    <span class="text-slate-400 text-sm font-medium">No cards configured.</span>
                </div>
            @endforelse
        </div>
    </div>
<!-- </section> -->
