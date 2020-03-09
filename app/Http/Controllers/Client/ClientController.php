<?php

namespace App\Http\Controllers\Client;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientRequest;
use App\Partner;
use App\Storage\ClientStorage;
use App\Storage\ProductStorage;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Partner::where('provider',0)->get();
        return view('client.index',compact('clients'));
    }

    public function show(Partner $client,ProductStorage $storage)
    {
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getDiscountProducts($providers,$client);
        $details = $client->compte->details;
        return view('client.show',compact('client','providers','products','details'));
    }

    public function edit(Partner $client,ProductStorage $storage)
    {
        $cities = City::all();
        $providers = Partner::where('provider',1)->get();
        $products = $storage->getDiscountProducts($providers,$client);
        return view('client.edit',compact('client','cities','providers','products'));
    }

    public function update(ClientRequest $request, Partner $client, ClientStorage $storage)
    {
        $storage->update($request->all(),$client);
        return redirect()->route('client.show',compact('client'));
    }
}
