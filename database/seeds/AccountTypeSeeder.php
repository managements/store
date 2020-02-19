<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run()
    {
        $types = ['caisses', 'charges', 'provider', 'client', 'store', 'tva', 'cheque', 'virement', 'term', 'gain_loss'];

        foreach ($types as $type) {
            AccountType::create([
                'type' => $type
            ]);
        }

        $types = AccountType::where('type', 'tva')
            ->orwhere('type','cheque')
            ->orwhere('type','virement')
            ->orwhere('type','term')
            ->orwhere('type','gain_loss')
            ->orwhere('type','store')
            ->get();

        foreach ($types as $type) {
            $type->accounts()->create([
                'account'       => $type->type
            ]);
        }
    }
}
