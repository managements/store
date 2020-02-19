<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargeTruck extends Model
{
    protected $fillable = ['truck_id', 'creator_id'];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'charge_truck_payment');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function charge_truck_details()
    {
        return $this->hasMany(ChargeTruckDetail::class);
    }
}
