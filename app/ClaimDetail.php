<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClaimDetail extends Model
{
    protected $fillable = ['claim_id', 'trade_id', 'bc_nbr', 'inv', 'term'];

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
