<?php

use App\Account;
use App\AccountType;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $account = Account::create([
            'account'           => "Particular",
            'account_type_id'   => AccountType::where('type', 'stock')->first()->id
        ]);

        $account->partners()->create([
            "account"           => 1000,
            "name"              => "Particulier",
            "speaker"           => "Particulier",
            "creator_id"        => 1,
        ]);
        // todo:: delete this in production
        $account = Account::create([
            'account'           => "stock client",
            'account_type_id'   => AccountType::where('type', 'stock')->first()->id
        ]);
        $client = $account->partners()->create([
            "account"           => 100000,
            "name"              => "client",
            "speaker"           => "client",
            "rc"                => 64321,
            "patent"            => 65342,
            "ice"               => 524,
            "creator_id"        => 1,
        ]);
        $client->address()->create([
            'address'   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt",
            'city_id'   => 1
        ]);
        $products = \App\Product::all();
        foreach ($products as $product) {
            $client->remises()->create([
                'remise'        => 0,
                'product_id'    => $product->id
            ]);
        }
    }
}
