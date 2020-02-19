<?php

namespace App\Http\Requests\User;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name'    => ['required', 'string', 'min:3', 'max:191'],
            'last_name'     => ['required', 'string', 'min:3', 'max:191'],
            'mobile'        => ['nullable', 'string', 'max:10', 'min:10', new MobileRule()],
            'cin'           => ['nullable', 'string', 'max:191', 'min:3'],
        ];
    }
}
