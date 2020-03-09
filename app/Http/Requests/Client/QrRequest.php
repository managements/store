<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class QrRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'number'        => "required|int|max:100"
        ];
    }
}
