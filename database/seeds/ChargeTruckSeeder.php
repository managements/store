<?php

use App\Mode;
use App\Storage\ChargeStorage;
use Illuminate\Database\Seeder;

class ChargeTruckSeeder extends Seeder
{
    public function run()
    {
        // payment 1800 && charge_details
        $data = [
            'payments'  =>
                [
                   [
                    'price'         => 600,
                    'operation'     => 13456,
                    'mode_id'       => Mode::where('mode','cheque')->first()->id
                   ],
                   [
                    'price'         => 700,
                    'operation'     => 134565,
                    'mode_id'       => Mode::where('mode','transfer')->first()->id
                   ],
                ],
            'details' => [
                [
                    'label'     => "Achat filtre Huile",
                    'price'     => 500,
                    'charge'    => \App\Charge::where('charge', 'Frais')->first()->id,
                ],
                [
                    'label'     => "Videnge Gasoil",
                    'price'     => 1300,
                    'charge'    => \App\Charge::where('charge', 'Entretien')->first()->id,
                ],
            ],
            'truck'     => \App\Truck::first()->id
        ];
        $charge = new ChargeStorage();
        $charge->add($data);
        $charge_truck = \App\ChargeTruck::first();
        $charge->sub($charge_truck);
    }
}
