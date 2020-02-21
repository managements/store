<?php

namespace App\Storage;

use App\AccountDetail;
use App\Charge;
use App\ChargeTruck;
use App\Payment;
use App\Truck;

class ChargeStorage
{
    public function add(array $data)
    {
        // data : truck - payments['cheque']['price'=>,'operation'=>,'mode_id'=>] - details['']
        // charge Truck
        $chargeTruck = ChargeTruck::create([
            'truck_id'      => $data['truck'],
            'creator_id'    => auth()->id()
        ]);
        // payment
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                $payment->charge_trucks()->attach($chargeTruck->id);
            }

        }

        $truck = Truck::find($data['truck']);
        foreach ($data['details'] as $detail) {
            $charge = Charge::find($detail['charge']);
            // account
            $accountDetail = AccountDetail::create([
                'account_id'     => $truck->account_charge_id,
                'label'          => $charge->charge,
                'detail'         => $detail['label'],
                'qt_enter'       => 1,
                'cr'             => $chargeTruck->payments()->sum('price')
            ]);
            // chargeTruckDetails
            $chargeTruck->charge_truck_details()->create([
                'label'         => $detail['label'],
                'price'         => $detail['price'],
                'charge_id'     => $detail['charge'],
                'account_detail_id' => $accountDetail->id
            ]);
        }
        return $chargeTruck;
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
            $chargeTruck->payments()->detach($payment->id);
            $payment->delete();
        }
        // sub chargeTruck
       return $chargeTruck->delete();
    }

}