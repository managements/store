<?php

namespace App;


class ClaimDetail extends IdUuid
{
    protected $fillable = ['claim_id', 'trade_id', 'bl_nbr', 'inv', 'term'];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

    public function account_details()
    {
        return $this->belongsToMany(AccountDetail::class,'account_detail_claim_detail');
    }
}
