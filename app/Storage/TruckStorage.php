<?php

namespace App\Storage;

use App\AccountType;
use App\Assistant;
use App\Driver;
use App\Size;
use App\Truck;

class TruckStorage
{
    public function add(array $data)
    {
        // account caisse
        $caisse_type = AccountType::where('type','caisses')->first();
        $account_caisse = $caisse_type->accounts()->create([
            'account'   => 'Caisse ' . $data['registered'],
        ]);
        // account charge
        $charge_type = AccountType::where('type','charges')->first();
        $account_charge = $charge_type->accounts()->create([
            'account'   => 'Charge ' . $data['registered'],
        ]);
        // create new Truck
        $truck = Truck::create([
            "registered"            => $data['registered'],
            "transporter"           => $data['transporter'],
            "assurance"             => $data['assurance'],
            "visit_technique"       => $data['visit_technique'],
            'account_caisse_id'     => $account_caisse->id,
            'account_charge_id'     => $account_charge->id,
            "creator_id"            => auth()->id()
        ]);
        $this->addStock($truck);
        // detach driver && detach Assistant && attach driver && attach assistant
        $this->detach_attach($data,$truck);
        return $truck;
    }

    public function addStock(Truck $truck)
    {
        $sizes = Size::all();
        foreach ($sizes as $size) {
            foreach ($size->products as $product) {
                if($product->type->type != 'foreign') {
                    $truck->stocks()->create([
                        'qt'            => 0,
                        'product_id'    => $product->id
                    ]);
                }
            }
        }
    }

    private function detach_attach(array $data, Truck $truck)
    {
        // detach driver && detach Assistant
        $this->detach_driver($data['driver']);
        $this->detach_assistant($data['assistant']);

        // attach driver && attach assistant
        $this->attach_driver($data['driver'],$truck);
        $this->attach_assistant($data['assistant'],$truck);
    }

    private function attach_driver(int $driver,Truck $truck)
    {
        return $truck->drivers()->create([
            'staff_id'      => $driver,
            'from'          => now()
        ]);
    }

    private function attach_assistant(int $assistant, Truck $truck)
    {
        return $truck->assistants()->create([
            'staff_id'      => $assistant,
            'from'          => now()
        ]);
    }

    private function detach_driver(int $driver_id)
    {
        if ($driver = Driver::where([['staff_id', $driver_id],['to', null]])->first()) {
            $driver->update([
                'to'    => now()
            ]);
        }
    }

    private function detach_assistant(int $assistant_id)
    {
        if ($assistant = Assistant::where([['staff_id', $assistant_id],['to', null]])->first()) {
            $assistant->update([
                'to'    => now()
            ]);
        }
    }

    public function update(array $data, Truck $truck)
    {
        // update name of accounts
        $truck->account_charge->update([
            'account'   => "Charge " . $data['registered']
        ]);
        $truck->account_caisse->update([
            'account'   => "Caisse " . $data['registered']
        ]);
        // update data of Truck
        $truck->update($data);
        // detach attach driver and assistant
        $this->detach_attach($data,$truck);
    }
}