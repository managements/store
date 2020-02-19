<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeTruckDetail extends Model
{
    protected $fillable = ['label', 'price', 'charge_id', 'charge_truck_id', 'account_detail_id'];


    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }

    public function charge_truck()
    {
        return $this->belongsTo(ChargeTruck::class,'charge_truck_id');
    }

    public function account_detail()
    {
        return $this->belongsTo(AccountDetail::class);
    }

}
