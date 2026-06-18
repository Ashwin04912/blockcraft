@php
    $isEdit    = isset($block) && $block !== null;
    $oldType   = old('type', $isEdit ? $block->type : 'banner');
    $oldConfig = old('config', $isEdit ? ($block->config ?? []) : []);
@endphp

{{-- Validation errors --}}
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-sm font-semibold text-red-700 mb-2">Please fix the following errors:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-5">

    {{-- Title --}}
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title"
               value="{{ old('title', $isEdit ? $block->title : '') }}"
               placeholder="e.g. Hero Banner, Feature Cards…"
               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition {{ $errors->has('title') ? 'border-red-400' : '' }}"
               required>
    </div>

    {{-- Type --}}
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
        <select id="type" name="type"
                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition bg-white">
            @foreach(['banner', 'card', 'list', 'stats', 'header', 'footer'] as $t)
                <option value="{{ $t }}" {{ $oldType === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Display Order --}}
    <div>
        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1.5">Display Order</label>
        <input type="number" id="display_order" name="display_order" min="0"
               value="{{ old('display_order', $isEdit ? $block->display_order : 0) }}"
               class="w-32 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        <p class="text-xs text-gray-400 mt-1">Lower number = shown first. Drag to reorder on the index page.</p>
    </div>

    {{-- Active --}}
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1"
               {{ old('is_active', $isEdit ? $block->is_active : true) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible on client page)</label>
    </div>

    <hr class="border-gray-100">

    {{-- ===== CONFIG FIELDS ===== --}}

    {{-- Banner --}}
    <div id="cfg-banner" class="space-y-4 config-section">
        <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Banner Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Image URL <span class="text-red-500">*</span></label>
            <input type="url" name="config[image_url]"
                   value="{{ old('config.image_url', $oldConfig['image_url'] ?? '') }}"
                   placeholder="https://example.com/banner.jpg"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Link URL <span class="text-red-500">*</span></label>
            <input type="url" name="config[link]"
                   value="{{ old('config.link', $oldConfig['link'] ?? '') }}"
                   placeholder="https://example.com"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
    </div>

    {{-- Card --}}
    <div id="cfg-card" class="space-y-4 config-section hidden">
        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Card Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Card Title <span class="text-red-500">*</span></label>
            <input type="text" name="config[title]"
                   value="{{ old('config.title', $oldConfig['title'] ?? '') }}"
                   placeholder="Feature title"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-red-500">*</span></label>
            <textarea name="config[description]" rows="3"
                      placeholder="Short description of this card…"
                      class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition resize-none">{{ old('config.description', $oldConfig['description'] ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Image URL <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="url" name="config[image_url]"
                   value="{{ old('config.image_url', $oldConfig['image_url'] ?? '') }}"
                   placeholder="https://example.com/image.jpg"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
    </div>

    {{-- List --}}
    <div id="cfg-list" class="space-y-4 config-section hidden">
        <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">List Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Items <span class="text-red-500">*</span></label>
            <div id="list-items" class="space-y-2">
                @php
                    $listItems = old('config.items', $oldConfig['items'] ?? ['']);
                    if (empty($listItems)) $listItems = [''];
                @endphp
                @foreach($listItems as $item)
                    <div class="flex gap-2 list-item-row items-center">
                        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <input type="text" name="config[items][]"
                               value="{{ $item }}"
                               placeholder="List item…"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <button type="button" onclick="removeListItem(this)"
                                class="text-gray-400 hover:text-red-500 transition px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addListItem()"
                    class="mt-2 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add item
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div id="cfg-stats" class="space-y-4 config-section hidden">
        <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Stats Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statistics <span class="text-red-500">*</span></label>
            <div id="stats-items" class="space-y-2">
                @php
                    $statsItems = old('config.stats', $oldConfig['stats'] ?? [['label' => '', 'value' => '']]);
                    if (empty($statsItems)) $statsItems = [['label' => '', 'value' => '']];
                @endphp
                @foreach($statsItems as $i => $stat)
                    <div class="flex gap-2 stats-item-row items-center">
                        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <input type="text" name="config[stats][{{ $i }}][label]"
                               value="{{ $stat['label'] ?? '' }}"
                               placeholder="Label (e.g. Users)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <input type="text" name="config[stats][{{ $i }}][value]"
                               value="{{ $stat['value'] ?? '' }}"
                               placeholder="Value (e.g. 10,000)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <button type="button" onclick="removeStatItem(this)"
                                class="text-gray-400 hover:text-red-500 transition px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addStatItem()"
                    class="mt-2 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add stat
            </button>
        </div>
    </div>

    {{-- Header --}}
    <div id="cfg-header" class="space-y-4 config-section hidden">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Header Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Background Style</label>
            <select name="config[bg_style]"
                    class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition bg-white">
                @php $oldBgStyle = old('config.bg_style', $oldConfig['bg_style'] ?? 'light'); @endphp
                @foreach(['light', 'dark', 'gradient'] as $style)
                    <option value="{{ $style }}" {{ $oldBgStyle === $style ? 'selected' : '' }}>{{ ucfirst($style) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo Text</label>
            <input type="text" name="config[logo_text]"
                   value="{{ old('config.logo_text', $oldConfig['logo_text'] ?? '') }}"
                   placeholder="e.g. Acme Corp"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Logo Image URL <span class="text-gray-400 font-normal">(optional)</span></label>
            <input type="url" name="config[logo_url]"
                   value="{{ old('config.logo_url', $oldConfig['logo_url'] ?? '') }}"
                   placeholder="https://example.com/logo.png"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA Label</label>
                <input type="text" name="config[cta_label]"
                       value="{{ old('config.cta_label', $oldConfig['cta_label'] ?? '') }}"
                       placeholder="e.g. Get Started"
                       class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">CTA URL</label>
                <input type="url" name="config[cta_url]"
                       value="{{ old('config.cta_url', $oldConfig['cta_url'] ?? '') }}"
                       placeholder="https://example.com"
                       class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nav Links</label>
            <div id="nav-links-items" class="space-y-2">
                @php
                    $navLinks = old('config.nav_links', $oldConfig['nav_links'] ?? []);
                @endphp
                @foreach($navLinks as $index => $link)
                    <div class="flex gap-2 nav-link-row items-center">
                        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <input type="text" name="config[nav_links][{{ $index }}][label]"
                               value="{{ $link['label'] ?? '' }}"
                               placeholder="Label (e.g. Home)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <input type="text" name="config[nav_links][{{ $index }}][url]"
                               value="{{ $link['url'] ?? '' }}"
                               placeholder="URL (e.g. /home)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <button type="button" onclick="removeDynamicRow(this, '.nav-link-row')"
                                class="text-gray-400 hover:text-red-500 transition px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addNavLinkItem()"
                    class="mt-2 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add nav link
            </button>
        </div>
    </div>

    {{-- Footer --}}
    <div id="cfg-footer" class="space-y-4 config-section hidden">
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Footer Config</p>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Brand</label>
            <input type="text" name="config[brand]"
                   value="{{ old('config.brand', $oldConfig['brand'] ?? '') }}"
                   placeholder="e.g. Acme Corp"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tagline</label>
            <input type="text" name="config[tagline]"
                   value="{{ old('config.tagline', $oldConfig['tagline'] ?? '') }}"
                   placeholder="Making the world better…"
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Copyright</label>
            <input type="text" name="config[copyright]"
                   value="{{ old('config.copyright', $oldConfig['copyright'] ?? '') }}"
                   placeholder="© 2026 Acme Corp. All rights reserved."
                   class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Quick Links</label>
            <div id="footer-links-items" class="space-y-2">
                @php
                    $footerLinks = old('config.links', $oldConfig['links'] ?? []);
                @endphp
                @foreach($footerLinks as $index => $link)
                    <div class="flex gap-2 footer-link-row items-center">
                        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <input type="text" name="config[links][{{ $index }}][label]"
                               value="{{ $link['label'] ?? '' }}"
                               placeholder="Label (e.g. Terms)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <input type="text" name="config[links][{{ $index }}][url]"
                               value="{{ $link['url'] ?? '' }}"
                               placeholder="URL"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <button type="button" onclick="removeDynamicRow(this, '.footer-link-row')"
                                class="text-gray-400 hover:text-red-500 transition px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addFooterLinkItem()"
                    class="mt-2 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add quick link
            </button>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Social Links</label>
            <div id="social-links-items" class="space-y-2">
                @php
                    $socialLinks = old('config.social_links', $oldConfig['social_links'] ?? []);
                @endphp
                @foreach($socialLinks as $index => $link)
                    <div class="flex gap-2 social-link-row items-center">
                        <div class="drag-row-handle cursor-grab text-gray-300 hover:text-gray-500 active:cursor-grabbing px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                        </div>
                        <input type="text" name="config[social_links][{{ $index }}][platform]"
                               value="{{ $link['platform'] ?? '' }}"
                               placeholder="Platform (e.g. Twitter)"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <input type="text" name="config[social_links][{{ $index }}][url]"
                               value="{{ $link['url'] ?? '' }}"
                               placeholder="URL"
                               class="flex-1 rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition min-w-0">
                        <button type="button" onclick="removeDynamicRow(this, '.social-link-row')"
                                class="text-gray-400 hover:text-red-500 transition px-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="addSocialLinkItem()"
                    class="mt-2 inline-flex items-center gap-1.5 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add social link
            </button>
        </div>
    </div>

</div>
