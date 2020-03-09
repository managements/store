<?php

namespace App;


class Transaction extends IdUuid
{
    protected $fillable = ['sale_id', 'buy_id','created_at'];

    public function getTotalPriceAttribute()
    {
        if($this->sale) {
           return $this->sale->payments()->sum('price');
        }
        elseif ($this->buy){
            return $this->buy->payments()->sum('price');
        }
        return null;
    }

    public function sale()
    {
        return $this->belongsTo(Trade::class);
    }

    public function buy()
    {
        return $this->belongsTo(Trade::class);
    }

}
