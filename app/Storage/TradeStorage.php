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
    private $data = [
        'payments'      => [
            'cash'      => '480',
            'cheque'    => ['price' => '480','operation' => '123456'],
            'transfer'  => ['price' => '480','operation' => '123856']
        ],
        'provider'      => 1,
        'buy'           => [
            'consignees'    => [
                'consign_provider' => [9 => 10]
            ],
        ],
        'sale'          => [
            'gazes'         => [
                'gaze_provider' => [1 => 10]
            ],
            'consignees'    => [
                'consign_provider' => [6 => 10]
            ],
        ],
    ];
    public function trade(array $data)
    {
        $this->payment($data);
        // sale
        $this->sale($data);
        // buy
        // payment

    }

    private function payment(array $data)
    {
        $cheque = Payment::create([
            'price'         => $data['payments']['cheque']['price'],
            'operation'     => $data['payments']['cheque']['operation'] ,
            'mode_id'       => 2
        ]);
        $cheque->trades()->attach($this->trades);
        $account_cheque = Account::where('account', 'cheque')->first();
        /*
        $account_cheque->details()->create([
            'label'         => "",
            'detail',
            'db'            => $cheque->price,
        ]);
        */
        $transfer = Payment::create([
            'price'         => $data['payments']['transfer']['price'],
            'operation'     => $data['payments']['transfer']['operation'] ,
            'mode_id'       => 3
        ]);
        $transfer->trades()->attach($this->trades);
        $cash = Payment::create([
            'price'         => $data['payments']['cash']['price'],
            'mode_id'       => 1
        ]);
        $cash->trades()->attach($this->trades);
        $term = Payment::create([
            'price'         => $data['payments']['term']['price'],
            'mode_id'       => 4
        ]);
       $term->trades()->attach($this->trades);
       // account
        
    }
    private function createTrade(array $data)
    {
        // trade
        $invoice = new InvoiceStorage();
        $create = array_merge($invoice->increment(),['partner_id' => $data['partner']],['creator_id' => auth()->id()]);
        $trade = Trade::create($create);
        $this->trades[] = $trade;
        return $trade;
    }

    public function sale(array $data)
    {
        // trade
        $trade = $this->createTrade($data);
        // sale gaz
        $this->saleProduct($data['sale']['gazes'],$data['partner']);
        // sale consign
        $this->saleProduct($data['sale']['consignees'],$data['partner']);
        // price Trade
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc
        ]);
        // payment
        // cheque - cash - transfer
    }

    private function saleProduct($data,int $partner_id)
    {
        foreach ($data as $key => $gaze) {
            // create orders
            $order = $this->order($key, $gaze,$partner_id);
            // sub stock gaz
            $stock = Stock::where([
                ['store_id', 1],
                ['product_id', $key]
            ])->first();
            $stock->update(['qt' => $stock->qt - $gaze]);
            // create account store
            $account = Account::where('account','store')->first();
            $account->details()->create([
                'label'     => "Vente Particulier " . $order->product->type->type,
                'detail'    => $order->product->size->size,
                'qt_out'    => $order->qt,
                'cr'        => $order->ttc
            ]);
/*
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
            $gain = $order->ht - ($order->qt * $buy->buy);
            if($gain > 0) {
                $account->details()->create([
                    'label'     => "Vente Particulier " . $order->product->type->type,
                    'detail'    => $order->product->size->size,
                    'qt_out'    => $order->qt,
                    'cr'        => $gain
                ]);
            }
            else {
                $account->details()->create([
                    'label'     => "Vente Particulier  " . $order->product->type->type,
                    'detail'    => $order->product->size->size,
                    'qt_out'    => $order->qt,
                    'db'        => $gain
                ]);
            }
            // create account Client
            $client = Partner::find($partner_id);
            $account = Account::where('account',$client->name)->first();
            $account->details()->create([
                'label'         => "Achat " . $order->product->type->type,
                'detail'        => $order->product->size->size,
                'qt_enter'      => $order->qt,
                'cr'            => $order->ttc
            ]);
*/
        }
    }

    private function order(int $product_id, int $qt,$client_id)
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

    private function buy(array $data)
    {
        // trade
        // buy consign
        // price Trade
    }
}