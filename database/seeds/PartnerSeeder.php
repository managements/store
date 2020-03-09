<?php

use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        //todo:: seed
        $provider = new \App\Storage\ProviderStorage();
        $data = [
            "name"          => "ZIZ",
            "speaker"       => "PDG",
            "rc"            => 653,
            "patent"        => 683541,
            "ice"           => 65456843
        ];
        $provider->add($data);
    }
}
