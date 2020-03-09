<?php

namespace App;


class Stock extends IdUuid
{
    protected $fillable = ['qt', 'product_id', 'truck_id', 'partner_id', 'store_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function store()
    {
        return $this->hasMany(Store::class);
    }
}
