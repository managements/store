<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PswRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password'      => ['required', 'string', 'min:8', 'max:191'],
            'password'          => ['required', 'string', 'min:8', 'max:191', 'confirmed']
        ];
    }
}
