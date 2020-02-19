<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['nbr', 'from', 'to'];

    public function trades()
    {
        return $this->belongsToMany(Trade::class,'invoice_trade');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class,'invoice_payment');
    }
}
