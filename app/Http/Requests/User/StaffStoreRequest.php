<?php

namespace App\Http\Requests\User;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => ['nullable', Rule::requiredIf($this->category != 4), 'string', 'max:255', 'unique:users'],
            'password'      => ['nullable', Rule::requiredIf($this->category != 4), 'string', 'min:8', 'confirmed'],
            'category'      => ['required', 'int', 'exists:categories,id'],
            'first_name'    => ['required', 'string', 'min:3', 'max:191'],
            'last_name'     => ['required', 'string', 'min:3', 'max:191'],
            'mobile'        => ['nullable', 'string', 'max:10', 'min:10', new MobileRule()],
            'cin'           => ['nullable', 'string', 'max:191', 'min:3'],
        ];
    }
}
