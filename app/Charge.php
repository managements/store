<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $fillable = ['charge'];

    public $timestamps = false;

    public function charges_truck_details()
    {
        return $this->hasMany(ChargeTruckDetail::class,'charge_id');
    }
}
