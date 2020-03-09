<?php

namespace App;


class Claim extends IdUuid
{
    protected $fillable = ["debt", "partner_id", "cheque_id", "transfer_id", "cash_id", "creator_id"];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function cheque()
    {
        return $this->belongsTo(Payment::class,'cheque_id');
    }

    public function transfer()
    {
        return $this->belongsTo(Payment::class);
    }

    public function cash()
    {
        return $this->belongsTo(Payment::class);
    }

    public function claim_details()
    {
        return $this->hasMany(ClaimDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
}
