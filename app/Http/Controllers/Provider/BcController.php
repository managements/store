<?php

namespace App\Http\Controllers\Provider;

use App\Bc;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trade\BcRequest;
use App\Intermediate;
use App\Partner;
use App\Storage\BcStorage;
use App\Storage\ProductStorage;
use App\Truck;

class BcController extends Controller
{
    public function create(ProductStorage $storage)
    {
        // providers
        $providers = Partner::where('provider',1)->get();
        // intermediates
        $intermediates = Intermediate::all();
        // products
        $products = $storage->getProducts($providers);
        // transporter
        $transporters = Truck::where('transporter',1)->get();
        return view('bc.create',compact('providers','intermediates','products','transporters'));
    }

    public function store(BcRequest $request, BcStorage $storage)
    {
        $storage->add($request->all());
        session()->flash('success', 'success');
        return redirect()->route('provider.links');
    }

    public function edit(Bc $bc,ProductStorage $storage)
    {
        // providers
        $providers = Partner::where('provider',1)->get();
        // intermediates
        $intermediates = Intermediate::all();
        // products
        $products = $storage->getOrderProducts($providers,$bc->orders);
        // transporter
        $transporters = Truck::where('transporter',1)->get();
        $truck = $bc->trade->truck;
        $inter = $bc->trade->intermediate;
        $partner = $bc->trade->partner;
        return view('bc.edit',compact('bc','providers', 'intermediates', 'products', 'transporters','truck','partner','inter'));
    }

    public function update(BcRequest $request, Bc $bc,BcStorage $storage)
    {
        $storage->sub($bc->trade);
        $bcStorage = new BcStorage();
            $bcStorage->add($request->all());
        session()->flash('success','success');
        return redirect()->route('provider.links');
    }
}
