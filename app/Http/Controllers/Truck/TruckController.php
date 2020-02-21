<?php

namespace App\Http\Controllers\Truck;

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
        $trucks = Truck::all();
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
        $driver = null;
        $assistant = null;
        if($t = Driver::where([
            ['to', null],
            ['truck_id', $truck->id]
        ])->first()){
            $driver = $t->staff->full_name;
        }
        if($t = Assistant::where([
            ['to', null],
            ['truck_id', $truck->id]
        ])->first()){
            $assistant = $t->staff->full_name;
        }
        // todo:: list of charges
        return view('truck.show',compact('truck','driver','assistant'));
    }

    public function edit(Truck $truck)
    {
        $drivers = Category::where('category','driver')->first()->staffs;
        $assistants = Category::where('category','assistant')->first()->staffs;
        $current_assistant = Assistant::where([
            ['to', null],
            ['truck_id', $truck->id]
        ])->first();
        $current_driver = Driver::where([
            ['to', null],
            ['truck_id', $truck->id]
        ])->first();
        return view('truck.edit',
            compact('truck',"current_assistant","current_driver","drivers","assistants")
        );

    }

    public function update(TruckRequest $request, Truck $truck,TruckStorage $storage)
    {
        $storage->update($request->all(),$truck);
        session()->flash('success', "Le Transport a bien été Mis à jour");
        return redirect()->route('truck.show',compact('truck'));
    }
}
