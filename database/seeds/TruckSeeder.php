<?php

use App\Storage\TruckStorage;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    public function run()
    {
        auth()->setUser(\App\User::find(1));
        $datas = [
            [
                'registered'        => "123456",
                'transporter'       => 1,
                'assurance'         => \Carbon\Carbon::now()->addMonths(3),
                'visit_technique'   => \Carbon\Carbon::now()->addMonths(3),
                'driver'            => 3,
                'assistant'         => 5,
            ],
            [
                'registered'        => "123987",
                'transporter'       => 0,
                'assurance'         => \Carbon\Carbon::now()->addMonths(3),
                'visit_technique'   => \Carbon\Carbon::now()->addMonths(3),
                'driver'            => 4,
                'assistant'         => 6,
            ]
        ];
        $truckStorage = new TruckStorage();
        foreach ($datas as $data) {
            $truckStorage->add($data);
        }
    }
}
