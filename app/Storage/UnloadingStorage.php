<?php

namespace App\Storage;


use App\Account;
use App\Driver;
use App\Loading;
use App\Partner;
use App\Payment;
use App\Product;
use App\Stock;
use App\Tmp;

class UnloadingStorage
{
    /*
     * sub
     * SubLoading
     * subTmps
     * SubStock
     * SubAccount
     * SubAccountPayments
     * update
     */
    private $truck;
    private $unloading;
    private $created_at;
    private $provider;

    public function __construct()
    {
        $this->created_at = now();
    }

    public function add(array $data)
    {
        // unloading
        $this->addUnloading($data);
        // tmp and accountTmp
        $this->addTmp($data,$this->unloading);
        // payments and accountPayment
        $this->addPayments($data);
        return $this->unloading;
    }

    private function addUnloading(array $data)
    {
        $truck = $this->getTruck($data['driver']);
        if(is_null($this->unloading)) {
            $this->provider = Partner::find($data['provider']);
            $this->unloading = Loading::create([
                'unloading'     => 1,
                'valid'         => 1,
                'truck_id'      => $truck->id,
                'partner_id'    => $data['provider'],
                'created_at'    => $this->created_at
            ]);
        }
        else{
            $this->unloading->update([
                'unloading'     => 1,
                'valid'         => 1,
                'truck_id'      => $truck->id,
                'partner_id'    => $data['provider'],
            ]);

        }
    }

    private function getTruck(int $driver_id)
    {
        $driver = Driver::find($driver_id);
        $this->truck = $driver->truck;
        return $driver->truck;
    }

    private function addTmp(array $data,Loading $unloading)
    {
        foreach ($data['products'] as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                $product = Product::find($product_id);
                if ($product->type->type == "gaz") {
                    // remplie
                    $consign = Product::where([
                        ['type_id', $product->type_id],
                        ['size_id', $product->size_id],
                        ['partner_id', $product->partner_id],
                    ])->first();
                    $tmp = $unloading->tmps()->create([
                        'qt'            => $qt,
                        'product_id'    => $product_id
                    ]);
                    $this->addStock($product,$qt);
                    $this->addStock($consign,$qt);
                    $this->addAccount($tmp,true,$consign);
                }
                if ($product->type->type == "consign" || $product->type->type == 'foreign') {
                    $tmp = $unloading->tmps()->create([
                        'qt'            => $qt,
                        'product_id'    => $product_id
                    ]);
                    $this->addStock($product,$qt);
                    $this->addAccount($tmp);
                }
            }
        }
    }

    private function addStock(Product $product, int $qt)
    {
        $stock_truck = Stock::where([
            ['truck_id', $this->truck->id],
            ['product_id', $product->id],
        ])->first();
        $stock_truck->update([
            'qt'        => $stock_truck->qt - $qt
        ]);
        $stock_store = Stock::where([
            ['store_id', 1],
            ['product_id', $product->id],
        ])->first();
        $stock_store->update([
            'qt'        => $stock_store->qt + $qt
        ]);
    }

    private function addAccount(Tmp $tmp,$gaz = null,?Product $consign = null)
    {
        $detail = $tmp->product->type->type . " " . $tmp->product->size->size;
        if(!is_null($gaz)) {
            $detail = "Remplie " . $tmp->product->size->size;
        }
        $price = $tmp->product->prices()->orderby('id','desc')->first();
        $price_buy = $price->buy * $tmp->qt;
        if(!is_null($consign)) {
            $price_consign = $consign->prices()->orderby('id','desc')->first();
            $price_buy_consign = $price_consign->buy * $tmp->qt;
            $price_buy = $price_buy + $price_buy_consign;
        }
        // compte Stock Dépôt
        $account = $tmp->account_details()->create([
            'label'         => "Déchargement N° " . $this->unloading->id . " " . $this->truck->driver,
            'detail'        => $detail,
            'qt_enter'      => $tmp->qt,
            'qt_out',
            'db'            => $price_buy,
            'cr',
            'account_id'    => Account::where('account', 'Stock Dépôt')->first()->id,
            'created_at'        => $this->created_at
        ]);
        $tmp->product->partner->account_details()->attach($account->id);
        // compte camion
        $tmp->account_details()->create([
            'label'         => "Déchargement N° " . $this->unloading->id,
            'detail'        => $detail,
            'qt_enter',
            'qt_out'        => $tmp->qt,
            'db',
            'cr'            => $price_buy,
            'account_id'    => $this->truck->account_stock_id,
            'created_at'    => $this->created_at
        ]);
    }

    private function addPayments(array $data)
    {
        foreach ($data['payments'] as $key => $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price'     => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id'   => $payment['mode_id']
                ]);
                if ($payment->mode_id == 1) {
                    $this->addAccountPayment($payment);
                    $this->truck->update([
                        'cash'      => $this->truck->cash - $payment->price
                    ]);
                }
                if ($payment->mode_id == 2) {
                    $this->truck->update([
                        'cheque'  => $this->truck->cheque - $payment->price
                    ]);
                }
                $payment->loadings()->attach($this->unloading->id);
            }
        }
    }

    private function addAccountPayment(Payment $payment)
    {
        if ($payment->mode_id == 1) {
            // caisse Dépôt
            $payment->account_details()->create([
                'label'             => $this->truck->driver,
                'detail'            => "Déchargement " . $this->unloading->id,
                'db'                => $payment->price,
                'cr',
                'account_id'        => Account::where('account','Caisse Dépôt')->first()->id,
                'created_at'        => $this->created_at
            ]);
            // caisse Camion
            $payment->account_details()->create([
                'label'             => $this->truck->driver,
                'detail'            => "Déchargement " . $this->unloading->id,
                'db',
                'cr'                => $payment->price,
                'account_id'        => $this->truck->account_caisse_id,
                'created_at'        => $this->created_at
            ]);
        }
    }

    public function sub(Loading $unloading)
    {
        $this->created_at = $unloading->created_at;
        $this->subTmp($unloading);
        $this->subPayments($unloading);
        return $unloading;
    }

    private function subTmp(Loading $unloading)
    {
        foreach ($unloading->tmps as $tmp) {
            $product = $tmp->product;
            $provider = $product->partner;
            foreach ($tmp->account_details as $detail) {
                $provider->account_details()->detach($detail->id);
                // sub stock
                if ($tmp->product->type->type == 'gaz') {
                    $consign = Product::where([
                        ['size_id', $tmp->product->size_id],
                        ['type_id', 2],
                        ['partner_id', $tmp->product->partner_id],
                    ])->first();
                    $this->subStock($unloading,$tmp,$consign);
                }
                $this->subStock($unloading,$tmp,$product);
                $detail->delete();
                $tmp->delete();
            }
        }

    }

    private function subStock(Loading $loading,Tmp $tmp,Product $product)
    {
        $stock_truck = Stock::where([
            ['truck_id', $loading->truck->id],
            ['product_id', $product->id],
        ])->first();
        $stock_truck->update([
            'qt'        => $stock_truck->qt + $tmp->qt
        ]);
        $stock_store = Stock::where([
            ['store_id', 1],
            ['product_id', $product->id],
        ])->first();
        $stock_store->update([
            'qt'        => $stock_store->qt - $tmp->qt
        ]);
    }

    private function subPayments(Loading $unloading)
    {
        foreach ($unloading->payments as $payment) {
            if ($payment->mode_id == 1) {
                $this->truck->update([
                    'cash'      => $this->truck->cash + $payment->price
                ]);
            }
            if ($payment->mode_id == 2) {
                $this->truck->update([
                    'cheque'      => $this->truck->cheque + $payment->price
                ]);
            }
            $unloading->payments()->detach($payment->id);
            foreach ($payment->account_details as $detail) {
                $detail->delete();
            }
            $payment->delete();
        }
    }

    public function update(array $data, Loading $unloading)
    {
        $this->unloading = $unloading;
        $this->truck = $unloading->truck;
        $this->sub($unloading);
        $this->add($data);
        return $this->unloading;
    }
}