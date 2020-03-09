<?php

namespace App\Http\Controllers\Loading;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Loading;
use App\Partner;
use App\Storage\LoadingStorage;
use App\Storage\ProductStorage;
use Illuminate\Http\Request;

class LoadingController extends Controller
{
    public function create(ProductStorage $storage)
    {
        // drivers
        $drivers = Driver::with(['staff','truck'])->where('to',null)->get();
        // providers
        $providers = Partner::where('provider', 1)->get();
        // products
        $products = $storage->getLoadingProducts($providers);
        return view('loading.create',compact('drivers','providers','products'));
    }

    public function store(Request $request,LoadingStorage $storage)
    {
        $loading = $storage->add($request->all());
        session()->flash('success', 'Bon de Chargement N° ' . $loading->id);
        return redirect()->route('loading.edit',compact('loading'));
    }

    public function edit(Loading $loading,ProductStorage $storage)
    {
        // drivers
        $drivers = Driver::with(['staff','truck'])->where('to',null)->get();
        // providers
        $providers = Partner::where('provider', 1)->get();
        // products
        $products = $storage->getLoadingEditProducts($providers,$loading);
        // payments
        $payments=  $loading->payments()->orderBy('id','asc')->get();

        return view('loading.edit',compact('drivers','providers','products','payments','loading'));
    }

    public function update(Request $request, Loading $loading,LoadingStorage $storage)
    {
        $loading = $storage->update($request->all(),$loading);
        session()->flash('success', 'Bon de Chargement N° ' . $loading->id);
        return redirect()->route('loading.edit',compact('loading'));
    }
}
