<?php

namespace App\Storage;


use App\Trade;

class PaymentStorage
{
    public function getPaymentBl(Trade $trade)
    {
        $payments = [];
        foreach ($trade->payments()->orderBy('mode_id','asc')->get() as $payment) {
            $payments[] = [
                'price'         => $payment->price,
                'mode_id'       => $payment->mode_id,
                'operation'     => $payment->operation
            ];
        }
        return $payments;
    }
}