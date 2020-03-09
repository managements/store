<?php

namespace App\Http\Controllers\Truck;

use App\Charge;
use App\ChargeTruck;
use App\Http\Controllers\Controller;
use App\Http\Requests\Truck\ChargeRequest;
use App\Storage\ChargeStorage;
use App\Truck;

class ChargeController extends Controller
{

    public function index()
    {
        $chargeTrucks = ChargeTruck::with(['payments','truck'])->get();
        return view('charge.index',compact('chargeTrucks'));
    }

    public function create()
    {
        $trucks = Truck::all();
        $charges = Charge::all();
        return view('charge.create',compact('trucks','charges'));
    }

    public function store(ChargeRequest $request,ChargeStorage $storage)
    {
        $storage->add($request->all());
        session()->flash('success', "success");
        return redirect()->route('saisie');
    }

    public function edit(ChargeTruck $charge)
    {
        $trucks = Truck::all();
        $charges = Charge::all();
        $payments = $charge->payments;
        // chargeTruck
        return view('charge.edit',compact('charge','charges','trucks','payments'));
    }

    public function update(ChargeRequest $request, ChargeTruck $chargeTruck,ChargeStorage $storage)
    {
        $storage->sub($chargeTruck);
        $charge = $storage->add($request->all());
        return redirect()->route('charge.edit',compact('charge'));
    }

    public function destroy(ChargeTruck $charge, ChargeStorage $storage)
    {
        $storage->sub($charge);
        session()->flash('success', 'success');
        return redirect()->route('charge.index');
    }

}
