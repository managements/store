<?php

namespace App;


class ProductType extends IdUuid
{
    protected $fillable = ['type'];
    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
