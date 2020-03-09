<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "name"      => "required|string|min:2|max:191",
            "speaker"   => "required|string|min:2|max:191",
            "rc"        => "required|string|min:2|max:191",
            "patent"    => "required|string|min:2|max:191",
            "ice"       => "required|string|min:2|max:191",
            'address'   => "required|string|min:2|max:191",
            'city'      => "required|int|exists:cities,id"
        ];
    }
}
