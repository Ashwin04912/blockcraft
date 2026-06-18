<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUiBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type');

        $rules = [
            'title'         => ['required', 'string', 'max:255'],
            'type'          => ['required', 'in:banner,card,list,stats,header,footer'],
            'is_active'     => ['sometimes', 'boolean'],
            'display_order' => ['sometimes', 'integer', 'min:0'],
            'config.size'   => ['sometimes', 'nullable', 'in:sm,md,lg'],
        ];

        return array_merge($rules, $this->configRules($type));
    }

    protected function configRules(string $type): array
    {
        return match ($type) {
            'banner' => [
                'config.image_url' => ['required', 'url'],
                'config.link'      => ['required', 'url'],
            ],
            'card' => [
                'config.title'       => ['required', 'string', 'max:255'],
                'config.description' => ['required', 'string'],
                'config.image_url'   => ['nullable', 'url'],
            ],
            'list' => [
                'config.items'   => ['required', 'array', 'min:1'],
                'config.items.*' => ['required', 'string'],
            ],
            'stats' => [
                'config.stats'          => ['required', 'array', 'min:1'],
                'config.stats.*.label'  => ['required', 'string'],
                'config.stats.*.value'  => ['required', 'string'],
            ],
            'header' => [
                'config.bg_style'                 => ['nullable', 'string', 'in:light,dark,gradient'],
                'config.logo_text'                => ['nullable', 'string', 'max:255'],
                'config.logo_url'                 => ['nullable', 'url'],
                'config.cta_label'                => ['nullable', 'string', 'max:255'],
                'config.cta_url'                  => ['nullable', 'url'],
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
            'config.items'       => 'list items',
            'config.stats'       => 'stats',
        ];
    }
}
