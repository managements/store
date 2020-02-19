<?php

use App\Product;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->prices()->create([
                'buy'       => '35',
                'sale'      => '40'
            ]);
        }
    }
}
