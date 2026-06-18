{{--
    Card block partial
    Variables: $title (string), $config (array)
    Config keys: title, description, image_url, size (sm|md|lg)
--}}
@php
    $size      = $config['size'] ?? 'md';
    $imgH      = ['sm' => 'h-36', 'md' => 'h-52', 'lg' => 'h-72'][$size] ?? 'h-52';
    $padding   = ['sm' => 'p-5',  'md' => 'p-7',  'lg' => 'p-9' ][$size] ?? 'p-7';
    $titleSize = ['sm' => 'text-lg', 'md' => 'text-xl', 'lg' => 'text-3xl'][$size] ?? 'text-xl';
@endphp
<section class="group">
    <div class="flex items-center gap-3 mb-6 px-1">
        <span class="inline-block w-2 h-6 bg-gradient-to-b from-indigo-500 via-purple-500 to-pink-500 rounded-full shadow-sm shadow-purple-500/50"></span>
        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 bg-indigo-50/80 px-3 py-1.5 rounded-full ml-auto ring-1 ring-indigo-500/10 shadow-sm">Card</span>
    </div>
    
    <div class="relative bg-white/80 backdrop-blur-3xl rounded-[2rem] shadow-xl shadow-slate-200/50 ring-1 ring-slate-900/5 overflow-hidden hover:shadow-2xl hover:shadow-indigo-500/20 hover:-translate-y-2 transition-all duration-500">
        
        {{-- Background ambient glow --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-400/20 rounded-full blur-3xl group-hover:bg-purple-400/30 transition-colors duration-500"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl group-hover:bg-indigo-400/30 transition-colors duration-500"></div>
        
        @if(!empty($config['image_url']))
            <div class="relative overflow-hidden {{ $imgH }}">
                <img src="{{ $config['image_url'] }}"
                     alt="{{ $config['title'] ?? $title }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent mix-blend-multiply"></div>
            </div>
        @else
            <div class="w-full {{ $imgH }} relative overflow-hidden bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex items-center justify-center">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiM2MzY2ZjEiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-50 mix-blend-overlay"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-indigo-200/60 drop-shadow-sm group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif
        
        <div class="relative {{ $padding }} z-10 bg-gradient-to-b from-transparent to-white/90">
            @if(!empty($config['title']))
                <h3 class="font-extrabold text-slate-900 {{ $titleSize }} mb-3 tracking-tight group-hover:text-indigo-600 transition-colors duration-300">{{ $config['title'] }}</h3>
            @endif
            @if(!empty($config['description']))
                <p class="text-slate-500 text-[15px] leading-relaxed font-medium">{{ $config['description'] }}</p>
            @endif
            
            <div class="mt-6 flex items-center gap-2 text-sm font-bold text-indigo-600 opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300">
                Read more
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </div>
    </div>
</section>
