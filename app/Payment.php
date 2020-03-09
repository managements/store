<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['price', 'operation', 'mode_id'];

    public function trades()
    {
        return $this->belongsToMany(Trade::class,'payment_trade');
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class,'invoice_payment');
    }

    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    public function charge_trucks()
    {
        return $this->belongsToMany(ChargeTruck::class,'charge_truck_payment');
    }

    public function charge_stores()
    {
        return $this->belongsToMany(ChargeStore::class,'charge_store_payment','charge_store_id');
    }

    public function loadings()
    {
        return $this->belongsToMany(Loading::class,'loading_payment');
    }

    public function chequeClaims()
    {
        return $this->hasMany(Claim::class,'cheque_id');
    }

    public function transferClaims()
    {
        return $this->hasMany(Claim::class,'transfer_id');
    }

    public function cashClaims()
    {
        return $this->hasMany(Claim::class,'cash_id');
    }

    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_payment');
    }
}
