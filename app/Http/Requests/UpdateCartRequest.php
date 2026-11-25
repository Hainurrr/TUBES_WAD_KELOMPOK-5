<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'id_menu' => ['required', 'integer', 'min:1'],
            'action' => ['required', 'string', 'in:increase,decrease,set'],
            'qty' => ['nullable', 'integer', 'min:0', 'max:999'],
        ];
    }
}
