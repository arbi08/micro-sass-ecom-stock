<?php

namespace App\Http\Requests\Types;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tenant_id' => ['required', 'integer'],

            'name' => ['required', 'string', 'max:255'],

            'parent_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($q) =>
                    $q->where('tenant_id', $this->tenant_id)
                ),
            ],
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

            // optional: prevent deep nesting
            if (!is_null($parent?->parent?->parent_id)) {
                $validator->errors()->add(
                    'parent_id',
                    'Max 2 levels allowed'
                );
            }
        });
    }
}