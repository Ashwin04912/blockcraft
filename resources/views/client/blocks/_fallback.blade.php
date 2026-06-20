{{--
    Fallback partial — rendered when a block's `type` has no matching
    Blade view yet (e.g. a newly supported type without a template).
--}}
<section class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-slate-400 text-sm">
    <p class="font-semibold text-slate-500">{{ $title }}</p>
    <p class="mt-1">No template available for this block type yet.</p>
</section>
