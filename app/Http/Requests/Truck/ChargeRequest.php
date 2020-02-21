<?php

namespace App\Http\Requests\Truck;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChargeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "truck"             => "required|int|exists:trucks,id",
            "details.*.price"   => "required|int",
            "details.*.label"   => "required|string|max:191",
            "details.*.charge"  => "required|int|exists:charges,id",
            "payments.0.price"  => [Rule::requiredIf(function () {
                return (is_null($this->payments[1]['price']));
            })],
            "payments.1.price"          => [Rule::requiredIf(function () {
                return (is_null($this->payments[0]['price']));
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
