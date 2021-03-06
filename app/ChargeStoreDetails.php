<?php

namespace App;


class ChargeStoreDetails extends IdUuid
{
    protected $fillable = ['label', 'price', 'charge_id', 'charge_store_id', 'account_detail_id'];

    public function chargeStore()
    {
        return $this->belongsTo(ChargeStore::class);
    }

    public function account_detail()
    {
        return $this->belongsTo(AccountDetail::class,'account_detail_id');
    }
}
