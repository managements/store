<?php

use App\Category;
use App\Storage\TruckStorage;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TruckSeeder extends Seeder
{
    public function run()
    {

        $driver_category = Category::where('category', 'driver')->first();
        $driver = $driver_category->staffs;
        $assistant_category = Category::where('category', 'driver')->first();
        $assistant = $assistant_category->staffs;

        $datas = [
            [
                'registered'        => "123456",
                'transporter'       => 1,
                'assurance'         => Carbon::now()->addMonths(3),
                'visit_technique'   => Carbon::now()->addMonths(3),
                'driver'            => $driver[0]->id,
                'assistant'         => $assistant[0]->id,
            ],
            [
                'registered'        => "123987",
                'transporter'       => 0,
                'assurance'         => Carbon::now()->addMonths(3),
                'visit_technique'   => Carbon::now()->addMonths(3),
                'driver'            => $driver[1]->id,
                'assistant'         => $assistant[1]->id,
            ]
        ];
        $truckStorage = new TruckStorage();
        foreach ($datas as $data) {
            $truckStorage->add($data);
        }
    }

}
