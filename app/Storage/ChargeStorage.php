<?php

namespace App\Storage;

use App\Account;
use App\AccountDetail;
use App\Charge;
use App\ChargeTruck;
use App\Payment;
use App\Truck;

class ChargeStorage
{

    public function add(array $data)
    {
        // charge Truck
        $chargeTruck = ChargeTruck::create([
            'truck_id'      => $data['truck'],
            'creator_id'    => auth()->id()
        ]);
        $truck = Truck::find($data['truck']);
        $driver = $truck->driver;
        // payment
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                $payment->charge_trucks()->attach($chargeTruck->id);
                // account Payment
                $this->addPaymentAccount($payment,$truck,$driver);
            }
        }
        foreach ($data['details'] as $detail) {
            $charge = Charge::find($detail['charge']);
            // account
            $accountDetail = AccountDetail::create([
                'account_id'     => $truck->account_charge_id,
                'label'          => $charge->charge,
                'detail'         => $detail['label'],
                'cr'             => $detail['price']
            ]);
            // chargeTruckDetails
            $chargeTruck->charge_truck_details()->create([
                'label'             => $detail['label'],
                'price'             => $detail['price'],
                'charge_id'         => $detail['charge'],
                'account_detail_id' => $accountDetail->id
            ]);
        }
        return $chargeTruck;
    }

    private function addPaymentAccount(Payment $payment,Truck $truck,?string $driver = null)
    {
        if (is_null($driver)) {
            $detail = $truck->registered;
        }
        else{
            $detail = $truck->driver;
        }
        if ($payment->mode_id === 1) {
            $payment->account_details()->create([
                'label'         => "charge",
                'detail'        => $detail,
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','Caisse Dépôt')->first()->id,
            ]);
        }
        if ($payment->mode_id === 2) {
            $payment->account_details()->create([
                'label'         => "charge",
                'detail'        => $detail,
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','cheque_emitted')->first()->id,
            ]);
        }
        if ($payment->mode_id === 3) {
            $payment->account_details()->create([
                'label'         => "charge",
                'detail'        => $detail,
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','transfer_emitted')->first()->id,
            ]);
        }
        if ($payment->mode_id === 4) {
            $payment->account_details()->create([
                'label'         => "charge",
                'detail'        => $detail,
                'cr'            => $payment->price,
                'account_id'    => Account::where('account','term_emitted')->first()->id,
            ]);
        }
    }

    public function sub(ChargeTruck $chargeTruck)
    {
        foreach ($chargeTruck->charge_truck_details as $charge_truck_detail) {
            // sub account detail
            $charge_truck_detail->account_detail->delete();
            // sub charge truck details
            $charge_truck_detail->delete();
        }
        // sub and detach payment
        foreach ($chargeTruck->payments as $payment) {
            // sub Payment Account
            foreach ($payment->account_details as $detail) {
                $detail->delete();
            }
            $chargeTruck->payments()->detach($payment->id);
            // sub Payment
            $payment->delete();
        }
        // sub chargeTruck
       return $chargeTruck->delete();
    }

}