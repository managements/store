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
        $caisseType = AccountType::where('type','caisses')->first();
        $caisseType->accounts()->create([
            'account'       => "Caisse " . $store->name
        ]);
    }
}