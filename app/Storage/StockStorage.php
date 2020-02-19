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


    public function add()
    {
        $this->addProduct();
        $this->addStock($this->createdProducts);
    }

    private function addProduct()
    {
        $sizes = Size::all();
        $providers = Partner::where('provider',1)->get();
        $types = ProductType::where('type','!=', 'foreign')->get();
        foreach ($sizes as $size) {
            foreach ($providers as $provider) {
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
    }

    private function addStock($products)
    {
        $trucks = Truck::all();
        $providers = Partner::where('provider',1)->get();
        foreach ($products as $product) {
            $product->stocks()->create([
                'store_id' => 1,
                'qt'        => 0
            ]);
            foreach ($providers as $provider) {
                $product->stocks()->create([
                    'partner_id' => $provider->id,
                    'qt'        => 0
                ]);
            }
            foreach ($trucks as $truck) {
                $product->stocks()->create([
                    'truck_id'  => $truck->id,
                    'qt'        => 0
                ]);
            }
        }
    }
}