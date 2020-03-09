<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['name'];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function store_charges()
    {
        return $this->hasMany(ChargeStore::class);
    }
}
