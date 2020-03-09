<?php

namespace App\Storage;


use App\AccountType;
use App\Store;

class StoreStorage
{
    public function add()
    {
        $store = Store::create([
            'name'  => 'Dépôt'
        ]);
        // caisse
        $caisseType = AccountType::where('type','caisses')->first();
        $caisseType->accounts()->create([
            'account'       => "Caisse " . $store->name
        ]);
        // stock
        $stockType = AccountType::where('type','stock')->first();
        $stockType->accounts()->create([
            'account'       => "Stock " . $store->name
        ]);
    }
}