<?php

namespace App\Http\Requests\Types;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        //     return auth()->check() &&
        //    auth()->user()->tenant_id !== null;
        return true;
    }
    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'integer'],

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')
                    ->where(fn ($q) => $q->where('tenant_id', $this->tenant_id))
                    ->ignore($this->route('id')),
            ],

            'icon' => ['nullable', 'string', 'max:50'],
            'is_active' => ['boolean'],
        ];
    }
}