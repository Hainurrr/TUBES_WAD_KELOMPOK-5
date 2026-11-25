<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $tenantId = $this->route('tenant');

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:100'],
            'category_select' => ['nullable', 'string', 'max:100'],
            'category_custom' => ['nullable', 'string', 'max:100'],
            'owner_name' => ['required', 'string', 'max:255'],
            'id_user' => [
                'nullable',
                'integer',
                'exists:users,id_user',
                Rule::unique('tenants', 'id_user')->ignore($tenantId, 'id_tenant'),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:255'],
            'banner_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public function resolvedCategory(): ?string
    {
        $category = $this->input('category_select');
        if ($category === 'Lainnya' || empty($category)) {
            $category = $this->input('category_custom');
        }

        return $category ? trim((string) $category) : null;
    }
}
