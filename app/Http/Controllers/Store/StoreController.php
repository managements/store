<?php

namespace App\Http\Controllers\Store;

use App\Account;
use App\AccountType;
use App\Http\Controllers\Controller;
use App\Order;
use App\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    private $accounts = [];

    /**
     * Bouton stock
     * Bouton Caisse
     * Bouton charge
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function links()
    {
        return view('store.links');
    }

    public function show(Partner $provider)
    {
        // trouver tous le compte de provider
        $dd = null;
        $trades = $provider->trades;
        foreach ($trades as $trade) {
            if ($trade->bc) {
                foreach ($trade->bc->orders as $order) {
                    $accounts = $order->account_details()
                        ->where('account_id', Account::where('account','Stock Dépôt')->first()->id)
                        ->get();
                    foreach ($accounts as $detail) {
                        $this->accounts[] = $detail;
                    }
                }
            }
        }

        return view('store.show',[
            'details'   => $this->accounts,
            'provider'  => $provider
        ]);

    }

    /**
     * @param Partner|null $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param Partner|null $partner
     * Affiché le compte stock avec ce Fournisseur de cette Année si exist si non affiché du premier
     * Affiché une select pour choisir un fournisseur
     */
    public function stock(?Partner $provider = null)
    {
        $providers = Partner::where('provider',1)->get();
        if (is_null($provider)) {
            $provider = $providers[0];
        }
        $details = $provider->account_details;
        return view('store.stock',compact('provider','details','providers'));
    }

    /**
     * affiché les détails du compte de Cette Année
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function caisse()
    {
        $account = Account::where('account','Caisse Dépôt')->first();
        $details = $account->details;
        return view('store.caisse',compact('account','details'));
    }

    /**
     * Affiché les Charges autre Fournisseur de cette Année
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function charge()
    {
        $account = Account::where('account','Charge Autre Fournisseur')->first();
        $details = $account->details;
        return view('store.charge',compact('account','details'));
    }
}
