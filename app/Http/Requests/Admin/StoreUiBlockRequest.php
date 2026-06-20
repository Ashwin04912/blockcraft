<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUiBlockRequest extends FormRequest
{
    use HasUiBlockConfigRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type');

        $rules = [
            'title'         => ['required', 'string', 'max:255'],
            'type'          => ['required', Rule::in(self::supportedTypes())],
            'is_active'     => ['sometimes', 'boolean'],
            'display_order' => ['sometimes', 'integer', 'min:0'],
            'config.size'   => ['sometimes', 'nullable', 'in:sm,md,lg'],
            'config.bg_style' => ['sometimes', 'nullable', 'string', 'in:light,dark,gradient'],
        ];

        return array_merge($rules, $this->configRules($type));
    }
}
