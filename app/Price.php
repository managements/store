<?php

namespace App;


class Price extends IdUuid
{
    protected $fillable = ['buy', 'sale', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
