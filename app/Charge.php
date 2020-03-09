<?php

namespace App;


class Charge extends IdUuid
{
    protected $fillable = ['charge'];

    public $timestamps = false;

    public function charges_truck_details()
    {
        return $this->hasMany(ChargeTruckDetail::class,'charge_id');
    }
}
