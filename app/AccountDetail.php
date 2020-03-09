<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountDetail extends Model
{
    protected $fillable = ['label', 'detail', 'qt_enter', 'qt_out', 'db', 'cr', 'account_id'];

    public function trades()
    {
        return $this->belongsToMany(Trade::class,'account_detail_trade');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function charge_truck_detail()
    {
        return $this->hasOne(ChargeTruckDetail::class);
    }

    public function charge_store_detail()
    {
        return $this->hasOne(ChargeStoreDetails::class);
    }

    public function tmps()
    {
        return $this->belongsToMany(Tmp::class,'account_detail_tmp');
    }

    public function claim_details()
    {
        return $this->belongsToMany(ClaimDetail::class,'account_detail_claim_detail');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'account_detail_payment');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class,'account_detail_partner');
    }
}
