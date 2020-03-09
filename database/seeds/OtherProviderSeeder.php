<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class OtherProviderSeeder extends Seeder
{
    public function run()
    {
        $accountType = AccountType::where('type','charges')->first();
        $accountType->accounts()->create([
            'account'       => "Charge Autre Fournisseur"
        ]);
    }
}
