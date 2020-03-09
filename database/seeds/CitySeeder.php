<?php

use App\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $cities = ['casablanca'];
        foreach ($cities as $city) {
            City::create([
                'city'      => $city
            ]);
        }
    }
}
