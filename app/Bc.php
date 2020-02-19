<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bc extends Model
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
