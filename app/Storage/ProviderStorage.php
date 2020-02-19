<?php

namespace App\Storage;

use App\Account;
use App\AccountType;
use App\Partner;

class ProviderStorage
{
    public function add(array $data)
    {
        // create new account
        $account = Account::create([
            'account'           => $data['name'],
            'account_type_id'   => AccountType::where('type', 'provider')->first()->id
        ]);
        // create new Provider
        $account->partners()->create([
            "provider"      => 1,
            "account"       => $this->getAccount(),
            "name"          => $data['name'],
            "speaker"       => $data['speaker'],
            "rc"            => $data['rc'],
            "patent"        => $data['patent'],
            "ice"           => $data['ice'],
            "creator_id"    => auth()->id()
        ]);
        // create new Produit products
        $stock = new StockStorage();
        $stock->add();
    }

    private function getAccount()
    {
        $provider = Partner::where('provider', 1)->latest()->first();
        if($provider) {
            return $provider->account + 100;
        }

        return 900000;
    }

    public function update(Partner $provider, array $data)
    {
        return $provider->update($data);
    }
}