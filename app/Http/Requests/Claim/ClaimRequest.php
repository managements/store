<?php

namespace App\Http\Requests\Claim;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClaimRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "payments.0.price"  => [Rule::requiredIf(function () {
                return (is_null($this->payments[2]['price']) && is_null($this->payments[1]['price']));
            })],
            "payments.1.price"          => [Rule::requiredIf(function () {
                return (is_null($this->payments[0]['price']) && is_null($this->payments[2]['price']));
            })],
            "payments.2.price"          => [Rule::requiredIf(function () {
                return (is_null($this->payments[0]['price']) && is_null($this->payments[1]['price']));
            })],
            "payments.0.operation"        => [Rule::requiredIf(function () {
                return (!is_null($this->payments[0]['price']));
            })],
            "payments.1.operation"     => [Rule::requiredIf(function () {
                return (!is_null($this->payments[1]['operation']));
            })],
        ];
    }

    public function attributes()
    {
        return [
            'payments.1.operation'      => 'numéro de chéque',
            'payments.0.operation'      => "numéro d'opération",
            "payments.1.price"          => "Cheque",
            "payments.0.price"          => "Virement"
        ];
    }
}
