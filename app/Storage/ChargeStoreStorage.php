<?php

namespace App\Storage;

use App\Account;
use App\AccountDetail;
use App\Charge;
use App\ChargeStore;
use App\Payment;

class ChargeStoreStorage
{
    public function add(array $data)
    {
        $charge_store = ChargeStore::create([
            'creator_id'    => auth()->id()
        ]);
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                $charge_store->payments()->attach($payment->id);
                // account Payment
                $this->addPaymentAccount($payment);
            }
        }
        foreach ($data['details'] as $detail) {
            $charge = Charge::find($detail['charge']);
            // account
            $accountDetail = AccountDetail::create([
                'account_id'     => Account::where('account','Charge Autre Fournisseur')->first()->id,
                'label'          => $charge->charge,
                'detail'         => $detail['label'],
                'db'             => $detail['price']
            ]);
            // chargeTruckDetails
            $charge_store->charge_store_details()->create([
                'label'             => $detail['label'],
                'price'             => $detail['price'],
                'charge_id'         => $detail['charge'],
                'account_detail_id' => $accountDetail->id
            ]);
        }
        return $charge_store;
    }

    private function addPaymentAccount(Payment $payment)
    {
        if ($payment->mode_id == 1) {
            $payment->account_details()->create([
                'label'         => "charge Dépôt",
                'detail'        => "Autre Fournisseur",
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','Caisse Dépôt')->first()->id,
            ]);
        }
        if ($payment->mode_id == 2) {

            $payment->account_details()->create([
                'label'         => "charge Dépôt",
                'detail'        => "Autre Fournisseur",
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','cheque_emitted')->first()->id,
            ]);
        }
        if ($payment->mode_id == 3) {
            $payment->account_details()->create([
                'label'         => "charge Dépôt",
                'detail'        => "Autre Fournisseur",
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','transfer_emitted')->first()->id,
            ]);
        }
        if ($payment->mode_id == 4) {
            $payment->account_details()->create([
                'label'         => "charge Dépôt",
                'detail'        => "Autre Fournisseur",
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','term_emitted')->first()->id,
            ]);
        }
    }

    public function sub(ChargeStore $chargeStore)
    {
        foreach ($chargeStore->charge_store_details as $charge_store_detail) {
            $charge_store_detail->account_detail->delete();
            $charge_store_detail->delete();
        }
        foreach ($chargeStore->payments as $payment) {
            // sub Payment Account
            foreach ($payment->account_details as $detail) {
                $detail->delete();
            }
            $chargeStore->payments()->detach($payment->id);
            // sub Payment
            $payment->delete();
        }
        return $chargeStore->delete();
    }


}