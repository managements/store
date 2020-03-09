<?php

namespace App\Http\Controllers\Store;

use App\Charge;
use App\ChargeStore;
use App\Http\Controllers\Controller;
use App\Storage\ChargeStoreStorage;
use Illuminate\Http\Request;

class ChargeStoreController extends Controller
{

    public function create()
    {
        $charges = Charge::all();
        return view('charge_store.create',compact('charges'));
    }

    public function store(Request $request,ChargeStoreStorage $storage)
    {
        $charge_store = $storage->add($request->all());
        return redirect()->route('charge_store.edit',compact('charge_store'));
    }

    public function edit(ChargeStore $charge_store)
    {
        $charges = Charge::all();
        $payments = $charge_store->payments()->orderby('mode_id','asc')->get();
        // chargeTruck
        return view('charge_store.edit',compact('charge_store','charges','payments'));
    }

    public function update(Request $request,ChargeStore $charge_store, ChargeStoreStorage $storage)
    {
        $storage->sub($charge_store);
        $charge_store = $storage->add($request->all());
        return redirect()->route('charge_store.edit',compact('charge_store'));
    }

}
