<?php

namespace App\Storage;

use App\Account;
use App\AccountType;
use App\Partner;
use App\Product;
use App\Size;
use App\Stock;

class ProviderStorage
{

    private $retained = [];

    private $retainedTotal = 0;

    public function add(array $data)
    {
        // create new account
        $account = Account::create([
            'account'           => $data['name'],
            'account_type_id'   => AccountType::where('type', 'stock')->first()->id
        ]);
        // create new Provider
        $provider = $account->partners()->create([
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
        $stock->add($provider);
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
        $account = Account::where([
            'account'           => $provider->name,
            'account_type_id'   => AccountType::where('type', 'provider')->first()->id
        ])->first();
        $account->update(['account'    => $data['name']]);
        return $provider->update($data);
    }

    public function account(Partner $provider)
    {
        return $provider->compte->details;
    }

    public function retained(Partner $provider)
    {
        // size
        $sizes = Size::all();
        foreach ($sizes as $size) {
            $products = $size->products;
            foreach ($products as $product) {
                if ($product->type->type === 'consign') {
                    $stock = Stock::where([
                        ['product_id', $product->id],
                        ['partner_id', $provider->id]
                    ])->first();
                    if($stock) {
                        $price = $product->prices()->orderBy('id','desc')->first();
                        $price_buy = $price->buy * $stock->qt;
                        $this->retainedTotal = $this->retainedTotal + $price_buy;
                        $this->retained[$size->size] = ['qt' => $stock->qt, 'price' => $price_buy];
                    }
                }
            }
        }
        $this->retained['total'] = $this->retainedTotal;
        return $this->retained;
    }

    public function sold(Partner $provider)
    {
        $cr = $provider->compte->details()->sum('cr');
        $db = $provider->compte->details()->sum('db');
        $sold = $cr - $db;
        if($sold > 0) {
            return "<span class='text-success'>$sold DB</span>";
        }
        return "<span class='text-danger'>$sold CR</span>";
    }
}