<?php

namespace App\Http\Requests\Trade;

use Illuminate\Foundation\Http\FormRequest;

class BcRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nbr'               => "required|string|max:191",
            'provider'          => "required|int|exists:partners,id",
            'intermediate'      => "required|int|exists:intermediates,id",
            'transporter'       => "required|int|exists:trucks,id",
        ];
    }
}
