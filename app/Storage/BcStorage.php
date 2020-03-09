<?php

namespace App\Storage;


use App\Account;
use App\Bc;
use App\Order;
use App\Partner;
use App\Price;
use App\Product;
use App\Stock;
use App\Trade;

class BcStorage
{
    private $ht = 0;
    private $tva = 0;
    private $ttc = 0;
    private $ordersProducts = [];
    private $ordersQts = [];

    public function add(array $data)
    {
        // trade
        $trade = $this->createTrade($data);
        // BC
        $bc = $this->createBc($data,$trade);
        // orders
        $this->orders($data['products'],$bc,$data['provider']);
        // stock
        $this->stock($data['provider']);
        // update Trade
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc
        ]);
        return $bc;
    }

    public function sub(Trade $trade)
    {
        $this->subOrders($trade->bc);
        $this->subStock($trade->partner_id);
        $trade->bc->delete();
        return $trade->delete();
    }

    private function createBc(array $data,Trade $trade) {
        return $trade->bc()->create(['nbr' => $data['nbr']]);
    }

    private function createTrade(array $data)
    {
        return  Trade::create([
            'ht'                    => 0,
            'tva'                   => 0,
            'ttc'                   => 0,
            'partner_id'            => $data['provider'],
            'intermediate_id'       => $data['intermediate'],
            'truck_id'              => $data['transporter'],
            'creator_id'            => auth()->id()
        ]);
    }

    private function subOrders(Bc $bc) {
        foreach ($bc->orders as $order) {
            $this->subAccount($bc->orders);
            $this->ordersProducts[] = $order->product;
            $this->ordersQts[] = $order->qt;
            // todo:: sub account
            $order->delete();
        }
    }


    private function orders(array $products,Bc $bc,int $provider_id) {
        $provider = Partner::find($provider_id);
        foreach ($products as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                $product = Product::find($product_id);
                $this->ordersProducts[] = $product;
                $this->ordersQts[] = $qt;
                $price = Price::where('product_id',$product_id)->orderby('id','desc')->first();
                $ht = $qt * $price->buy;
                $this->ht = $this->ht + $ht;
                $tva = $product->tva * $ht / 100;
                $this->tva = $this->tva + $tva;
                $ttc = $ht + $tva;
                $this->ttc = $this->ttc + $ttc;
                $order = $bc->orders()->create([
                    'qt'            => $qt,
                    'ht'            => $ht,
                    'tva'           => $tva,
                    'ttc'           => $ttc,
                    'product_id'    => $product_id,
                ]);
                // todo:: account [stock store - provider]
                $this->addAccount($order,$provider,$bc,$products);
            }
        }
    }

    private function addAccount(Order $order, Partner $provider,Bc $bc,array $products)
    {
        // provider
        if ($order->product->type->type === 'gaz') {
            $consign = Product::where([
                ['type_id', 2],
                ['size_id',$order->product->size_id],
                ['partner_id',$provider->id],
            ])->first();
            $qt_out = $order->qt - $products[$consign->id];
            $price = $consign->prices()->orderby('id','desc')->first();
            $consign_price = $price->buy * $qt_out;
            $account_store = Account::where('account','Stock Dépôt')->first();
            $store_details = $account_store->details()->create([
                'label'             => "BC N° " . $bc->nbr,
                'detail'            => "Achat de Consigne " . $order->product->size->size,
                'qt_enter',
                'qt_out'            => $qt_out,
                'db',
                'cr'                => $consign_price,
            ]);
            $order->account_details()->attach($store_details->id);
        }
        else {
            $account_provider = Account::where('account',$provider->name)->first();
            $provider_details = $account_provider->details()->create([
                'label'             => "BC N° " . $bc->nbr,
                'detail'            => "Achat Consigne " . $order->product->size->size,
                'qt_enter',
                'qt_out'            => $order->qt,
                'db',
                'cr'                => $order->ttc,
            ]);
            $order->account_details()->attach($provider_details->id);
        }
    }

    private function subAccount($orders)
    {
        foreach ($orders as $order) {
            foreach ($order->account_details as $account_detail) {
                $order->account_details()->detach($account_detail->id);
                $account_detail->delete();
            }
        }
    }

    private function stock(int $provider_id)
    {
        $this->stockConsign();
        $this->stockGaz($provider_id);
    }

    private function subStock(int $provider_id)
    {
        $this->subStockConsign();
        $this->subStockGaz($provider_id);
    }

    private function stockConsign() {
        foreach ($this->ordersProducts as $key => $product) {
            if ($product->type->type === 'consign') {
                // add to stock store consign
                $stock = Stock::where([
                    'store_id'      => 1,
                    'product_id'    => $product->id
                ])->first();
                $stock->update([
                    'qt'        => $stock->qt + $this->ordersQts[$key]
                ]);
            }
        }
    }

    private function subStockConsign() {
        foreach ($this->ordersProducts as $key => $product) {
            if ($product->type->type === 'consign') {
                // add to stock store consign
                $stock = Stock::where([
                    'store_id'      => 1,
                    'product_id'    => $product->id
                ])->first();
                $stock->update([
                    'qt'        => $stock->qt - $this->ordersQts[$key]
                ]);
            }
        }
    }

    private function stockGaz($provider_id) {
        foreach ($this->ordersProducts as $key => $product)  {
            if ($product->type->type === 'gaz') {
                // add to stock provider gaz
                $stock_provider_gaz = Stock::where([
                    'partner_id'   => $provider_id,
                    'product_id'    => $product->id
                ])->first();
                $stock_provider_gaz->update([
                    'qt'        => $stock_provider_gaz->qt + $this->ordersQts[$key]
                ]);
                // add to stock provider consign
                $consign = Product::where([
                    'size_id'       => $product->size_id,
                    'type_id'       => 2,
                    'partner_id'    => $provider_id
                ])->first();
                $stock_provider_consign = Stock::where([
                    'partner_id'        => $provider_id,
                    'product_id'        => $consign->id
                ])->first();
                $stock_provider_consign->update([
                    'qt'    => $stock_provider_consign->qt + $this->ordersQts[$key]
                ]);
                // sub to stock store consign
                $stock_store_consign = Stock::where([
                    'store_id'          => 1,
                    'product_id'        => $consign->id
                ])->first();
                $stock_store_consign->update([
                    'qt'    => $stock_store_consign->qt - $this->ordersQts[$key]
                ]);
            }
        }
    }

    private function subStockGaz($provider_id) {
        foreach ($this->ordersProducts as $key => $product)  {
            if ($product->type->type === 'gaz') {
                // sub to stock provider gaz
                $stock_provider_gaz = Stock::where([
                    'partner_id'   => $provider_id,
                    'product_id'    => $product->id
                ])->first();
                $stock_provider_gaz->update([
                    'qt'        => $stock_provider_gaz->qt - $this->ordersQts[$key]
                ]);
                // sub stock provider consign
                $consign = Product::where([
                    'size_id'       => $product->size_id,
                    'type_id'       => 2,
                    'partner_id'    => $provider_id
                ])->first();
                $stock_provider_consign = Stock::where([
                    'partner_id'        => $provider_id,
                    'product_id'        => $consign->id
                ])->first();
                $stock_provider_consign->update([
                    'qt'    => $stock_provider_consign->qt - $this->ordersQts[$key]
                ]);
                // add to stock store consign
                $stock_store_consign = Stock::where([
                    'store_id'          => 1,
                    'product_id'        => $consign->id
                ])->first();
                $stock_store_consign->update([
                    'qt'    => $stock_store_consign->qt + $this->ordersQts[$key]
                ]);
            }
        }
    }

}