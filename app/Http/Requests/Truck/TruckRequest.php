<?php

namespace App\Http\Requests\Truck;

use Illuminate\Foundation\Http\FormRequest;

class TruckRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "transporter"       => "required|bool",
            "registered"        => "required|string|min:3|max:191",
            "assistant"         => "required|int|exists:staff,id",
            "driver"            => "required|int|exists:staff,id"
        ];
    }
}
