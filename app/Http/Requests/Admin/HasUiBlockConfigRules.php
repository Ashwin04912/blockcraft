<?php

namespace App\Http\Requests\Admin;

trait HasUiBlockConfigRules
{
    /** Single source of truth for which block types the system accepts. */
    public static function supportedTypes(): array
    {
        return [
            'banner', 'card', 'list', 'stats', 'header', 'footer',
            'carousel', 'cta', 'form', 'table', 'tabs', 'accordion',
            'media', 'rich_text', 'feature_highlight',
        ];
    }

    protected function configRules(string $type): array
    {
        return match ($type) {
            'banner' => [
                'config.image_url' => ['required', 'url'],
                'config.link'      => ['required', 'url'],
            ],
            'card' => [
                'config.cards'                 => ['required', 'array', 'min:1'],
                'config.cards.*.title'         => ['required', 'string', 'max:255'],
                'config.cards.*.description'   => ['required', 'string'],
                'config.cards.*.image_url'     => ['nullable', 'url'],
            ],
            'list' => [
                'config.items'   => ['required', 'array', 'min:1'],
                'config.items.*' => ['required', 'string'],
                'config.layout'  => ['nullable', 'in:vertical,horizontal'],
            ],
            'stats' => [
                'config.stats'          => ['required', 'array', 'min:1'],
                'config.stats.*.label'  => ['required', 'string'],
                'config.stats.*.value'  => ['required', 'string'],
                'config.stats.*.icon'   => ['nullable', 'string', 'max:100'],
            ],
            'header' => [
                'config.logo_text'                => ['nullable', 'string', 'max:255'],
                'config.logo_url'                 => ['nullable', 'url'],
                'config.cta_label'                => ['nullable', 'string', 'max:255'],
                'config.cta_url'                  => ['nullable', 'string', 'max:2048'],
                'config.nav_links'                => ['nullable', 'array'],
                'config.nav_links.*.label'        => ['required_with:config.nav_links', 'string'],
                'config.nav_links.*.url'          => ['required_with:config.nav_links', 'string'],
            ],
            'footer' => [
                'config.brand'                    => ['nullable', 'string', 'max:255'],
                'config.tagline'                  => ['nullable', 'string', 'max:255'],
                'config.copyright'                => ['nullable', 'string', 'max:255'],
                'config.links'                    => ['nullable', 'array'],
                'config.links.*.label'            => ['required_with:config.links', 'string'],
                'config.links.*.url'              => ['required_with:config.links', 'string'],
                'config.social_links'             => ['nullable', 'array'],
                'config.social_links.*.platform'  => ['required_with:config.social_links', 'string'],
                'config.social_links.*.url'       => ['required_with:config.social_links', 'string'],
            ],
            'carousel' => [
                'config.items'         => ['required', 'array', 'min:1'],
                'config.items.*.image' => ['required', 'url'],
                'config.items.*.title' => ['nullable', 'string', 'max:255'],
            ],
            'cta' => [
                'config.heading'     => ['required', 'string', 'max:255'],
                'config.subheading'  => ['nullable', 'string', 'max:500'],
                'config.button_text' => ['required', 'string', 'max:100'],
                'config.button_link' => ['required', 'url'],
                'config.style'       => ['nullable', 'string', 'in:primary,secondary'],
            ],
            'form' => [
                'config.fields'              => ['required', 'array', 'min:1'],
                'config.fields.*.label'      => ['required', 'string', 'max:255'],
                'config.fields.*.name'       => ['required', 'string', 'max:100'],
                'config.fields.*.type'       => ['required', 'string', 'in:text,email,textarea,select,checkbox'],
                'config.fields.*.required'   => ['nullable', 'boolean'],
                'config.submit_label'        => ['nullable', 'string', 'max:100'],
            ],
            'table' => [
                'config.headers'   => ['required', 'array', 'min:1'],
                'config.headers.*' => ['required', 'string'],
                'config.rows'      => ['required', 'array', 'min:1'],
                'config.rows.*'    => ['required', 'array', 'min:1'],
                'config.rows.*.*'  => ['nullable', 'string'],
            ],
            'tabs' => [
                'config.tabs'           => ['required', 'array', 'min:1'],
                'config.tabs.*.label'   => ['required', 'string', 'max:255'],
                'config.tabs.*.content' => ['required', 'string'],
            ],
            'accordion' => [
                'config.items'            => ['required', 'array', 'min:1'],
                'config.items.*.question' => ['required', 'string', 'max:255'],
                'config.items.*.answer'   => ['required', 'string'],
            ],
            'media' => [
                'config.media_type' => ['required', 'string', 'in:image,video'],
                'config.url'        => ['required', 'url'],
                'config.caption'    => ['nullable', 'string', 'max:255'],
            ],
            'rich_text' => [
                'config.html' => ['required', 'string'],
            ],
            'feature_highlight' => [
                'config.icon'        => ['nullable', 'string', 'max:100'],
                'config.heading'     => ['required', 'string', 'max:255'],
                'config.description' => ['required', 'string'],
            ],
            default => [],
        };
    }

    public function attributes(): array
    {
        return [
            'config.image_url'   => 'image URL',
            'config.link'        => 'link URL',
            'config.title'       => 'card title',
            'config.description' => 'description',
            'config.items'       => 'items',
            'config.stats'       => 'stats',
            'config.heading'     => 'heading',
            'config.button_text' => 'button text',
            'config.button_link' => 'button link',
            'config.fields'      => 'form fields',
            'config.headers'     => 'table headers',
            'config.rows'        => 'table rows',
            'config.tabs'        => 'tabs',
            'config.html'        => 'rich text content',
        ];
    }
}
