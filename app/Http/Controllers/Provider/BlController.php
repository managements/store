<?php

namespace App\Http\Controllers\Provider;

use App\Bl;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trade\BlRequest;
use App\Intermediate;
use App\Partner;
use App\Storage\BlStorage;
use App\Storage\PaymentStorage;
use App\Storage\ProductStorage;
use App\Truck;
use Illuminate\Http\Request;

class BlController extends Controller
{

    public function create(ProductStorage $storage)
    {
        // providers
        $providers = Partner::where('provider',1)->get();
        // intermediates
        $intermediates = Intermediate::all();
        // products
        $products = $storage->getGazProducts($providers);
        // transporter
        $transporters = Truck::where('transporter',1)->get();
        return view('bl.create',compact('providers','intermediates','products','transporters'));
    }

    public function store(BlRequest $request,BlStorage $storage)
    {
        $storage->add($request->all());
        session()->flash('success', 'success');
        return redirect()->route('provider.links');
    }

    public function edit(Bl $bl,ProductStorage $storage,PaymentStorage $paymentStorage)
    {
        // providers
        $providers = Partner::where('provider',1)->get();
        // intermediates
        $intermediates = Intermediate::all();
        // products
        $products = $storage->getGazOrderProducts($providers,$bl->orders);
        // transporter
        $transporters = Truck::where('transporter',1)->get();
        $truck = $bl->trade->truck;
        $inter = $bl->trade->intermediate;
        $partner = $bl->trade->partner;
        $payments = $paymentStorage->getPaymentBl($bl->trade);
        return view('bl.edit',compact('bl','providers', 'intermediates', 'products', 'transporters','truck','partner','inter','payments'));
    }

    public function update(BlRequest $request,Bl $bl, BlStorage $storage)
    {
        $storage->sub($bl);
        $blStorage = new BlStorage();
        $blStorage->add($request->all());
        session()->flash('success', 'success');
        return redirect()->route('provider.links');
    }
}
