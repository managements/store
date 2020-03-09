<?php

namespace App;


class Account extends IdUuid
{
    protected $fillable = ['account', 'account_type_id'];

    public function partners()
    {
        return $this->hasMany(Partner::class);
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class,'account_type_id');
    }

    public function caisse_truck()
    {
        return $this->hasOne(Truck::class,'account_caisse_id');
    }

    public function charge_truck()
    {
        return $this->hasOne(Truck::class,'account_charge_id');
    }

    public function stock_truck()
    {
        return $this->hasOne(Truck::class,'account_charge_id');
    }

    public function details()
    {
        return $this->hasMany(AccountDetail::class,'account_id');
    }
}
