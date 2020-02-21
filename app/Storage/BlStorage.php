<?php

namespace App\Storage;


use App\Bl;
use App\Payment;
use App\Price;
use App\Product;
use App\Stock;
use App\Trade;

class BlStorage
{
    private $ht = 0;
    private $tva = 0;
    private $ttc = 0;
    private $ordersProducts = [];
    private $ordersQts = [];

    public function add(array $data)
    {
        // create trade
        $trade = $this->createTrade($data);
        // create Bl
        $bl = $this->createBl($data,$trade);
        // create orders && stock
        $this->createOrders($data['products'],$bl,$data['provider']);
        // update Trade
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc
        ]);
        // create payment
        $this->createPayment($data,$trade);

        return $bl;
    }

    public function sub(Bl $bl)
    {
        $trade = $bl->trade;
        // orders and stock
        $this->subOrders($bl,$trade->partner_id);
        // payment
        $this->subPayments($trade);
        // trade
        $this->subTrade($bl);
        // bl
        return $this->subBl($bl);
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

    private function subTrade(Bl $bl)
    {
        return $bl->trade->delete();
    }

    public function createBl(array $data,Trade $trade)
    {
        return $trade->bl()->create([
            'nbr'       => $data['nbr']
        ]);
    }

    private function subBl(Bl $bl)
    {
        return $bl->delete();
    }

    private function createOrders(array $products,Bl $bl,int $provider_id) {
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
                $bl->orders()->create([
                    'qt'            => $qt,
                    'ht'            => $ht,
                    'tva'           => $tva,
                    'ttc'           => $ttc,
                    'product_id'    => $product_id,
                ]);
                $this->createStock($product,$provider_id,$qt);
            }
        }
    }

    private function subOrders(Bl $bl, int $provider_id)
    {
        foreach ($bl->orders as $order) {
            $this->subStock($order->product,$provider_id,$order);
        }
    }

    private function createStock($product, $provider_id,$qt) {
        // stock provider
        $consign = Product::where([
            ['type_id', 2],
            ['size_id', $product->size_id]
        ])->first();
        $stock_provider_gaz = Stock::where([
            ['product_id', $product->id],
            ['partner_id', $provider_id]
        ])->first();
        $stock_provider_gaz->update([
            'qt'    => $stock_provider_gaz->qt - $qt
        ]);
        $stock_provider_consign = Stock::where([
            ['product_id', $consign->id],
            ['partner_id', $provider_id]
        ])->first();
        $stock_provider_consign->update([
            'qt'    => $stock_provider_consign->qt - $qt
        ]);
        // stock store
        $stock_store_gaz = Stock::where([
            ['product_id', $product->id],
            ['store_id', 1]
        ])->first();
        $stock_store_gaz->update([
            'qt'        => $stock_store_gaz->qt + $qt
        ]);
        $stock_store_consign = Stock::where([
            ['product_id', $consign->id],
            ['store_id', 1]
        ])->first();
        $stock_store_consign->update([
            'qt'        => $stock_store_consign->qt + $qt
        ]);
    }

    private function subStock($product, $provider_id,$order)
    {
        // stock provider
        $consign = Product::where([
            ['type_id', 2],
            ['size_id', $product->size_id]
        ])->first();
        $stock_provider_gaz = Stock::where([
            ['product_id', $product->id],
            ['partner_id', $provider_id]
        ])->first();
        $stock_provider_gaz->update([
            'qt'    => $stock_provider_gaz->qt + $order->qt
        ]);
        $stock_provider_consign = Stock::where([
            ['product_id', $consign->id],
            ['partner_id', $provider_id]
        ])->first();
        $stock_provider_consign->update([
            'qt'    => $stock_provider_consign->qt + $order->qt
        ]);
        // stock store
        $stock_store_gaz = Stock::where([
            ['product_id', $product->id],
            ['store_id', 1]
        ])->first();
        $stock_store_gaz->update([
            'qt'        => $stock_store_gaz->qt - $order->qt
        ]);
        $stock_store_consign = Stock::where([
            ['product_id', $consign->id],
            ['store_id', 1]
        ])->first();
        $stock_store_consign->update([
            'qt'        => $stock_store_consign->qt - $order->qt
        ]);
    }
    
    private function createPayment(array $data, Trade $trade)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                $payment->trades()->attach($trade->id);
            }
        }
    }

    private function subPayments(Trade $trade)
    {
        foreach ($trade->payments as $payment) {
            $trade->payments()->delete();
            $trade->payments()->detach($payment->id);
        }
    }

}