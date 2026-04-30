<?php

namespace App\Http\Requests\Types;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //     return auth()->check() &&
        //    auth()->user()->tenant_id !== null;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'integer'],

            'name' => ['required', 'string', 'max:255'],

            'icon' => ['nullable', 'string', 'max:50'],

            'is_active' => ['boolean'],
        ];
    }
}
