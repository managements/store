<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = ["debt", "partner_id", "cheque_id", "transfer_id", "cash_id"];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function chaque()
    {
        return $this->belongsTo(Payment::class);
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
}
