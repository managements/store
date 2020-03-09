<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class OtherProviderSeeder extends Seeder
{
    public function run()
    {
        $accountType = AccountType::where('type','charges')->first();
        $account = $accountType->accounts()->create([
            'account'       => "Charge Autre Fournisseur"
        ]);
        
      // Partner::create([]);
    }
}
