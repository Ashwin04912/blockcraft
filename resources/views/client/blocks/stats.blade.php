{{--
    Stats block partial
    Variables: $title (string), $config (array)
    Config keys: stats (array of { label, value, icon }), size (sm|md|lg)
--}}
@php
    $stats     = $config['stats'] ?? [];
    $size      = $config['size'] ?? 'md';
    $padding   = ['sm' => 'p-4',  'md' => 'p-6',  'lg' => 'p-8' ][$size] ?? 'p-6';
    $valSize   = ['sm' => 'text-2xl', 'md' => 'text-3xl', 'lg' => 'text-4xl'][$size] ?? 'text-3xl';
@endphp
<section class="w-full">
    <div class="flex items-center gap-4 mb-6">
        <span class="inline-block w-2 h-6 bg-emerald-500 rounded-full"></span>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $title }}</h2>
        
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($stats as $stat)
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 {{ $padding }} text-center hover:shadow-lg hover:shadow-slate-900/10 hover:-translate-y-0.5 transition-all duration-200">
                @if(!empty($stat['icon']))
                    <div class="flex justify-center mb-2 text-emerald-500">
                        @if($stat['icon'] === 'users')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        @elseif($stat['icon'] === 'chart')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        @elseif($stat['icon'] === 'star')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                        @elseif($stat['icon'] === 'clock')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>
                @endif

                <div class="{{ $valSize }} font-extrabold text-emerald-600 tracking-tight">
                    {{ $stat['value'] ?? '—' }}
                </div>
                <div class="text-xs text-slate-500 font-semibold mt-2 uppercase tracking-wider">
                    {{ $stat['label'] ?? '' }}
                </div>
            </div>
        @empty
            <div class="col-span-full text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200 py-12">
                <span class="text-slate-400 text-sm font-medium">No stats configured.</span>
            </div>
        @endforelse
    </div>
</section>
