<?php

namespace App;


class Store extends IdUuid
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
