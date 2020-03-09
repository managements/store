<?php

namespace App\Storage;


use App\Account;
use App\Bl;
use App\Order;
use App\Partner;
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
        // provider
        $provider = Partner::find($data['provider']);
        // create trade
        $trade = $this->createTrade($data);
        // create Bl
        $bl = $this->createBl($data,$trade);
        // create orders && stock
        $this->createOrders($data['products'],$bl,$provider);
        // update Trade
        $trade->update([
            'ht'        => $this->ht,
            'tva'       => $this->tva,
            'ttc'       => $this->ttc
        ]);
        // create payment
        $this->createPayment($data,$trade,$provider,$bl);

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

    private function createOrders(array $products,Bl $bl,Partner $provider) {
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
                $order = $bl->orders()->create([
                    'qt'            => $qt,
                    'ht'            => $ht,
                    'tva'           => $tva,
                    'ttc'           => $ttc,
                    'product_id'    => $product_id,
                ]);
                $this->createStock($product,$provider->id,$qt,$order,$provider,$bl);
            }
        }
    }

    private function subOrders(Bl $bl, int $provider_id)
    {
        $provider = Partner::find($provider_id);
        foreach ($bl->orders as $order) {
            $this->subStock($order->product,$provider_id,$order);
            $this->subAccountStore($order,$provider);
        }
    }

    private function createStock($product, $provider_id,$qt,Order $order,Partner $provider,Bl $bl) {
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
        $this->addAccountStore($consign,$order,$provider,$bl);
    }

    private function addAccountStore(Product $consign,Order $order,Partner $provider,Bl $bl)
    {
        // price gaz
        $price_gaz = $order->ht;
        // price consign
        $price = $consign->prices()->orderby('id','desc')->first();
        $price_consign = $price->buy * $order->qt;
        // account_store
        $order->account_details()->create([
            'label'             => "BL N° " . $bl->nbr,
            'detail'            => "Achat Gaze " . $order->product->size->size,
            'qt_enter'          => $order->qt,
            'db'                => $price_gaz,
            'account_id'        => Account::where('account','stock Dépôt')->first()->id
        ]);
        $order->account_details()->create([
            'label'             => "BL N° " . $bl->nbr,
            'detail'            => "Achat Consigne " . $order->product->size->size,
            'qt_enter'          => $order->qt,
            'db'                => $price_consign,
            'account_id'        => Account::where('account','stock Dépôt')->first()->id
        ]);
        // account_provider
        $order->account_details()->create([
            'label'             => "BL N° " . $bl->nbr,
            'detail'            => "Achat Gaze " . $order->product->size->size,
            'qt_out'            => $order->qt,
            'cr'                => $order->ttc,
            'account_id'        => Account::where('account',$provider->name)->first()->id
        ]);
        // account_tva
        $order->account_details()->create([
            'label'             => "BL N° " . $bl->nbr,
            'detail'            => "Achat Gaze " . $order->product->size->size,
            'db'                => $order->tva,
            'account_id'        => Account::where('account','tva')->first()->id
        ]);
    }

    public function subAccountStore(Order $order, Partner $provider)
    {
        // Dépôt
        $store_account = Account::where('account','stock Dépôt')->first();
        $account_store_details = $order->account_details()
            ->where('account_id', $store_account->id)
            ->get();
        foreach ($account_store_details as $store_account_detail) {
            $store_account_detail->delete();
        }
        // Provider
        $account_provider = $order->account_details()
            ->where('account_id',Account::where('account',$provider->name)->first()->id)
            ->first();
        $account_provider->delete();
        // tva
        $account_tva = $order->account_details()
            ->where('account_id',Account::where('account','tva')->first()->id)
            ->first();
        $account_tva->delete();
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
    
    private function createPayment(array $data, Trade $trade,Partner $provider, Bl $bl)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                $payment->trades()->attach($trade->id);
                $this->addAccountPayment($provider,$bl,$trade,$payment);
            }
        }
    }

    private function addAccountPayment(Partner $partner,Bl $bl,Trade $trade,Payment $payment)
    {
        // cheque emitted
        if ($payment->mode_id == 2) {
            $trade->account_details()->create([
                'label'         => "BL N° " . $bl->nbr,
                'detail'        => "Chéque N° " . $payment->operation,
                'db',
                'cr'            => $payment->price,
                'account_id'    => Account::where('account', 'cheque_emitted')->first()->id
            ]);
            $trade->account_details()->create([
                'label'             => "BL N° " . $bl->nbr,
                'detail'            => "Chéque N° " . $payment->operation,
                'db'                => $payment->price,
                'account_id'        => Account::where('account',$partner->name)->first()->id
            ]);
        }
        // transfer emitted
        elseif ($payment->mode_id == 3) {
            $trade->account_details()->create([
                'label'         => "BL N° " . $bl->nbr,
                'detail'        => "Virement N° " . $payment->operation,
                'db',
                'cr'            => $payment->price,
                'account_id'    => Account::where('account', 'transfer_emitted')->first()->id
            ]);
            $trade->account_details()->create([
                'label'             => "BL N° " . $bl->nbr,
                'detail'            => "Virement N° " . $payment->operation,
                'db'                => $payment->price,
                'account_id'        => Account::where('account',$partner->name)->first()->id
            ]);
        }
        // term emitted
        elseif ($payment->mode_id == 4) {
            $trade->account_details()->create([
                'label'         => "BL N° " . $bl->nbr,
                'detail'        => $partner->name,
                'cr'            => $payment->price,
                'account_id'    => Account::where('account', 'term_emitted')->first()->id
            ]);
        }
    }

    private function subAccountPayment(Trade $trade)
    {
        foreach ($trade->account_details as $account_detail) {
            $account_detail->delete();
        }
    }

    private function subPayments(Trade $trade)
    {
        foreach ($trade->payments as $payment) {
            $trade->payments()->delete();
            $trade->payments()->detach($payment->id);
        }
        $this->subAccountPayment($trade);
    }

}