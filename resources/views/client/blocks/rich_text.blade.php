{{--
    Rich text block partial
    Variables: $title (string), $config (array)
    Config keys: html (string — HTML/markdown-ish content)

    Security: $config['html'] is admin-authored but still passed through
    strip_tags() with an allow-list before output. No <script>/<style>/event
    handlers/inline JS survive this — never echo config.html raw.

    <a> is intentionally excluded: strip_tags() only removes disallowed
    tags, it doesn't sanitize attributes on tags it keeps, so an allowed
    <a href="javascript:..."> would still execute. No link support here
    until a real attribute-aware sanitizer (e.g. HTML Purifier) is added.
--}}
@php
    $allowedTags = '<p><br><strong><b><em><i><ul><ol><li><h2><h3><h4><blockquote>';
    $safeHtml = strip_tags($config['html'] ?? '', $allowedTags);
@endphp
<section class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-900/5 p-6">
    @if($title)
        <h2 class="text-xl font-bold text-slate-900 tracking-tight mb-2">{{ $title }}</h2>
    @endif
    <div class="prose prose-slate prose-sm max-w-none text-slate-600">
        {!! $safeHtml !!}
    </div>
</section>
