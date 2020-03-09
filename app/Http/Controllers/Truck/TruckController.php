<?php

namespace App\Http\Controllers\Truck;

use App\Account;
use App\Assistant;
use App\Category;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Truck\TruckRequest;
use App\Storage\TruckStorage;
use App\Truck;

class TruckController extends Controller
{
    public function index()
    {
        $trucks = Truck::with(['drivers.staff','assistants.staff'])->get();
        return view('truck.index',compact('trucks'));
    }

    public function create()
    {
        $drivers = Category::where('category','driver')->first()->staffs;
        $assistants = Category::where('category','assistant')->first()->staffs;
        return view('truck.create',compact('drivers','assistants'));
    }

    public function store(TruckRequest $request, TruckStorage $storage)
    {
        $truck = $storage->add($request->all());
        session()->flash('success', "Un nouveau Transport a bien été Ajouter");
        return redirect()->route('truck.show',compact('truck'));
    }

    public function show(Truck $truck)
    {
        $account_caisse = Account::where('account', 'Caisse ' . $truck->registered)->first();
        $account_charge = Account::where('account', 'Charge ' . $truck->registered)->first();
        $account_stock = Account::where('account', 'Stock ' . $truck->registered)->first();
        // todo:: list of charges
        return view('truck.show',compact('truck','account_stock','account_charge','account_caisse'));
    }

    public function edit(Truck $truck)
    {
        $drivers = Category::where('category','driver')->first()->staffs;
        $assistants = Category::where('category','assistant')->first()->staffs;
        return view('truck.edit', compact('truck',"drivers","assistants"));
    }

    public function update(TruckRequest $request, Truck $truck,TruckStorage $storage)
    {
        $storage->update($request->all(),$truck);
        session()->flash('success', "Le Transport a bien été Mis à jour");
        return redirect()->route('truck.show',compact('truck'));
    }
}
