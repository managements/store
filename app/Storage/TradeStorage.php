<?php

namespace App\Storage;


use App\Account;
use App\Order;
use App\Partner;
use App\Payment;
use App\Price;
use App\Product;
use App\Remise;
use App\Stock;
use App\Trade;
use App\Transaction;
use Carbon\Carbon;

class TradeStorage
{
    private $ht = 0;
    private $tva = 0;
    private $ttc = 0;
    private $trades = [];
    private $buyConsign = 0;
    private $saleConsign = 0;
    private $saleGaz = 0;
    private $tradeSale = null;
    private $tradeBuy = null;
    private $slug_inv = null;
    private $inv = null;
    private $created_at = null;

    private function validate(array $data)
    {
        foreach ($data['products']['sale']['gaz'] as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                $this->saleGaz = 1;
            }
        }
        foreach ($data['products']['sale']['consign'] as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                $this->saleConsign = 1;
            }
        }
        foreach ($data['products']['buy']['consign'] as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                $this->buyConsign = 1;
            }
        }
    }

    public function add(array $data)
    {
        $this->validate($data);
        if ($this->saleGaz || $this->saleConsign || $this->buyConsign){
            if($this->saleGaz || $this->saleConsign) {
                $this->addSale($data);
            }
            if ($this->buyConsign) {
                $this->addBuy($data);
            }
            $this->addPayments($data);
            return $this->addTransaction();
        }
        return null;
    }

    private function addTransaction()
    {
        return Transaction::create([
            'sale_id'       => $this->tradeSale,
            'buy_id'        => $this->tradeBuy
        ]);
    }

    private function addSale(array $data)
    {
        $trade = $this->addTrade($data,true);
        $this->addSaleOrders($data['products']['sale']['consign'],$trade);
        $this->addSaleOrders($data['products']['sale']['gaz'],$trade);
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc,
        ]);
    }

    private function addBuy(array $data)
    {
        $trade = $this->addTrade($data);
        $this->addBuyOrders($data['products']['buy']['consign'],$trade);
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc,
        ]);
    }

    private function inv()
    {
        if (!$this->inv) {
            $invoice = new InvoiceStorage();
            $slug = $invoice->increment();
            $this->inv = $slug['inv'];
            $this->slug_inv = $slug['slug_inv'];
        }
    }

    private function addTrade(array $data, $sale = null)
    {
        $this->tradeZero();
        $inv = null;
        $slug_inv = null;
        if (!$sale) {
            $this->inv();
            $inv = $this->inv;
            $slug_inv = $this->slug_inv;
        }
        $trade = Trade::create([
            'slug_inv'              => $slug_inv,
            'inv'                   => $inv,
            'ht'                    => 0,
            'tva'                   => 0,
            'ttc'                   => 0,
            'partner_id'            => $data['partner'],
            'creator_id'            => auth()->id(),
            'created_at'            => ($this->created_at) ? $this->created_at : now()
        ]);
        if ($sale) {
            $this->tradeSale = $trade->id;
        }
        else{
            $this->tradeBuy = $trade->id;
        }
        $this->trades[] = $trade->id;
        return $trade;
    }

    private function tradeZero()
    {
        $this->ht = 0;
        $this->tva = 0;
        $this->ttc = 0;
    }

    private function addSaleOrders(array $data,Trade $trade)
    {
        foreach ($data as $size => $products) {
            foreach ($products as $product_id => $qt) {
                if (!is_null($qt) && $qt > 0) {
                    $product = Product::find($product_id);
                    $price = $product->prices()->orderby('id','desc')->first();
                    $ht = $qt * $price->sale;
                    $tva = $ht * $product->tva / 100;
                    $ttc = $ht + $tva;
                    $this->ht = $this->ht + $ht;
                    $this->tva = $this->tva + $tva;
                    $this->ttc = $this->ttc + $ttc;
                    $product->orders()->create([
                        'qt'        => $qt,
                        'ht'        => $ht,
                        'tva'       => $tva,
                        'ttc'       => $ttc,
                        'trade_id'  => $trade->id
                    ]);
                    $stock = Stock::where([
                        ['store_id', 1],
                        ['product_id', $product_id],
                    ])->first();
                    $stock->update([
                        'qt'    => $stock->qt - $qt
                    ]);
                }
            }
        }
    }

    private function addBuyOrders(array $data,Trade $trade)
    {
        foreach ($data as $size => $products) {
            foreach ($products as $product_id => $qt) {
                if (!is_null($qt) && $qt > 0) {
                    if (!is_null($qt) && $qt > 0) {
                        $product = Product::find($product_id);
                        $price = $product->prices()->orderby('id', 'desc')->first();
                        $ht = $qt * $price->buy;
                        $tva = $ht * $product->tva / 100;
                        $ttc = $ht + $tva;
                        $this->ht = $this->ht + $ht;
                        $this->tva = $this->tva + $tva;
                        $this->ttc = $this->ttc + $ttc;
                        $product->orders()->create([
                            'qt' => $qt, 'ht' => $ht, 'tva' => $tva, 'ttc' => $ttc, 'trade_id' => $trade->id
                        ]);
                        $stock = Stock::where([
                            ['store_id', 1], ['product_id', $product_id],
                        ])->first();
                        $stock->update([
                            'qt' => $stock->qt + $qt
                        ]);
                    }
                }
            }
        }
    }

    private function addPayments(array $data)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                foreach ($this->trades as $trade) {
                    $payment->trades()->attach($trade);
                }
            }
        }
    }

    public function updated(array $data,Transaction $transaction)
    {
        $sale = $transaction->sale;
        $buy = $transaction->buy;
        if ($sale) {
            // payments
            $this->subPayments($sale);
            $this->subSale($sale);
        }
        else{
            // payments
            $this->subPayments($sale);
        }
        if ($buy) {
            $this->subBuy($buy);
        }
        $transaction->delete();
        return $this->add($data);
    }

    private function subBuy(Trade $trade)
    {
        $this->subBuyOrders($trade->orders);
        $this->slug_inv = $trade->slug_inv;
        $this->inv = $trade->inv;
        $this->created_at = $trade->created_at;
        $trade->delete();
    }

    private function subBuyOrders($orders)
    {
        foreach ($orders as $order) {
            $stock = Stock::where([
                ['store_id', 1], ['product_id', $order->product_id],
            ])->first();
            $stock->update([
                'qt' => $stock->qt - $order->qt
            ]);
            $order->delete();
        }
    }

    private function subSale(Trade $trade)
    {
        $this->subSaleOrders($trade->orders);
        $trade->delete();
    }

    private function subSaleOrders($orders)
    {
        foreach ($orders as $order) {
            $stock = Stock::where([
                ['store_id', 1],
                ['product_id', $order->product_id],
            ])->first();
            $stock->update([
                'qt'    => $stock->qt + $order->qt
            ]);
            $order->delete();
        }
    }

    private function subPayments(Trade $trade)
    {
        foreach ($trade->payments as $payment) {
            $trade->payments()->detach($payment->id);
            $payment->delete();
        }
    }
}