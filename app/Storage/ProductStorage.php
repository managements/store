<?php

namespace App\Storage;


use App\ProductType;
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
                            ['partner_id', $provider->id],
                            ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id','desc')->first();

                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id,
                            'price'     => $price->buy,
                            'tva'       => $product->tva
                        ];
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
                            ['partner_id', $provider->id],
                            ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id','desc')->first();

                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product' => $product->id,
                            'price'     => $price->buy,
                            'tva'       => $product->tva
                        ];
                    }
                }
            }

        }
        return $this->products;
    }

    public function getGazOrderProducts($providers,$orders)
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
                            ['partner_id', $provider->id],
                            ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id','desc')->first();

                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product'   => $product->id,
                            'price'     => $price->buy,
                            'tva'       => $product->tva,
                            'qt'        => null
                        ];
                        foreach ($orders as $order) {
                            if($order->product_id === $product->id) {
                                $this->products[$provider->name][$type->type][$size->size] = [
                                    'product'   => $product->id,
                                    'price'     => $price->buy,
                                    'tva'       => $product->tva,
                                    'qt'        => $order->qt
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
                            ['partner_id', $provider->id],
                            ['type_id', $type->id],
                        ])->first();
                        // price
                        $price = $product->prices()->orderby('id','desc')->first();
                        $this->products[$provider->name][$type->type][$size->size] = [
                            'product'   => $product->id,
                            'price'     => $price->buy,
                            'tva'       => $product->tva,
                            'qt'        => null
                        ];
                        foreach ($orders as $order) {
                            if($order->product_id === $product->id) {
                                $this->products[$provider->name][$type->type][$size->size] = [
                                    'product'   => $product->id,
                                    'price'     => $price->buy,
                                    'tva'       => $product->tva,
                                    'qt'        => $order->qt
                                ];
                            }
                        }
                    }
                }

            }

        }
        return $this->products;
    }
}