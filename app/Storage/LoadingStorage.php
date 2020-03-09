<?php

namespace App\Storage;


use App\Account;
use App\Driver;
use App\Loading;
use App\Payment;
use App\Product;
use App\Stock;
use App\Tmp;
use App\Truck;

class LoadingStorage
{
    private $loading;
    private $truck;
    private $driver;

    public function add(array $data, $unloading = null)
    {
        // truck
        $this->addLoading($data, $unloading);
        $this->addTmp($data, $this->loading);
        $this->payments($data, $this->loading);
        return $this->loading;
    }

    private function payments(array $data, Loading $loading)
    {
        foreach ($data['payments'] as $payment) {
            if ($payment['price']) {
                $payment = Payment::create([
                    'price' => $payment['price'],
                    'operation' => (isset($payment['operation'])) ? $payment['operation'] : null,
                    'mode_id' => $payment['mode_id']
                ]);
                $payment->loadings()->attach($loading->id);
                if ($payment->mode_id == 1) {
                    $this->addAccountPayment($payment);
                    $this->truck->update([
                        'cash' => $this->truck->cash + $payment->price
                    ]);
                }
                if ($payment->mode_id == 2) {
                    $this->truck->update([
                        'cheque' => $this->truck->cheque + $payment->price
                    ]);
                }
            }
        }
    }

    private function addTmp(array $data, Loading $loading)
    {
        foreach ($data['products'] as $product_id => $qt) {
            if (!is_null($qt) && $qt > 0) {
                // tmp
                $tmp = $loading->tmps()->create([
                    'product_id' => $product_id, 'qt' => $qt
                ]);
                $this->addStock($product_id, $qt, $this->truck, $tmp);
            }
        }
    }

    private function addLoading(array $data, $unloading = null)
    {
        $driver = Driver::find($data['driver']);
        $this->driver = $driver->staff->full_name;
        $truck_id = $driver->truck_id;
        $this->truck = Truck::find($truck_id);
        if (is_null($this->loading)) {
            $this->loading = Loading::create([
                'unloading' => (is_null($unloading)) ? 0 : 1, 'truck_id' => $truck_id,
                'partner_id' => $data['provider'],
            ]);
        } else {
            $this->loading->update([
                'truck_id' => $truck_id, 'partner_id' => $data['provider']
            ]);
        }
    }

    private function addStock(int $product_id, int $qt, Truck $truck, Tmp $tmp)
    {
        $product_gaz = Product::find($product_id);
        $product_consign = Product::where([
            ['size_id', $product_gaz->size_id], ['type_id', $product_gaz->type_id],
        ])->first();
        $stock_gaz_store = Stock::where([
            ['store_id', 1], ['product_id', $product_gaz->id]
        ])->first();
        $stock_consign_store = Stock::where([
            ['store_id', 1], ['product_id', $product_consign->id]
        ])->first();
        $stock_gaz_truck = Stock::where([
            ['truck_id', $truck->id], ['product_id', $product_gaz->id]
        ])->first();
        $stock_consign_truck = Stock::where([
            ['truck_id', $truck->id], ['product_id', $product_consign->id]
        ])->first();
        $stock_consign_store->update([
            'qt' => $stock_consign_store->qt - $qt
        ]);
        $stock_gaz_store->update([
            'qt' => $stock_gaz_store->qt - $qt
        ]);
        $stock_consign_truck->update([
            'qt' => $stock_consign_truck->qt + $qt
        ]);
        $stock_gaz_truck->update([
            'qt' => $stock_gaz_truck->qt + $qt
        ]);
        $this->addAccount($truck, $tmp, $product_gaz, $product_consign);
    }

    private function subStock(Tmp $tmp, Truck $truck)
    {
        $product_gaz = Product::find($tmp->product_id);
        $product_consign = Product::where([
            ['size_id', $product_gaz->size_id], ['type_id', $product_gaz->type_id],
        ])->first();
        $stock_gaz_store = Stock::where([
            ['store_id', 1], ['product_id', $product_gaz->id]
        ])->first();
        $stock_consign_store = Stock::where([
            ['store_id', 1], ['product_id', $product_consign->id]
        ])->first();
        $stock_gaz_truck = Stock::where([
            ['truck_id', $truck->id], ['product_id', $product_gaz->id]
        ])->first();
        $stock_consign_truck = Stock::where([
            ['truck_id', $truck->id], ['product_id', $product_consign->id]
        ])->first();
        $stock_consign_store->update([
            'qt' => $stock_consign_store->qt + $tmp->qt
        ]);
        $stock_gaz_store->update([
            'qt' => $stock_gaz_store->qt + $tmp->qt
        ]);
        $stock_consign_truck->update([
            'qt' => $stock_consign_truck->qt - $tmp->qt
        ]);
        $stock_gaz_truck->update([
            'qt' => $stock_gaz_truck->qt - $tmp->qt
        ]);
    }

    private function addAccount(Truck $truck, Tmp $tmp, Product $product_gaz, Product $product_consign)
    {
        // stock camion db
        $price_gaz = $product_gaz->prices()->orderby('id', 'desc')->first();
        $price_consign = $product_consign->prices()->orderby('id', 'desc')->first();
        $price = ($price_gaz->buy + $price_consign->buy) * $tmp->qt;
        $tmp->account_details()->create([
            'label' => "Chargement Camion " . $truck->driver,
            'detail' => "Remplie " . $product_gaz->size->size,
            'qt_enter' => $tmp->qt,
            'qt_out',
            'db' => $price,
            'cr',
            'account_id' => $truck->account_stock_id
        ]);
        // stock dépôt cr
        $account = $tmp->account_details()->create([
            'label' => "Chargement " . $this->driver,
            'detail' => "Remplie " . $product_gaz->size->size, 'qt_enter',
            'qt_out' => $tmp->qt,
            'db',
            'cr' => $price,
            'account_id' => Account::where('account', 'Stock Dépôt')->first()->id
        ]);
        $product_gaz->partner->account_details()->attach($account->id);
    }

    private function addAccountPayment(Payment $payment)
    {
        if ($payment->mode_id == 1) {
            // caisse dépôt cr
            $payment->account_details()->create([
                'label' => $this->truck->driver, 'detail' => "Chargement", 'db', 'cr' => $payment->price,
                'account_id' => Account::where('account', 'Caisse Dépôt')->first()->id
            ]);
            // caisse camion db
            $payment->account_details()->create([
                'label' => $this->truck->driver, 'detail' => "Chargement", 'db' => $payment->price, 'cr',
                'account_id' => $this->truck->account_caisse_id
            ]);
        }
    }

    public function update(array $data, Loading $loading, $unloading = null)
    {
        $this->loading = $loading;
        $this->truck = $loading->truck;
        $this->sub($loading);
        return $this->add($data, $unloading);
    }

    public function sub(Loading $loading)
    {
        $this->subPayment($loading);
        $this->subTmps($loading);
    }

    private function subPayment(Loading $loading)
    {
        foreach ($loading->payments as $payment) {
            if ($payment->mode_id == 1) {
                foreach ($payment->account_details as $account_detail) {
                    $account_detail->delete();
                }
                $this->truck->update([
                    'cash' => $this->truck->cash - $payment->price
                ]);
            }
            if ($payment->mode_id == 2) {
                $this->truck->update([
                    'cheque' => $this->truck->cheque - $payment->price
                ]);
            }
            $loading->payments()->detach($payment->id);
            $payment->delete();
        }
    }

    private function subTmps(Loading $loading)
    {
        foreach ($loading->tmps as $tmp) {
            $provider = $tmp->product->partner;
            $this->subStock($tmp, $this->truck);
            foreach ($tmp->account_details as $account_detail) {
                $provider->account_details()->detach($account_detail->id);
                $account_detail->delete();
            }
            $tmp->delete();
        }
    }

}
