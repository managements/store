<?php

namespace App\Storage;


use App\Account;
use App\AccountDetail;
use App\AccountType;
use App\Order;
use App\Partner;
use App\Payment;
use App\Price;
use App\Product;
use App\Remise;
use App\Stock;
use App\Trade;

class TradeStorage
{
    private $ht = 0;
    private $tva = 0;
    private $ttc = 0;
    private $trades = [];
    private $buyConsign = 0;
    private $saleConsign = 0;
    private $saleGaz = 0;

    public function trade(array $data)
    {
        // if isset buy - sale
        $this->validation($data);
        // sale
        $this->sale($data);
        // buy
        $this->buy($data);
        // payment
        $this->payment($data);
    }

    private function payment(array $data)
    {
        $cheque = Payment::create([
            'price'         => $data['payments']['cheque']['price'],
            'operation'     => $data['payments']['cheque']['operation'] ,
            'mode_id'       => 2
        ]);
        $transfer = Payment::create([
            'price'         => $data['payments']['transfer']['price'],
            'operation'     => $data['payments']['transfer']['operation'] ,
            'mode_id'       => 3
        ]);
        $cash = Payment::create([
            'price'         => $data['payments']['cash']['price'],
            'mode_id'       => 1
        ]);
        $term = Payment::create([
            'price'         => $data['payments']['term']['price'],
            'mode_id'       => 4
        ]);

        foreach ($this->trades as $trade) {
            $term->trades()->attach($trade);
            $cash->trades()->attach($trade);
            $transfer->trades()->attach($trade);
            $cheque->trades()->attach($trade);
       }
       // todo::account
    }

    private function createTrade(array $data,?bool $sale = false)
    {
        $invoice = new InvoiceStorage();
        $create = array_merge(
            ['partner_id' => $data['partner']],
            ['creator_id' => auth()->id()]
        );
        if($sale) {
            $create = array_merge(
                $create,
                $invoice->increment()
            );
        }
        $trade = Trade::create($create);
        $this->trades[] = $trade->id;
        return $trade;
    }

    public function sale(array $data)
    {
        if ($this->saleGaz || $this->saleConsign) {
            // trade
            $trade = $this->createTrade($data,true);
            $this->ht = 0;
            $this->tva = 0;
            $this->ttc = 0;
            // sale gaz
            if ($this->saleGaz) {
                $this->saleProduct($data['sale']['gazes'],$data['partner']);
            }
            // sale consign
            if ($this->saleConsign) {
                $this->saleProduct($data['sale']['consignees'],$data['partner']);
            }
            // price Trade
            $trade->update([
                'ht'        => $this->ht,
                'tva'       => $this->tva,
                'ttc'       => $this->ttc
            ]);
        }
    }

    private function saleProduct(array $data,int $partner_id)
    {
        foreach ($data as $key => $gaze) {
            // create orders
            $this->saleOrder($key, $gaze,$partner_id);
            // sub stock gaz
            $stock = Stock::where([
                ['store_id', 1],
                ['product_id', $key]
            ])->first();
            $stock->update(['qt' => $stock->qt - $gaze]);
        }
    }

    private function saleOrder(int $product_id, int $qt,$client_id)
    {
        $product = Product::find($product_id);
        $price = $product->prices()->orderBy('id','desc')->first();
        $ht = $price->sale * $qt;
        $this->ht = $this->ht + $ht;
        $tva = $ht * $product->tva / 100;
        $this->tva = $this->tva + $tva;
        $ttc = $ht + $tva;
        $this->ttc = $this->ttc + $ttc;
        $order = Order::create([
            'qt'            => $qt,
            'ht'            => $ht,
            'tva'           => $tva,
            'ttc'           => $ttc,
            'product_id'    => $product_id
        ]);
        $this->saleAccount($order,$product,$price,$client_id);
        return $order;
    }

    private function saleAccount(Order $order,Product $product,Price $price,int $client_id)
    {
        // create account store
        $account = Account::where('account','store')->first();
        $account->details()->create([
            'label'     => "Vente Particulier " . $product->type->type,
            'detail'    => $product->size->size,
            'qt_out'    => $order->qt,
            'cr'        => $price->buy * $order->qt
        ]);
        // create account Client
        $client = Partner::find($client_id);
        $account = Account::where('account',$client->name)->first();
        $account->details()->create([
            'label'         => "Achat " . $order->product->type->type,
            'detail'        => $order->product->size->size,
            'qt_enter'      => $order->qt,
            'db'            => $order->ttc
        ]);
        // create account tva
        $account = Account::where('account','tva')->first();
        $detail_tva = $account->details()->create([
            'label'     => "Vente Particulier " . $order->product->type->type,
            'detail'    => $order->product->size->size,
            'cr'        => $order->tva
        ]);
        $order->account_details()->attach($detail_tva->id);
        // create account gain_loss
        $account = Account::where('account','gain_loss')->first();
        $buy = $order->product->prices()->orderBy('id','desc')->first();
        $account->details()->create([
            'label'     => "Vente Particulier " . $order->product->type->type,
            'detail'    => $order->product->size->size,
            'qt_out'    => $order->qt,
            'cr'        => $order->ht - ($order->qt * $buy->buy)
        ]);
        // discount
        $discount = Remise::where([
            ['product_id', $product->id],
            ['partner_id', $client_id]
        ])->first();
        if (!is_null($discount) && $discount->remise > 0) {
            $account->details()->create([
                'label'     => "Vente Particulier " . $order->product->type->type . ' ' . $order->product->size->size,
                'detail'    => "Réduction",
                'db'        => $order->ttc - ($discount->remise * $order->qt)
            ]);
        }
    }

    private function buyOrder(int $product_id, int $qt, int $client_id)
    {
        $product = Product::find($product_id);
        $price = $product->prices()->orderBy('id','desc')->first();
        $ht = $price->buy * $qt;
        $this->ht = $this->ht + $ht;
        $tva = $ht * $product->tva / 100;
        $this->tva = $this->tva + $tva;
        $ttc = $ht + $tva;
        $this->ttc = $this->ttc + $ttc;
        $order = Order::create([
            'qt'            => $qt,
            'ht'            => $ht,
            'tva'           => $tva,
            'ttc'           => $ttc,
            'product_id'    => $product_id
        ]);
        // todo::account details
        return $order;
    }

    private function buyProduct(array $data, int $partner_id)
    {
        foreach ($data as $key => $consign) {
            // create orders
            $this->buyOrder($key, $consign,$partner_id);
            // add stock
            $stock = Stock::where([
                ['store_id', 1],
                ['product_id', $key]
            ])->first();
            $stock->update(['qt' => $stock->qt + $consign]);
        }
    }

    private function buy(array $data)
    {
        // trade
        $trade = $this->createTrade($data);
        // buy consign
        if($this->buyConsign){
            $this->ht = 0;
            $this->tva = 0;
            $this->ttc = 0;
            $this->buyProduct($data['buy']['consignees'],$data['partner']);
            // price Trade
            $trade->update([
                'ht'        => $this->ht,
                'tva'       => $this->tva,
                'ttc'       => $this->ttc
            ]);
        }
    }

    private function validation(array $data)
    {
        foreach ($data['sale']['gazes'] as $key => $value) {
            if(!is_null($value)){
                $this->saleGaz = 1;
            }
        }
        foreach ($data['sale']['consignees'] as $key => $value) {
            if(!is_null($value)){
                $this->saleConsign = 1;
            }
        }
        foreach ($data['buy']['consignees'] as $key => $value) {
            if(!is_null($value)){
                $this->buyConsign = 1;
            }
        }
    }
}