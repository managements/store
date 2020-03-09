<?php

namespace App;


class Bc extends IdUuid
{
    protected $fillable = ['nbr', 'trade_id'];

    public function trade()
    {
        return $this->belongsTo(Trade::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
