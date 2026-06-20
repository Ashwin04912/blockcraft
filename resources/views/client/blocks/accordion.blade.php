{{--
    Accordion (FAQ) block partial
    Variables: $title (string), $config (array)
    Config keys: items (array of {question, answer})
--}}
@php
    $items = $config['items'] ?? [];
@endphp
<section>
    <div class="flex items-center gap-4 mb-6">
        <span class="inline-block w-2 h-6 bg-indigo-500 rounded-full"></span>
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $title }}</h2>
        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full ml-auto ring-1 ring-indigo-500/10">FAQ</span>
    </div>
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 divide-y divide-slate-100 overflow-hidden">
        @forelse($items as $i => $item)
            <details class="group">
                <summary class="flex items-center justify-between gap-4 px-5 py-4 cursor-pointer list-none font-semibold text-slate-800 hover:text-indigo-600 transition-colors">
                    {{ $item['question'] ?? '' }}
                    <svg class="h-4 w-4 flex-shrink-0 text-slate-400 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </summary>
                <div class="px-5 pb-4 text-sm text-slate-500 leading-relaxed">
                    {{ $item['answer'] ?? '' }}
                </div>
            </details>
        @empty
            <div class="px-5 py-8 text-center text-slate-400 text-sm">No FAQ items configured.</div>
        @endforelse
    </div>
</section>
