<?php

namespace App\Storage;


use App\Partner;
use App\Product;
use App\ProductType;
use App\Remise;
use App\Size;

class ProductStorage
{
    private $products = [];

    public function getProducts($providers)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();

                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva
                        ];
                    }
                }
            }

        }
        return $this->products;
    }

    public function getTradeProduct($providers)
    {
        $operations = ['sale', 'buy'];
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        foreach ($operations as $operation) {
                            if ($operation == 'sale') {
                                $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                    'product' => $product->id, 'price' => $price->sale, 'tva' => $product->tva
                                ];
                            } else {
                                if ($type->type == 'consign') {
                                    $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                        'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva
                                    ];
                                }
                            }
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getTradeOrderProduct($providers, $transaction)
    {
        $operations = ['sale', 'buy'];
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        foreach ($operations as $operation) {
                            if ($operation == 'sale') {
                                $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                    'product' => $product->id, 'price' => $price->sale, 'tva' => $product->tva,
                                    'qt' => null
                                ];
                                if ($sale = $transaction->sale) {
                                    foreach ($sale->orders as $order) {
                                        if ($order->product_id === $product->id) {
                                            $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                                'product' => $product->id, 'price' => $price->sale,
                                                'tva' => $product->tva, 'qt' => $order->qt
                                            ];
                                        }
                                    }
                                }

                            } else {
                                if ($type->type == 'consign') {
                                    $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                        'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva,
                                        'qt' => null
                                    ];
                                    if ($buy = $transaction->buy) {
                                        foreach ($buy->orders as $order) {
                                            if ($order->product_id === $product->id) {
                                                $this->products[$provider->name][$operation][$type->type][$size->size] = [
                                                    'product' => $product->id, 'price' => $price->buy,
                                                    'tva' => $product->tva, 'qt' => $order->qt
                                                ];
                                            }
                                        }
                                    }

                                }
                            }
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getGazProducts($providers)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        if (!$price) {
                        }
                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva
                        ];
                    }
                }
            }

        }
        return $this->products;
    }

    public function getGazOrderProducts($providers, $orders)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();

                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva, 'qt' => null
                        ];
                        foreach ($orders as $order) {
                            if ($order->product_id === $product->id) {
                                $this->products[$provider->name][$type->type][$size->size] = [
                                    'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva,
                                    'qt' => $order->qt
                                ];
                            }
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getOrderProducts($providers, $orders)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva, 'qt' => null
                        ];
                        foreach ($orders as $order) {
                            if ($order->product_id === $product->id) {
                                $this->products[$provider->name][$type->type][$size->size] = [
                                    'product' => $product->id, 'price' => $price->buy, 'tva' => $product->tva,
                                    'qt' => $order->qt
                                ];
                            }
                        }
                    }
                }

            }

        }
        return $this->products;
    }

    public function getLoadingProducts($providers)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        $consign = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', 2],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        $price_consign = $consign->prices()->orderby('id', 'desc')->first();
                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id,
                            'price' => $price->sale + $price_consign->sale,
                            'tva' => $product->tva
                        ];
                    }
                }
            }

        }
        return $this->products;
    }

    public function getUnloadingProducts($providers)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign' || $type->type == 'foreign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        if ($type->type == 'foreign') {
                            $product = $size->products()->where([
                                ['type_id', $type->id],
                            ])->first();
                            $consign = $size->products()->where([
                                ['partner_id', $provider->id], ['type_id', 2], ['size_id', $product->size_id]
                            ])->first();
                            $price_defective = $consign->prices()->orderby('id', 'desc')->first();
                            $prices = $price_defective->buy;
                            $this->products[$provider->name][$type->type][$size->size] = [
                                'product'   => $product->id,
                                'price'     => $prices,
                                'tva'       => $product->tva
                            ];
                        }
                        else{
                            $product = $size->products()->where([
                                ['partner_id', $provider->id],
                                ['type_id', $type->id],
                            ])->first();
                            $price = $product->prices()->orderby('id', 'desc')->first();
                            if ($type->type == 'gaz') {
                                $consign = $size->products()->where([
                                    ['partner_id', $provider->id], ['type_id', 2], ['size_id', $product->size_id]
                                ])->first();
                                // price
                                $price_consign = $consign->prices()->orderby('id', 'desc')->first();
                                $prices = $price->buy + $price_consign->buy;
                            }
                            else{
                                $prices = $price->buy;
                            }
                            $this->products[$provider->name][$type->type][$size->size] = [
                                'product'   => $product->id,
                                'price'     => $prices,
                                'tva'       => $product->tva
                            ];
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getLoadingEditProducts($providers, $loading)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        $product = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', $type->id],
                        ])->first();
                        $consign = $size->products()->where([
                            ['partner_id', $provider->id], ['type_id', 2],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        $price_consign = $consign->prices()->orderby('id', 'desc')->first();
                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id, 'price' => $price->sale + $price_consign->sale,
                            'tva' => $product->tva, 'qt' => null
                        ];
                        foreach ($loading->tmps as $tmp) {
                            if ($tmp->product_id === $product->id) {
                                $this->products[$provider->name][$type->type][$size->size] = [
                                    'product' => $product->id, 'price' => $price->sale + $price_consign->sale,
                                    'tva' => $product->tva, 'qt' => $tmp->qt
                                ];
                            }
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getUnloadingEditProducts($providers, $unloading)
    {
        foreach ($providers as $provider) {
            // type
            $types = ProductType::all();
            foreach ($types as $type) {
                if ($type->type == 'gaz' || $type->type == 'consign' || $type->type == 'foreign') {
                    // size
                    $sizes = Size::all();
                    foreach ($sizes as $size) {
                        if ($type->type == 'foreign') {
                            $product = $size->products()->where([
                                ['type_id', $type->id],
                            ])->first();
                            $consign = $size->products()->where([
                                ['partner_id', $provider->id], ['type_id', 2], ['size_id', $product->size_id]
                            ])->first();
                            $price_defective = $consign->prices()->orderby('id', 'desc')->first();
                            $prices = $price_defective->buy;
                            $this->products[$provider->name][$type->type][$size->size] = [
                                'product'   => $product->id,
                                'price'     => $prices,
                                'tva'       => $product->tva,
                                'qt'        => null
                            ];
                            foreach ($unloading->tmps as $tmp) {
                                if ($tmp->product_id == $product->id) {
                                    $this->products[$provider->name][$type->type][$size->size] = [
                                        'product'   => $product->id,
                                        'price'     => $prices,
                                        'tva'       => $product->tva,
                                        'qt'        => $tmp->qt
                                    ];
                                }
                            }
                        }
                        else{
                            $product = $size->products()->where([
                                ['partner_id', $provider->id],
                                ['type_id', $type->id],
                            ])->first();
                            $price = $product->prices()->orderby('id', 'desc')->first();
                            if ($type->type == 'gaz') {
                                $consign = $size->products()->where([
                                    ['partner_id', $provider->id], ['type_id', 2], ['size_id', $product->size_id]
                                ])->first();
                                // price
                                $price_consign = $consign->prices()->orderby('id', 'desc')->first();
                                $prices = $price->buy + $price_consign->buy;
                            }
                            else{
                                $prices = $price->buy;
                            }
                            $this->products[$provider->name][$type->type][$size->size] = [
                                'product'   => $product->id,
                                'price'     => $prices,
                                'tva'       => $product->tva,
                                'qt'        => null
                            ];
                            foreach ($unloading->tmps as $tmp) {
                                if ($tmp->product_id == $product->id) {
                                    $this->products[$provider->name][$type->type][$size->size] = [
                                        'product'   => $product->id,
                                        'price'     => $prices,
                                        'tva'       => $product->tva,
                                        'qt'        => $tmp->qt
                                    ];
                                }

                            }
                        }
                    }
                }
            }

        }
        return $this->products;
    }

    public function getPriceProducts($providers)
    {
        foreach ($providers as $provider) {
            $types = ProductType::where('type', '!=', 'foreign')->get();
            foreach ($types as $type) {
                $sizes = Size::all();
                foreach ($sizes as $size) {
                    $product = $size->products()->where([
                        ['partner_id', $provider->id], ['type_id', $type->id],
                    ])->first();
                    $price = $product->prices()->orderby('id', 'desc')->first();
                    $this->products[$provider->name][$type->type][$size->size] = [
                        'product' => $product->id, 'sale' => $price->sale, 'buy' => $price->buy,
                    ];
                }
            }
        }
        return $this->products;
    }

    public function getDiscountProducts($providers,$client)
    {
        foreach ($providers as $provider) {
            $types = ProductType::where('type', '!=', 'foreign')->get();
            foreach ($types as $type) {
                $sizes = Size::all();
                foreach ($sizes as $size) {
                    $product = $size->products()->where([
                        ['partner_id', $provider->id], ['type_id', $type->id],
                    ])->first();
                    $remise = Remise::where([
                        'product_id'        => $product->id,
                        'partner_id'        => $client->id
                    ])->first();
                    $this->products[$provider->name][$type->type][$size->size] = [
                        'remise_id'   => $remise->id,
                        'remise'    => $remise->remise,
                    ];
                }
            }
        }
        return $this->products;
    }

}