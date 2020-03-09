<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoadingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'driver'            => "required|int|exists:drivers,id",
            'provider'          => "required|int|exists:partners,id",
        ];
    }
}
