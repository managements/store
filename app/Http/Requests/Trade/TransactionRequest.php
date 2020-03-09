<?php

namespace App\Http\Requests\Trade;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            "payments.0.price"  => [Rule::requiredIf(function () {
                return (
                    is_null($this->payments[1]['price'])
                    && is_null($this->payments[2]['price'])
                    && is_null($this->payments[3]['price'])
                );
            })],
            "payments.1.price"          => [Rule::requiredIf(function () {
                return (
                    is_null($this->payments[0]['price'])
                    && is_null($this->payments[2]['price'])
                    && is_null($this->payments[3]['price'])
                );
            })],
            "payments.2.price"          => [Rule::requiredIf(function () {
                return (
                    is_null($this->payments[1]['price'])
                    && is_null($this->payments[0]['price'])
                    && is_null($this->payments[3]['price'])
                );
            })],
            "payments.3.price"          => [Rule::requiredIf(function () {
                return (
                    is_null($this->payments[0]['price'])
                    && is_null($this->payments[1]['price'])
                    && is_null($this->payments[2]['price'])
                );
            })],
            "payments.0.operation"        => [Rule::requiredIf(function () {
                return (!is_null($this->payments[0]['price']));
            })],
            "payments.1.operation"     => [Rule::requiredIf(function () {
                return (!is_null($this->payments[1]['price']));
            })],
        ];
    }

    public function attributes()
    {
        return [
            'payments.0.operation'      => 'numéro de chéque',
            'payments.1.operation'      => "numéro d'opération",
            "payments.0.price"          => "Cheque",
            "payments.1.price"          => "Virement"
        ];
    }
}
