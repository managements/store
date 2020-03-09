<?php

use App\Charge;
use Illuminate\Database\Seeder;

class ChargeSeeder extends Seeder
{
    public function run()
    {
        $charges = ["assurance", "vignette", "visite technique", "gasoil", "Autre Services", "Frais", "Entretien"];

        foreach ($charges as $charge) {
            Charge::create([
                'charge' => $charge
            ]);
        }

    }
}
