<?php

namespace App\Http\Controllers\Loading;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoadingRequest;
use App\Loading;
use App\Partner;
use App\Storage\ProductStorage;
use App\Storage\UnloadingStorage;

class UnloadingController extends Controller
{
    public function create(ProductStorage $storage)
    {
        // drivers
        $drivers = Driver::with(['staff','truck'])->where('to',null)->get();
        // providers
        $providers = Partner::where('provider', 1)->get();
        // products
        $products = $storage->getUnloadingProducts($providers);
        return view('unloading.create',compact('drivers','providers','products'));
    }

    public function store(LoadingRequest $request,UnloadingStorage $storage)
    {
        $unloading = $storage->add($request->all());
        session()->flash('success', 'Bon de Déchargement N° ' . $unloading->id);
        return redirect()->route('unloading.edit',compact('unloading'));
    }

    public function edit(Loading $unloading,ProductStorage $storage)
    {
        if (!$unloading->unloading) {
            return abort(404);
        }
        // drivers
        $drivers = Driver::with(['staff','truck'])->where('to',null)->get();
        // providers
        $providers = Partner::where('provider', 1)->get();
        // products
        $products = $storage->getUnloadingEditProducts($providers,$unloading);
        // payments
        $payments=  $unloading->payments()->orderBy('id','asc')->get();

        return view('unloading.edit',compact('drivers','providers','products','payments','unloading'));
    }

    public function update(LoadingRequest $request, Loading $unloading,UnloadingStorage $storage)
    {
        $unloading = $storage->update($request->all(),$unloading);
        session()->flash('success', 'Bon de Déchargement N° ' . $unloading->id);
        return redirect()->route('unloading.edit',compact('unloading'));
    }
}
