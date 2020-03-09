<?php

use App\ProductType;
use App\Size;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    private $foreign;
    private $products = [];

    public function run()
    {
        $types = ['gaz', 'consign', 'defective', 'foreign'];

        foreach ($types as $type) {
            $productType = ProductType::create([
                'type'      => $type
            ]);
            if($type === 'foreign') {
                $this->foreign = $productType;
            }
        }
        // create foreign product
        $sizes = Size::all();
        foreach ($sizes as $size) {
            $this->products[] = $size->products()->create([
                'type_id'       => $this->foreign->id,
                'tva'           => 20,
            ]);
        }
        // create foreign in stock store with qt 0
        foreach ($this->products as $product) {
            $product->stocks()->create([
                'qt'        => 0,
                'store_id'  => 1
            ]);
        }
    }
}
