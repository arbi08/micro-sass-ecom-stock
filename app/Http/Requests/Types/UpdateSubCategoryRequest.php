<?php

namespace App\Http\Requests\Types;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubCategoryRequest extends FormRequest
{
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

            'parent_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($q) =>
                    $q->where('tenant_id', $this->tenant_id)
                ),
            ],

            'icon' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $parent = \App\Models\Category::find($this->parent_id);

            if (!$parent || is_null($parent->parent_id)) {
                $validator->errors()->add(
                    'parent_id',
                    'Parent must be a Category only'
                );
            }

            // prevent invalid deep nesting
            if (!is_null($parent?->parent?->parent_id)) {
                $validator->errors()->add(
                    'parent_id',
                    'Max 2 levels allowed'
                );
            }
        });
    }
}