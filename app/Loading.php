<?php

namespace App;


class Loading extends IdUuid
{
    protected $fillable = ['unloading', 'valid', 'truck_id', 'partner_id'];

    public function tmps()
    {
        return $this->hasMany(Tmp::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'loading_payment');
    }
}
