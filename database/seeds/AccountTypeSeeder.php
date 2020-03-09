<?php

use App\AccountType;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run()
    {
        /*
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
        */
        $types = ['caisses', 'charges', 'stock', 'tva', 'gain_loss', 'banque','term'];

        foreach ($types as $type) {
            AccountType::create([
                'type' => $type
            ]);
        }
        // tva and gain_loss
        $accountTypes = AccountType::where('type','tva')->orWhere('type','gain_loss')->get();
        foreach ($accountTypes as $accountType) {
            $accountType->accounts()->create([
                'account'       => $accountType->type
            ]);
        }
        // banque => cheque_emis, cheque_encaissement, virment_emis, virment_encaissé
        $banques = ['cheque_emitted', 'cheque_cashed', 'transfer_emitted', 'transfer_cashed'];
        $accountBanque = AccountType::where('type','banque')->first();
        foreach ($banques as $banque) {
            $accountBanque->accounts()->create([
                'account'   => $banque
            ]);
        }
        // term => term_emis, term_encaissement.
        $terms = ['term_emitted', 'term_cashed'];
        $accountTerm = AccountType::where('type','term')->first();
        foreach ($terms as $term) {
            $accountTerm->accounts()->create([
                'account'   => $term
            ]);
        }
    }
}
