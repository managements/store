<?php

namespace App\Storage;


use App\Partner;
use App\Product;
use App\ProductType;
use App\Size;
use App\Truck;

class StockStorage
{
    private $createdProducts = [];

    public function add($provider)
    {
        $this->addProduct($provider);
        $this->addStock($this->createdProducts,$provider);
        $this->addDiscount($this->createdProducts);
    }

    private function addProduct($provider)
    {
        $sizes = Size::all();
        $types = ProductType::where('type','!=', 'foreign')->get();
        foreach ($sizes as $size) {
                foreach ($types as $type) {
                    $product = $size->products()->create([
                        'type_id'       => $type->id,
                        'tva'           => ($type->type === 'gaz') ? 10 : 20,
                        'partner_id'    => $provider->id
                    ]);
                    $product->prices()->create([
                        'buy'       => 0,
                        'sale'      => 0,
                    ]);
                    $this->createdProducts[] = $product;
                }
        }
    }

    private function addStock($products,$provider)
    {
        $trucks = Truck::all();
        foreach ($products as $product) {
            // store
            $product->stocks()->create([
                'store_id' => 1,
                'qt'        => 0
            ]);
            // provider
            $product->stocks()->create([
                'partner_id' => $provider->id,
                'qt'        => 0
            ]);
            // trucks
            foreach ($trucks as $truck) {
                $product->stocks()->create([
                    'truck_id'  => $truck->id,
                    'qt'        => 0
                ]);
            }
        }
    }

    private function addDiscount($products)
    {
        $clients = Partner::where('provider',0)->get();
        foreach ($clients as $client) {
            foreach ($products as $product) {

                $client->remises()->create([
                    'remise'    => 0,
                    'product_id' => $product->id
                ]);
            }
        }
    }
}