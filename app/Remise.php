<?php

namespace App;


class Remise extends IdUuid
{
    protected $fillable = ['remise', 'product_id', 'partner_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
