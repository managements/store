<?php

namespace App\Storage;

use App\Account;
use App\Order;
use App\Partner;
use App\Payment;
use App\Price;
use App\Product;
use App\Stock;
use App\Trade;
use App\Transaction;

class TradeStorage
{
    private $saleConsign;
    private $saleGaz;
    private $buyConsign;
    private $sale;
    private $buy;
    private $saleHt = 0;
    private $saleTva = 0;
    private $saleTtc = 0;
    private $buyHt = 0;
    private $buyTva = 0;
    private $buyTtc = 0;
    private $trades = [];
    private $payments = [];
    private $slug;
    private $inv;
    private $created_at;
    private $client_account;

    public function add(array $data)
    {
        $this->validate($data);
        if ($this->saleGaz || $this->saleConsign || $this->buyConsign){
            $partner = Partner::find($data['partner']);
            if($this->saleGaz || $this->saleConsign) {
                $this->addSale($data,$partner);
            }
            if ($this->buyConsign) {
                $this->addBuy($data,$partner);
            }

            $this->addPayments($data,$partner);

            return $this->addTransaction();
        }
        return null;
    }


    /**
     * Verifier si il ya une Achat de Gaz Achat Consigne Vente Consigne
     * @param array $data
     */
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

    private function addSale(array $data,Partner $partner)
    {
        $trade = $this->addTrade($data,true);
        $this->addSaleOrders($data['products']['sale']['consign'],$trade,$partner);
        $this->addSaleOrders($data['products']['sale']['gaz'],$trade,$partner);
        $trade->update([
            'ht'        => $this->saleHt,
            'tva'       => $this->saleTva,
            'ttc'       => $this->saleTtc,
        ]);
        return $trade;
    }

    private function addTrade(array $data, $sale = null)
    {
        $inv = null;
        $slug_inv = null;
        if ($sale) {
            $this->inv();
            $inv = $this->inv;
            $slug_inv = $this->slug;
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
            $this->sale = $trade;
        }
        else{
            $this->buy = $trade;
        }
        $this->trades[] = $trade;
        return $trade;
    }

    private function inv()
    {
        if (!$this->inv) {
            $invoice = new InvoiceStorage();
            $slug = $invoice->increment();
            $this->inv = $slug['inv'];
            $this->slug = $slug['slug_inv'];
        }
    }

    private function addSaleOrders(array $data,Trade $trade, Partner $partner)
    {
        foreach ($data as $size => $products) {
            foreach ($products as $product_id => $qt) {
                if (!is_null($qt) && $qt > 0) {
                    $product = Product::find($product_id);
                    $price = $product->prices()->orderby('id','desc')->first();
                    $ht = $qt * $price->sale;
                    $tva = $ht * $product->tva / 100;
                    $ttc = $ht + $tva;
                    $this->saleHt = $this->ht + $ht;
                    $this->saleTva = $this->saleTva + $tva;
                    $this->saleTtc = $this->saleTtc + $ttc;
                    // orders
                    $order = $product->orders()->create([
                        'qt'            => $qt,
                        'ht'            => $ht,
                        'tva'           => $tva,
                        'ttc'           => $ttc,
                        'trade_id'      => $trade->id,
                        'created_at'    => ($this->created_at) ? $this->created_at : now()
                    ]);
                    // stock
                    $this->addSaleStock($product_id,$order);
                    // account
                    $this->AddSaleAccounts($order,$price,$partner);
                }
            }
        }
    }

    private function addSaleStock(int $product_id, Order $order)
    {
        $stock = Stock::where([
            ['store_id', 1],
            ['product_id', $product_id],
        ])->first();
        $stock->update([
            'qt'    => $stock->qt - $order->qt
        ]);
    }

    private function AddSaleAccounts(Order $order, Price $price,Partner $partner)
    {
        // stock dépôt
        if ($order->product->type->type === 'consign') {
            $label = "Vente Consigne";
        }
        else {
            $label = "Vente Gaze";
        }
        $price_buy = $order->qt * $price->buy;
        $gain = $order->ht - $price_buy;
        // compte Dépôt
        $order->account_details()->create([
            'label'         => $label,
            'detail'        => $order->product->size->zize,
            'qt_enter',
            'qt_out'        => $order->qt,
            'db',
            'cr'            => $price_buy,
            'account_id'    => Account::where('account','Stock Dépôt')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // compte client
        $order->account_details()->create([
            'label'         => $label,
            'detail'        => $order->product->size->zize,
            'qt_enter'      => $order->qt,
            'qt_out',
            'db'            => $order->ttc,
            'cr',
            'account_id'    => Account::where('account','Particular')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // compte tva
        $order->account_details()->create([
            'label'         => $label . " " . $order->product->size->zize,
            'detail'        => $partner->name,
            'qt_enter',
            'qt_out',
            'db',
            'cr'            => $order->tva,
            'account_id'    => Account::where('account','tva')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // compte gain_loss
        if ($gain != 0) {
            $order->account_details()->create([
                'label'         => $label . " " . $order->product->size->zize,
                'detail'        => $partner->name,
                'qt_enter',
                'qt_out'        => $order->qt,
                'db'            => ($gain < 0) ? $gain : null,
                'cr'            => ($gain > 0) ? $gain : null,
                'account_id'    => Account::where('account','gain_loss')->first()->id,
                'created_at'    => ($this->created_at) ? $this->created_at : now()
            ]);
        }
    }

    private function addBuy(array $data,Partner $partner)
    {
        $trade = $this->addTrade($data);
        $this->addBuyOrders($data['products']['buy']['consign'],$trade,$partner);
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc,
        ]);
    }

    private function addBuyOrders(array $data,Trade $trade,Partner $partner)
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
                        $this->buyHt = $this->buyHt + $ht;
                        $this->buyTva = $this->buyTva + $tva;
                        $this->buyTtc = $this->buyTtc + $ttc;
                        $order = $product->orders()->create([
                            'qt' => $qt,
                            'ht' => $ht,
                            'tva' => $tva,
                            'ttc' => $ttc,
                            'trade_id' => $trade->id,
                            'created_at'    => ($this->created_at) ? $this->created_at : now()
                        ]);
                        // stock
                        $this->addBuyStock($product_id,$order);
                        $this->addAccountBuy($order,$price, $partner);
                    }
                }
            }
        }
    }

    private function addBuyStock(int $product_id, Order $order)
    {
        $stock = Stock::where([
            ['store_id', 1], ['product_id', $product_id],
        ])->first();
        $stock->update([
            'qt' => $stock->qt + $order->qt
        ]);
    }

    private function addAccountBuy(Order $order,Price $price, Partner $partner)
    {
        // compte tva
        $order->account_details()->create([
            'label'         => "Achat Consigne " . $order->product->size->zize,
            'detail'        => $partner->name,
            'qt_enter',
            'qt_out',
            'db'            => $order->tva,
            'cr',
            'account_id'    => Account::where('account','tva')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // compte client
        $order->account_details()->create([
            'label'         => "Achat Consigne",
            'detail'        => $order->product->size->zize,
            'qt_enter'      => $order->qt,
            'qt_out',
            'db',
            'cr'            => $order->ttc,
            'account_id'    => Account::where('account','Particular')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // compte Dépôt
        $order->account_details()->create([
            'label'         => "Achat Consigne",
            'detail'        => $order->product->size->zize,
            'qt_enter',
            'qt_out'        => $order->qt,
            'db'            => $order->qt * $price->buy,
            'cr',
            'account_id'    => Account::where('account','Stock Dépôt')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
    }

    private function addPayments(array $data,Partner $partner)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                foreach ($this->trades as $trade) {
                    $payment->trades()->attach($trade->id);
                }
                $this->payments[] = $payment;
                $this->addAccountPayments($partner,$payment);
            }
        }
    }

    private function addAccountPayments(Partner $partner,Payment $payment)
    {
        // compte client
        $payment->account_details()->create([
            'label'         => "Achat Vente",
            'detail'        => $payment->mode->mode,
            'qt_enter',
            'qt_out',
            'db'            => $this->transaction($payment->ttc)['cr'],
            'cr'            => $this->transaction($payment->ttc)['db'],
            'account_id'    => Account::where('account','Particular')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
        // caisse
        $payment->account_details()->create([
            'label'         => "Achat Vente",
            'detail'        => $partner->name,
            'qt_enter',
            'qt_out',
            'db'            => $this->transaction($payment->price)['db'],
            'cr'            => $this->transaction($payment->price)['cr'],
            'account_id'    => Account::where('account','Caisse Dépôt')->first()->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
    }

    private function transaction(int $ttc)
    {
        // caisse dépôt
        $transaction = $this->saleTtc - $this->buyTtc;
        if ($transaction > 0) {
            return [
                'db'        => $ttc,
                'cr'        => null
            ];
        }
        return [
            'db'        => null,
            'cr'        => $ttc
        ];
    }

    private function addTransaction()
    {
        return Transaction::create([
            'sale_id'       => $this->sale->id,
            'buy_id'        => $this->buy->id,
            'created_at'    => ($this->created_at) ? $this->created_at : now()
        ]);
    }

    public function updated(array $data,Transaction $transaction)
    {
        $sale = $transaction->sale;
        $buy = $transaction->buy;
        $this->created_at = $transaction->created_at;
        if ($sale) {
            // payments
            $this->slug = $sale->slug_inv;
            $this->inv = $sale->inv;
            $this->subPayments($sale);
            $this->subSale($sale);
        }
        if ($buy) {
            $this->subBuy($buy);
            $this->subPayments($buy);
        }
        $transaction->delete();
        $this->sale = null;
        $this->buy = null;
        return $this->add($data);
    }

    private function subSale(Trade $trade)
    {
        $this->subSaleOrders($trade->orders);
        $trade->delete();
    }

    private function subSaleOrders($orders)
    {
        foreach ($orders as $order) {
            $this->subSaleStock($order);
            $this->subAccount($order);
            $order->delete();
        }
    }

    private function subSaleStock(Order $order)
    {
        $stock = Stock::where([
            ['store_id', 1],
            ['product_id', $order->product_id],
        ])->first();
        $stock->update([
            'qt'    => $stock->qt + $order->qt
        ]);
    }

    private function subPayments(Trade $trade)
    {
        foreach ($trade->payments as $payment) {
            $trade->payments()->detach($payment->id);
            $this->subAccountPayments($payment);
            $payment->delete();
        }
    }

    private function subAccountPayments(Payment $payment)
    {
        foreach ($payment->account_details as $account_detail) {
            $account_detail->delete();
        }
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
            $this->subAccount($order);
            $order->delete();
        }
    }

    private function subAccount(Order $order)
    {
        foreach ($order->account_details as $detail) {
            $order->account_details()->detach($detail->id);
            $detail->delete();
        }
    }

    /*
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
    private $payments = [];

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
            $partner = Partner::find($data['partner']);
            if($this->saleGaz || $this->saleConsign) {
                $this->addSale($data,$partner);
            }
            if ($this->buyConsign) {
                $this->addBuy($data,$partner);
            }
            $this->addPayments($data,$partner);
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

    private function addSale(array $data,Partner $partner)
    {
        $trade = $this->addTrade($data,true);
        $this->addSaleOrders($data['products']['sale']['consign'],$trade,$partner);
        $this->addSaleOrders($data['products']['sale']['gaz'],$trade,$partner);
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc,
        ]);
        return $trade;
    }

    private function addBuy(array $data,Partner $partner)
    {
        $trade = $this->addTrade($data);
        $this->addBuyOrders($data['products']['buy']['consign'],$trade,$partner);
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
        $this->trades[] = $trade;
        return $trade;
    }

    private function tradeZero()
    {
        $this->ht = 0;
        $this->tva = 0;
        $this->ttc = 0;
    }

    private function addSaleOrders(array $data,Trade $trade, Partner $partner)
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
                    // orders
                    $order = $product->orders()->create([
                        'qt'        => $qt,
                        'ht'        => $ht,
                        'tva'       => $tva,
                        'ttc'       => $ttc,
                        'trade_id'  => $trade->id
                    ]);
                    // stock
                    $stock = Stock::where([
                        ['store_id', 1],
                        ['product_id', $product_id],
                    ])->first();
                    $stock->update([
                        'qt'    => $stock->qt - $qt
                    ]);
                    // account
                    $this->addAccountSale($order,$price,$partner);
                }
            }
        }
    }

    private function addAccountSale(Order $order, Price $price,Partner $partner)
    {
        // stock dépôt
        if ($order->product->type->type === 'consign') {
            $label = "Vente Consigne";
        }
        else {
            $label = "Vente Gaze";
        }
        $price_buy = $order->qt * $price->buy;
        $gain = $order->ht - $price_buy;
        // compte Dépôt
        $order->account_details()->create([
            'label'         => $label,
            'detail'        => $order->product->size->zize,
            'qt_enter',
            'qt_out'        => $order->qt,
            'db',
            'cr'            => $price_buy,
            'account_id'    => Account::where('account','Stock Dépôt')->first()->id
        ]);
        // compte client
        $order->account_details()->create([
            'label'         => $label,
            'detail'        => $order->product->size->zize,
            'qt_enter'      => $order->qt,
            'qt_out',
            'db'            => $order->ttc,
            'cr',
            'account_id'    => Account::where('account','Particular')->first()->id
        ]);
        // compte tva
        $order->account_details()->create([
            'label'         => $label . " " . $order->product->size->zize,
            'detail'        => $partner->name,
            'qt_enter',
            'qt_out',
            'db',
            'cr'            => $order->tva,
            'account_id'    => Account::where('account','tva')->first()->id
        ]);
        // compte gain_loss
        if ($gain != 0) {
            $order->account_details()->create([
                'label'         => $label . " " . $order->product->size->zize,
                'detail'        => $partner->name,
                'qt_enter',
                'qt_out'        => $order->qt,
                'db'            => ($gain < 0) ? $gain : null,
                'cr'            => ($gain > 0) ? $gain : null,
                'account_id'    => Account::where('account','gain_loss')->first()->id
            ]);
        }
    }

    private function addAccountBuy(Order $order,Price $price, Partner $partner)
    {
        // compte tva
        $order->account_details()->create([
            'label'         => "Vente Consigne " . $order->product->size->zize,
            'detail'        => $partner->name,
            'qt_enter',
            'qt_out',
            'db'            => $order->tva,
            'cr',
            'account_id'    => Account::where('account','tva')->first()->id
        ]);
        // compte client
        $order->account_details()->create([
            'label'         => "Vente Consigne",
            'detail'        => $order->product->size->zize,
            'qt_enter'      => $order->qt,
            'qt_out',
            'db',
            'cr'            => $order->ttc,
            'account_id'    => Account::where('account','Particular')->first()->id
        ]);
        // compte Dépôt
        $order->account_details()->create([
            'label'         => "Vente Consigne",
            'detail'        => $order->product->size->zize,
            'qt_enter',
            'qt_out'        => $order->qt,
            'db'            => $order->qt * $price->buy,
            'cr',
            'account_id'    => Account::where('account','Stock Dépôt')->first()->id
        ]);
    }

    private function addBuyOrders(array $data,Trade $trade,Partner $partner)
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
                        $order = $product->orders()->create([
                            'qt' => $qt, 'ht' => $ht, 'tva' => $tva, 'ttc' => $ttc, 'trade_id' => $trade->id
                        ]);
                        $stock = Stock::where([
                            ['store_id', 1], ['product_id', $product_id],
                        ])->first();
                        $stock->update([
                            'qt' => $stock->qt + $qt
                        ]);
                        $this->addAccountBuy($order,$price, $partner);
                    }
                }
            }
        }
    }

    private function addPayments(array $data,Partner $partner)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                foreach ($this->trades as $trade) {
                    $payment->trades()->attach($trade->id);
                }
                $this->payments[] = $payment;
                $this->addAccountPayments($partner,$payment);
            }
        }
    }

    private function addAccountPayments(Partner $partner,Payment $payment)
    {
        // client
        // a terme
        // a cheque_cashed
        // a $é
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
    */

}